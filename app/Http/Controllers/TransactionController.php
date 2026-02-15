<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Book;
use App\Models\Visit;
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
        if (Auth::user()?->role !== 'admin') {
            abort(403);
        }

        $mode = $request->get('mode', 'peminjaman');

        $transactions = Transaction::with(['user', 'book'])
            ->when($mode === 'peminjaman', function($q) {
                $q->whereIn('status', ['buku_hilang', 'belum_dikembalikan']);
            })
            ->when($mode === 'pengembalian', function($q) {
                $q->whereIn('status', ['menunggu_konfirmasi', 'sudah_dikembalikan']);
            })
                ->latest()
                ->paginate(10);

        return view('admin.transaksi', compact('transactions', 'mode'));
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
        return view('siswa.pinjam-buku', compact('books'));
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
    if ($visit) {
        $visit->update([
            'transaction_id' => $transaction->id
        ]);
    }

        return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam!');
    }

    /**
     * User mengajukan pengembalian buku (status menjadi 'menunggu')
     */
    public function ajukanPengembalian($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'belum_dikembalikan')
            ->firstOrFail();

        $transaction->update([
            'status' => 'menunggu_konfirmasi',
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil, menunggu persetujuan admin');
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
            'status' => 'belum_dikembalikan'
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
            ->where('status', 'belum_dikembalikan')
            ->firstOrFail();

        $transaksi->update([
            'status' => 'menunggu_konfirmasi'
        ]);

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

        if (!in_array($transaksi->status, ['belum_dikembalikan', 'menunggu'])) {
            return back()->with('error', 'Tidak bisa ditandai hilang');
        }

        $transaksi->update([
            'status' => 'hilang',
            'tanggal_pengembalian' => now(),
        ]);

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
            ->where('status', 'belum_dikembalikan')
            ->firstOrFail();

        // Cek sudah lewat jatuh tempo atau belum
        if (now()->greaterThan($transaksi->tanggal_jatuh_tempo)) {
            return back()->with('error', 'Tidak bisa perpanjang, sudah melewati jatuh tempo');
        }

        // Tambah 3 hari
        $transaksi->tanggal_jatuh_tempo = $transaksi->tanggal_jatuh_tempo->addDays(3);
        $transaksi->save();

        return back()->with('success', 'Perpanjangan berhasil! Buku dapat dikembalikan dalam 3 hari lagi');
    }

    /**
     * Get user's own transactions
     */
    public function myTransactions()
    {
        $user = Auth::user();
        // paginate user's transactions so view pagination works
        $transactions = Transaction::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->paginate(10);

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
            if (in_array($transaction->status, ['belum_dikembalikan', 'menunggu'])) {
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
        $q = $request->q;

        $transactions = Transaction::where('user_id', Auth::id())
            ->where(function ($query) use ($q) {
                $query->where('tanggal_peminjaman', 'like', "%$q%")
                    ->orWhere('tanggal_pengembalian', 'like', "%$q%")
                    ->orWhere('tanggal_jatuh_tempo', 'like', "%$q%");
            })
            ->with('book')
            ->get();

        return view('siswa.pengembalian-buku', compact('transactions'));
    }
}

    