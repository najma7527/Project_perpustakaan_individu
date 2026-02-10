<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    $transactions = Transaction::with(['user', 'book'])->get();

    return view('admin.transaksi', compact('transactions'));
}

public function pinjam(Request $request, $bukuId)
{
    $buku = Book::findOrFail($bukuId);

    $request->validate([
        'tanggal_peminjaman' => 'required|date',
        'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_peminjaman',
    ]);

    if ($buku->stok <= 0) {
        return back()->with('error', 'Buku tidak tersedia');
    }

    Transaction::create([
        'user_id' => Auth::id(),
        'buku_id' => $bukuId,
        'tanggal_peminjaman' => $request->tanggal_peminjaman,
        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        'jenis_transaksi' => 'dipinjam',
        'status' => 'belum_dikembalikan',
    ]);

    $buku->decrement('stok');

    return back()->with('success', 'Buku berhasil dipinjam');
}

    public function ajukanPengembalian($Id)
    {

    $transaction = Transaction::where('id', $Id)
        ->where('user_id', Auth::id())
        ->where('status', 'belum_dikembalikan')
        ->firstOrFail();
    
       $transaction->update([
        'status' => 'menunggu',
         ]);
        return back()->with('success', 'Menunggu persetujuan admin');
    }

    public function terimaPengembalian($id)
    {
        $transaction = Transaction::findOrFail($id);

            if (Auth::user()->role !== 'admin') abort(403);

            if ($transaction->status !== 'menunggu') {
            return back()->with('error', 'status tidak valid');
            }

        $transaction->update([
            'status' => 'dikembalikan',
            'tanggal_pengembalian' => now(),
        ]);

        $transaction->book->increment('stok');

        return back()->with('success', 'Pengembalian diterima');
    }

    public function tolakPengembalian($id)
{

            if (Auth::user()->role !== 'admin') abort(403);

            $transaksi = Transaction::findOrFail($id);

            if ($transaksi->status !== 'menunggu') {
            return back()->with('error', 'Status tidak valid');
    }

        $transaksi->update([
            'status' => 'ditolak'
     ]);

            return back()->with('success', 'Pengembalian ditolak');
}

   public function ajukanUlang($id)
{
        $transaksi = Transaction::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'ditolak')
        ->firstOrFail();
        $transaksi->update([
            'status' => 'menunggu'
    ]);

        return back()->with('success', 'Pengajuan ulang berhasil');


}
    public function tandaiHilang($id)
{
    if (Auth::user()->role !== 'admin') abort(403);
    
    $transaksi = Transaction::findOrFail($id);

    if (!in_array($transaksi->status, ['belum_dikembalikan', 'menunggu'])) {
        return back()->with('error', 'Tidak bisa ditandai hilang');
    }

    $transaksi->update([
        'status' => 'hilang'
    ]);

    return back()->with('success', 'Buku ditandai hilang');
}


    public function show(Transaction $transaction)
    {
            if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) {
                abort(403);
    }
          $transaction->load('user', 'book', 'reports', 'visits');

          return view('transactions.show', compact('transaction'));
}
    public function edit(Transaction $transaction)
    {
            if (Auth::user()?->role !== 'admin') abort(403);
            $users = User::where('role', 'anggota')->get();
            $books = Book::all();

            return view('admin.transaksi.edit', compact('transaction', 'users', 'books'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after:tanggal_peminjaman',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',

        ]);

        $transaction->update($data);
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaction berhasil diupdate');
    }

    public function destroy(Transaction $transaction)
    {
        // admin or owner can delete
        if (Auth::user()?->role !== 'admin') abort(403);

        try {
            if (in_array($transaction->status, ['belum_dikembalikan', 'menunggu'])) {
                $transaction->book->increment('stok');
            }

            // If deleting a dipinjam transaction, restore stock
            $transaction->delete();
            
            return redirect()->route('transactions.index')
                ->with('success', 'Transaction berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function myTransactions()
    {

        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->with('book')->get();
        return view('siswa.pengembalian-buku', compact('transactions'));
    }

    public function perpanjang($id)
{
    $transaksi = Transaction::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'belum_dikembalikan')
        ->firstOrFail();

    // Cek kalau sudah lewat jatuh tempo
    if (now()->greaterThan($transaksi->tanggal_jatuh_tempo)) {
        return back()->with('error', 'Tidak bisa perpanjang, sudah melewati jatuh tempo');
    }

    //Tambah 3 hari
        $transaksi->tanggal_jatuh_tempo = now()->addDays(3);
        $transaksi->save();

        return back()->with('success', 'Perpanjangan berhasil 3 hari');
    }

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

    return view('siswa.transaksi', compact('transactions'));

    }

}


    