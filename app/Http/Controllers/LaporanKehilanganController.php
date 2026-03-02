<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class LaporanKehilanganController extends Controller
{
    public function create()
    {
        return view('siswa.laporan-kehilangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transactions_id' => 'required|exists:transactions,id',
            'tanggal_ganti' => 'required|date',
            'keterangan' => 'required|string|max:500'
        ]);

        // Pastikan user hanya bisa membuat laporan untuk transaksi mereka sendiri
        $transaction = \App\Models\Transaction::where('id', $request->transactions_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Report::create([
            'user_id' => Auth::id(),
            'transactions_id' => $request->transactions_id,
            'tanggal_ganti' => $request->tanggal_ganti,
            'keterangan' => $request->keterangan,
            'status' => 'belum_dikembalikan'
        ]);

        // Update status transaksi menjadi 'hilang'
        $transaction->update(['status' => 'buku_hilang']);

        return redirect()
            ->route('anggota.pengembalian')
            ->with('success', 'Laporan kehilangan berhasil dibuat. Buku ditandai sebagai hilang.');
    }

    public function index(Request $request)
    {
        $query = Report::where('user_id', Auth::id())
            ->with('transaction.book');

        // Search by book title or keterangan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('transaction.book', fn($b) => $b->where('judul', 'like', "%$search%"))
                  ->orWhere('keterangan', 'like', "%$search%");
            });
        }

        // Filter by date (tanggal pinjam)
        if ($request->filled('date')) {
            $query->whereHas('transaction', fn($t) => $t->whereDate('tanggal_peminjaman', $request->date));
        }

        // use pagination so large result sets won't time out and to match view
        $reports = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('siswa.laporan_kehilangan', compact('reports'));
    }

    public function edit($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('siswa.laporan-kehilangan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_ganti' => 'required|date',
            'keterangan' => 'required|string|max:500'
        ]);

        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($laporan->status !== 'belum_dikembalikan') {
            return back()->with('error', 'Laporan yang sudah diproses tidak bisa diubah');
        }

        $laporan->update([
            'tanggal_ganti' => $request->tanggal_ganti,
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan kehilangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($laporan->status !== 'belum_dikembalikan') {
            return back()->with('error', 'Hanya laporan yang belum diproses bisa dihapus');
        }

        // Reset status transaksi ke belum_dikembalikan jika laporan dihapus
        if ($laporan->transaction) {
            $laporan->transaction->update(['status' => 'belum_dikembalikan']);
        }

        $laporan->delete();

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan kehilangan berhasil dihapus');
    }

    /**
     * Anggota menandai laporan sebagai kembalikan (mengembalikan buku).
     */
    public function kembalikan($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('transaction.book')
            ->firstOrFail();

        if (!in_array($laporan->status, ['belum_dikembalikan', 'rejected'])) {
            return back()->with('error', 'Laporan tidak dapat diproses');
        }

        // Set laporan menjadi approved dan update transaksi + buku
        $laporan->update(['status' => 'pending']);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Buku berhasil dikembalikan');
    }
}
