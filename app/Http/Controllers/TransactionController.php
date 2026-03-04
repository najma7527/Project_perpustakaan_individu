<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Book;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of transactions (Admin only)
     */
    public function index(Request $request)
    {
        $mode = $request->mode ?? 'peminjaman'; // default peminjaman

        $query = Transaction::with(['user','book']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->whereHas('user', fn($qq) => $qq->where('name','like',"%$search%")
                                                    ->orWhere('kelas','like',"%$search%"))
                  ->orWhereHas('book', fn($qq) => $qq->where('judul','like',"%$search%"));
            });
        }

        // Filter tanggal (tanggal pinjam)
        if ($request->filled('date')) {
            $query->whereDate('tanggal_peminjaman', $request->date);
        }

        // Filter status berdasarkan mode tab
        if ($mode === 'peminjaman') {
            // Apply filter jika ada, atau default ke peminjaman
            if ($request->filled('filter')) {
                $query->whereIn('status', (array)$request->filter);
            } else {
                $query->whereIn('status', ['buku_hilang', 'belum_dikembalikan', 'terlambat']);
            }
        } elseif ($mode === 'pengembalian') {
            // Apply filter jika ada, atau default ke pengembalian
            if ($request->filled('filter')) {
                $query->whereIn('status', (array)$request->filter);
            } else {
                $query->whereIn('status', ['menunggu_konfirmasi', 'sudah_dikembalikan', 'ditolak']);
            }
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.transaksi', compact('transactions','mode'));
    }

    /**
     * Create form for new transaction (optional - can use browse instead)
     */
    public function create()
    {
        if (Auth::user()?->role !== 'anggota') {
            abort(403);
        }
        
        $books = Book::where('stok', '>', 0)->with('row')->get();

        $hasActiveLoan = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])
            ->exists();

        return view('siswa.pinjam-buku', compact('books', 'hasActiveLoan'));
    }

    /**
     * Store a new book borrowing transaction
     */
    public function pinjam(Request $request, $bukuId)
    {
        $buku = Book::findOrFail($bukuId);
        $visit = Visit::where('user_id', Auth::id())
        ->whereDate('tanggal_datang', now()->toDateString())
        ->first();

        $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_peminjaman',
        ]);

        // Cek apakah buku tersedia
        if ($buku->status !== 'tersedia') {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam');
        }

        // Cek apakah siswa sudah memiliki pinjaman aktif
        $hasActiveLoan = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])
            ->exists();

        if ($hasActiveLoan) {
            return back()->with('error', 'Anda masih memiliki buku yang belum dikembalikan. Kembalikan terlebih dahulu sebelum meminjam buku lain.');
        }

         $transaction = Transaction::create([
        'user_id' => Auth::id(),
        'buku_id' => $bukuId,
        'tanggal_peminjaman' => $request->tanggal_peminjaman,
        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        'jenis_transaksi' => 'dipinjam',
        'status' => 'belum_dikembalikan',
        ]);

        // Ubah status buku menjadi dipinjam
        $buku->update(['status' => 'dipinjam']);
        // Update visit jika ada
    $visitToday = Visit::where('user_id', Auth::id())
    ->whereDate('tanggal_datang', today())
    ->first();

if ($visitToday) {
    $visitToday->update([
        'transactions_id' => $transaction->id
    ]);
}

        return back()
        ->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam!')
        ->with('cetak.nota', $transaction->id);
    }

    /**
     * User mengajukan pengembalian buku (status menjadi 'menunggu_konfirmasi')
     */
    public function ajukanPengembalian($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'terlambat', 'ditolak'])
            ->firstOrFail();

        $transaction->update([
            'status' => 'menunggu_konfirmasi',
            'tanggal_pengembalian' => now(),
            'jenis_transaksi' => 'dikembalikan',
        ]);
        $visitToday = Visit::where('user_id', Auth::id())
    ->whereDate('tanggal_datang', today())
    ->first();

