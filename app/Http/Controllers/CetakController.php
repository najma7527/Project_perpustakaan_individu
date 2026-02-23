<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Transaction;
use App\Models\Report;
use App\Models\Visit;

use App\Exports\TransaksiExport;
use App\Exports\KehilanganExport;
use App\Exports\KunjunganExport;
use App\Exports\AnggotaDiterimaExport;
use App\Exports\BukuExport;


class CetakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =====================================================
    // 🔹 FILTER - halaman preview dengan data
    // =====================================================
    public function filterTransaksi(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        
        $transactions = Transaction::with('user', 'book')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_peminjaman', [$start, $end]))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();

        return view('cetak.laporan.cetak-transaksi', compact('transactions'));
    }

    public function filterKehilangan(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        
        $reports = Report::with(['user', 'transaction.book'])
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cetak.laporan.cetak-kehilangan', compact('reports'));
    }

    public function filterKunjungan(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        
        $visits = Visit::with('user', 'transaction')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_datang', [$start, $end]))
            ->orderBy('tanggal_datang', 'desc')
            ->get();

        return view('cetak.laporan.cetak-daftar-pengunjung', compact('visits'));
    }

    // =====================================================
    // 🔹 TRANSAKSI - CETAK PER ID (tetap seperti lama)
    // =====================================================

    public function cetakNotaPdf($id, $jenis = 'peminjaman')
{
    $transaction = Transaction::with('user', 'book')->findOrFail($id);

    if ($jenis === 'peminjaman') {
        $peminjam = (object)[
            'nama' => $transaction->user->name,
            'nis'  => $transaction->user->nis_nisn,
            'kelas'=> $transaction->user->kelas,
        ];
        $buku = (object)[
            'judul' => $transaction->book->judul,
            'kode'  => $transaction->book->kode_buku,
        ];
        $peminjaman = (object)[
            'tgl_pinjam'   => $transaction->tanggal_peminjaman?->format('d F Y'),
            'tgl_kembali'  => $transaction->tanggal_jatuh_tempo?->format('d F Y'),
            'batas_pinjam' => '8/5/2028', // sesuaikan logika kamu
        ];
    } else {
        $pengembalian = (object)[
            'nama_peminjam' => $transaction->user->name,
            'nis'           => $transaction->user->nis_nisn,
            'kelas'         => $transaction->user->kelas,
            'judul_buku'    => $transaction->book->judul,
            'kode_buku'     => $transaction->book->kode_buku,
            'tanggal_pinjam'=> $transaction->tanggal_peminjaman?->format('d F Y'),
            'batas_lama'    => $transaction->tanggal_jatuh_tempo?->format('d F Y'),
            'batas_baru'    => $transaction->tanggal_pengembalian?->format('d F Y') ?? $transaction->tanggal_jatuh_tempo?->format('d F Y'),
            'jumlah_hari'   => '0 Hari', // hitung selisih kalau perpanjangan
        ];
    }

    return Pdf::loadView('cetak.nota.cetak-transaksi', compact(
        $jenis === 'peminjaman' ? ['peminjam', 'buku', 'peminjaman', 'jenis'] 
                                : ['pengembalian', 'jenis']
    ))
    ->setPaper('A5', 'portrait')
    ->stream("nota-{$jenis}-{$id}.pdf");
}

    // =====================================================
    // 🔹 LAPORAN KESELURUHAN + EXPORT
    // =====================================================

    public function transaksiExportPdf(Request $request)
    {
        $transactions = $this->getTransactions($request);
        $pdf = Pdf::loadView('cetak.pdf.transaction-report', compact('transactions'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_transaksi.pdf');
    }

    // ✅ EXCEL TRANSAKSI (maatwebsite/excel v3)
    public function transaksiExportExcel()
    {
        return Excel::download(new TransaksiExport, 'laporan-transaksi.xlsx');
    }

    private function getTransactions(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        return Transaction::with('user', 'book')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_peminjaman', [$start, $end]))
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
    }

    // =====================================================
    // 🔹 KEHILANGAN
    // =====================================================

    public function pengembalianHilangPdf($id)
{
    $report = Report::with(['user', 'transaction.book'])->findOrFail($id);

    return Pdf::loadView('cetak.nota.cetak-buku-hilang', compact('report'))
              ->setPaper('A5', 'portrait')
              ->stream("pengembalian-buku-hilang-{$id}.pdf");
}

    public function kehilanganExportPdf(Request $request)
    {
        $reports = $this->getReports($request);
        $pdf = Pdf::loadView('cetak.pdf.report', compact('reports'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_kehilangan.pdf');
    }

    // ✅ EXCEL KEHILANGAN (maatwebsite/excel v3)
    public function kehilanganExportExcel()
    {
        return Excel::download(new KehilanganExport, 'laporan-kehilangan.xlsx');
    }

    private function getReports(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        return Report::with(['user', 'transaction.book'])
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // =====================================================
    // 🔹 KUNJUNGAN
    // =====================================================

    public function kunjunganExportPdf(Request $request)
    {
        $visits = $this->getVisits($request);
        $pdf = Pdf::loadView('cetak.pdf.visit', compact('visits'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_kunjungan.pdf');
    }

    // ✅ EXCEL KUNJUNGAN (maatwebsite/excel v3)
    public function kunjunganExportExcel()
    {
        return Excel::download(new KunjunganExport, 'laporan-kunjungan.xlsx');
    }

    private function getVisits(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        return Visit::with('user', 'transaction')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_datang', [$start, $end]))
            ->orderBy('tanggal_datang', 'desc')
            ->get();
    }

    // =====================================================
    // 🔹 ROUTE ALIASES (SUDAH DIBENARKAN!)
    // =====================================================
    public function transaksiPrint()     { return $this->transactionReport(request()); }
    public function transaksiPdf()       { return $this->transaksiExportPdf(request()); }
    public function kehilanganPrint()    { return $this->reportPrint(); }
    public function kehilanganPdf()      { return $this->kehilanganExportPdf(request()); }
    public function kehilanganExcel()    { return $this->kehilanganExportExcel(request()); }
    public function kunjunganPrint()     { return $this->visitPrint(); }
    public function kunjunganPdf()       { return $this->kunjunganExportPdf(request()); }
    public function kunjunganExcel()     { return $this->kunjunganExportExcel(request()); }

    public function kartuSiswa()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('cetak.cetak-kartu', compact('user'));
    }

    public function downloadKartuSiswa()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $pdf = Pdf::loadView('cetak.cetak-kartu', compact('user'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("kartu-anggota-{$user->nis_nisn}.pdf");
    }

    // =====================================================
    // 🔹 CETAK KARTU ANGGOTA (ADMIN)
    // =====================================================

    public function exportKartuAdmin($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        $user = \App\Models\User::findOrFail($id);
        $pdf = Pdf::loadView('cetak.cetak-kartu', compact('user'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("kartu-anggota-{$user->nis_nisn}.pdf");
    }

    // =====================================================
    // 🔹 EXPORT EXCEL DATA ANGGOTA DITERIMA
    // =====================================================

    public function anggotaDiterimaExcel()
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        return Excel::download(new AnggotaDiterimaExport, 'data-anggota-diterima.xlsx');
    }

    // =====================================================
    // 🔹 EXPORT EXCEL DATA BUKU
    // =====================================================

    public function bukuExcel()
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        return Excel::download(new BukuExport, 'data-buku.xlsx');
    }
}