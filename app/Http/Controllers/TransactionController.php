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

    public function pinjam($bukuId)
{
    $buku = Book::findOrFail($bukuId);

    $totalBuku = Book::where('judul', $buku->judul)->count();

    $dipinjam = Transaction::whereHas('book', function ($q) use ($buku) {
        $q->where('judul', $buku->judul);
    })->whereIn('status', ['dipinjam', 'menunggu'])
        ->count();


    if ($dipinjam >= $totalBuku) {
        return back()->with('error', 'Buku tidak tersedia');
    }

    Transaction::create([
        'user_id' => Auth::id(),
        'buku_id' => $bukuId,
        'tanggal_peminjaman' => now(),
        'status' => 'dipinjam',
    ]);

    return back()->with('success', 'Buku berhasil dipinjam');
}


    public function ajukanPengembalian($Id)
    {

    $transaction = Transaction::where('id', $Id)
        ->where('user_id', Auth::id())
        ->where('status', 'dipinjam')
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
    $transaksi = Transaction::findOrFail($id);

    if (!in_array($transaksi->status, ['dipinjam', 'menunggu'])) {
        return back()->with('error', 'Tidak bisa ditandai hilang');
    }

    $transaksi->update([
        'status' => 'hilang'
    ]);

    return back()->with('success', 'Buku ditandai hilang');
}


    public function show(Transaction $transaction)
    {
            if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) abort(403);
            return response()->json(['data' => $transaction->load('book', 'user', 'reports', 'visits')]);
    }

    public function edit(Transaction $transaction)
    {
            if (Auth::user()?->role !== 'admin') abort(403);
            $users = User::where('role', 'anggota')->get();
            $books = Book::get();
            return response()->json(['data' => $transaction, 'users' => $users, 'books' => $books]);
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
        return response()->json(['message' => 'Transaction updated', 'data' => $transaction->load('user', 'book')]);
    }


    public function destroy(Transaction $transaction)
    {
        // admin or owner can delete
        if (Auth::user()?->role !== 'admin') abort(403);

        // If deleting a dipinjam transaction, restore stock
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted']);
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
        ->where('status', 'dipinjam')
        ->firstOrFail();

    // Cek kalau sudah lewat jatuh tempo
    if (now()->greaterThan($transaksi->tanggal_jatuh_tempo)) {
        return back()->with('error', 'Tidak bisa perpanjang, sudah melewati jatuh tempo');
    }

    // Tambah 3 hari
    $transaksi->update([
        'tanggal_jatuh_tempo' => \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->addDays(3)
    ]);

    return back()->with('success', 'Perpanjangan berhasil 3 hari');
}

}

    