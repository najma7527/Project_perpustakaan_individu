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

use Carbon\Carbon;


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
        ->when($start && $end, function ($q) use ($start, $end) {
            $q->whereBetween('tanggal_peminjaman', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ]);
        })
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
        ->when($start && $end, function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ]);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('cetak.laporan.cetak-kehilangan', compact('reports'));
}

public function filterKunjungan(Request $request)
{
    $start = $request->get('start_date');
    $end   = $request->get('end_date');
    
    $visits = Visit::with('user', 'transaction')
        ->when($start && $end, function ($q) use ($start, $end) {
            $q->whereBetween('tanggal_datang', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ]);
        })
        ->orderBy('tanggal_datang', 'desc')
        ->get();

    return view('cetak.laporan.cetak-daftar-pengunjung', compact('visits'));
}

    // =====================================================
    // 🔹 BUKU (PREVIEW + EXPORT)
    // =====================================================
    public function filterBuku(Request $request)
    {
        $books = $this->getBooks($request);
        return view('cetak.laporan.cetak-buku', compact('books'));
    }

    public function bukuExportPdf(Request $request)
    {
        $books = $this->getBooks($request);
        $kategori = $request->get('kategori');
        $pdf = Pdf::loadView('cetak.pdf.book-report', compact('books', 'kategori'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_buku.pdf');
    }

    public function bukuExportExcel(Request $request)
    {
        $kategori = $request->get('kategori');
        return Excel::download(new BukuExport($kategori), 'laporan-buku.xlsx');
    }

    // =====================================================
    // 🔹 ANGGOTA (PREVIEW + EXPORT)
    // =====================================================
    public function filterAnggota(Request $request)
    {
        $users = $this->getAnggotas($request);
        return view('cetak.laporan.cetak-anggota', compact('users'));
    }

    public function anggotaExportPdf(Request $request)
    {
        $users = $this->getAnggotas($request);
        $pdf = Pdf::loadView('cetak.pdf.anggota-report', compact('users'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_anggota.pdf');
    }

    // =====================================================
    // 🔹 TRANSAKSI - CETAK PER ID (tetap seperti lama)
    // =====================================================

    public function cetakNotaPdf($id, $jenis = 'peminjaman')
{
    $transaction = Transaction::with('user', 'book')->findOrFail($id);

    // Mapping ukuran kertas
    $paperSizes = [
        'peminjaman'   => [0, 0, 700, 350],  
        'pengembalian' => [0, 0, 700, 360],  
    ];

    // Ambil ukuran berdasarkan jenis, default A4
    $paperSize = $paperSizes[$jenis] ?? [0, 0, 340, 500];

    return Pdf::loadView('cetak.nota.cetak-transaksi', [
        'transaction' => $transaction,
        'jenis'       => $jenis
    ])
    ->setPaper($paperSize) // bisa array atau string
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
    public function transaksiExportExcel(Request $request)
    {
        // forward filter parameters to export class
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        $type  = $request->get('type');
        return Excel::download(new TransaksiExport($start, $end, $type), 'laporan-transaksi.xlsx');
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
              ->setPaper([0, 0, 330, 515])
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
    public function kehilanganExportExcel(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        return Excel::download(new KehilanganExport($start, $end), 'laporan-kehilangan.xlsx');
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
    public function kunjunganExportExcel(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        return Excel::download(new KunjunganExport($start, $end), 'laporan-kunjungan.xlsx');
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
            ->setPaper([0, 0, 520, 330]);

        return $pdf->download("kartu-anggota-{$user->nis_nisn}.pdf");
    }

    // =====================================================
    // 🔹 FILTER PAGE HELPERS
    // =====================================================

    private function getBooks(Request $request)
    {
        $kategori = $request->get('kategori');
        return \App\Models\KodeBuku::with('book.row.bookshelf')
            ->when($kategori && in_array($kategori, ['fiksi', 'nonfiksi']), fn($q) => $q->whereHas('book', fn($qb) => $qb->where('kategori_buku', $kategori)))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getAnggotas(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        $status = $request->get('status');
        return \App\Models\User::where('role', 'anggota')
            ->when($status && in_array($status, ['aktif','nonaktif']), fn($q) => $q->where('status', $status))
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // =====================================================
    // 🔹 CETAK KARTU ANGGOTA (ADMIN)
    // =====================================================

    public function exportKartuAdmin($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        $user = \App\Models\User::findOrFail($id);
        $pdf = Pdf::loadView('cetak.cetak-kartu', compact('user'))
            ->setPaper([0, 0, 520, 330]);

        return $pdf->download("kartu-anggota-{$user->nis_nisn}.pdf");
    }

    // =====================================================
    // 🔹 EXPORT EXCEL DATA ANGGOTA DITERIMA
    // =====================================================

    public function anggotaDiterimaExcel(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        return Excel::download(new AnggotaDiterimaExport($start, $end), 'data-anggota-diterima.xlsx');
    }

    // =====================================================
    // 🔹 EXPORT EXCEL DATA BUKU
    // =====================================================

    public function bukuExcel(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        $start = $request->get('start_date');
        $end   = $request->get('end_date');

        return Excel::download(new BukuExport($start, $end), 'data-buku.xlsx');
    }
}