if ($visitToday) {
    $visitToday->update([
        'transactions_id' => $transaction->id
    ]);
}

        return back()->with('success', 'Pengajuan pengembalian berhasil, menunggu persetujuan admin');
    }

    /**
     * Alias for ajukanPengembalian (used by route)
     */
    public function returnBook($id)
    {
        return $this->ajukanPengembalian($id);
    }

    /**
     * Admin menerima pengembalian buku
     */
    public function terimaPengembalian($id)
    {
        $transaction = Transaction::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($transaction->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Status transaksi tidak valid untuk diterima');
        }

        $transaction->update([
            'status' => 'sudah_dikembalikan',
            'tanggal_pengembalian' => now(),
        ]);

        // Ubah status buku kembali menjadi tersedia
        $transaction->book->update(['status' => 'tersedia']);

        return back()->with('success', 'Pengembalian buku berhasil diterima');
    }

    /**
     * Admin menolak pengembalian buku
     */
    public function tolakPengembalian($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $transaksi = Transaction::findOrFail($id);

        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Status tidak valid');
        }

        $transaksi->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pengembalian buku ditolak, status kembali ke belum dikembalikan');
    }

    /**
     * User mengajukan pengembalian ulang setelah ditolak
     */
    public function ajukanUlang($id)
    {
        $transaksi = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'ditolak')
            ->firstOrFail();

        $transaksi->update([
            'status' => 'menunggu_konfirmasi'
        ]);
        $visitToday = Visit::where('user_id', Auth::id())
            ->whereDate('tanggal_datang', today())
            ->first();

        if ($visitToday) {
            $visitToday->update([
                'transactions_id' => $transaksi->id
            ]);
        }

        return back()->with('success', 'Pengajuan pengembalian ulang berhasil');
    }

    /**
     * Tandai buku sebagai hilang
     */
    public function tandaiHilang(Request $request, $id)
    {
        $transaksi = Transaction::findOrFail($id);

        // Bisa di-tandai hilang oleh anggota atau admin
        if (Auth::user()->id !== $transaksi->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (!in_array($transaksi->status, ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])) {
            return back()->with('error', 'Tidak bisa ditandai hilang');
        }

        $transaksi->update([
            'status' => 'buku_hilang',
            'tanggal_pengembalian' => now(),
        ]);

            $visitToday = Visit::where('user_id', Auth::id())
                ->whereDate('tanggal_datang', today())
                ->first();
        if ($visitToday) {
            $visitToday->update([
                'transactions_id' => $transaksi->id
            ]);
        }

        // Tetap tandai buku sebagai tersedia untuk bisa dipinjam lagi
        $transaksi->book->update(['status' => 'tersedia']);

        return back()->with('success', 'Buku berhasil dilaporkan hilang');
    }

    /**
     * Perpanjang peminjaman selama 3 hari
     */
    public function perpanjang($id)
    {
        $transaksi = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'terlambat'])
            ->firstOrFail();

        $transaksi->tanggal_jatuh_tempo = Carbon::parse($transaksi->tanggal_jatuh_tempo)->addDays(3);
        
        if ($transaksi->status === 'terlambat' && now()->lessThanOrEqualTo($transaksi->tanggal_jatuh_tempo)) {
            $transaksi->status = 'belum_dikembalikan';
        }

        $transaksi->save();

        return back()->with('success', 'Perpanjangan berhasil! Buku dapat dikembalikan dalam 3 hari lagi');
    }

    /**
     * Get user's own transactions
     */
    public function myTransactions(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::where('user_id', $user->id)
            ->with('book');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($qq) use ($q) {
                $qq->whereHas('book', fn($b) => $b->where('judul', 'like', "%$q%"))
                   ->orWhere('tanggal_peminjaman', 'like', "%$q%")
                   ->orWhere('tanggal_jatuh_tempo', 'like', "%$q%")
                   ->orWhere('tanggal_pengembalian', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal_peminjaman', $request->date);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('siswa.pengembalian-buku', compact('transactions'));
    }

    /**
     * Show transaction detail
     */
    public function show(Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load('user', 'book', 'reports', 'visits');
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show form for editing transaction (admin only)
     */
    public function edit(Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403);
        }

        $users = User::where('role', 'anggota')->get();
        $books = Book::all();

        return view('admin.transaksi.edit', compact('transaction', 'users', 'books'));
    }

    /**
     * Update transaction (admin only)
     */
    public function update(Request $request, Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after:tanggal_peminjaman',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',
        ]);

        $transaction->update($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Delete transaction (admin only)
     */
    public function destroy(Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403);
        }

        try {
            if (in_array($transaction->status, ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])) {
                // Kembalikan status buku ke tersedia
                $transaction->book->update(['status' => 'tersedia']);
            }

            $transaction->delete();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Search transactions
     */
    public function search(Request $request)
    {
        // redirect to myTransactions with query string parameters
        return redirect()->route('anggota.pengembalian', $request->only('q','search','status','date'));
    }

    public function cekJatuhTempo()
    {
        // reuse command which also sends notifications
        \Artisan::call('app:cek-keterlambatan');
        $output = trim(\Artisan::output());

        return response()->json([
            'message' => 'Pengecekan jatuh tempo selesai',
            'detail' => $output
        ]);
    }

    public function cekKeterlambatan()
    {
        // simply reuse same command so behaviour stays consistent
        return $this->cekJatuhTempo();
    }
}