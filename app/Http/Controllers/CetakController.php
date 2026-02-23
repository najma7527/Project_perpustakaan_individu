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
    // 🔹 TRANSAKSI - CETAK PER ID
    // =====================================================

    public function transactionPrint($id)
    {
        $transaction = Transaction::with('user', 'book')->findOrFail($id);
        return view('print.transaction-id', compact('transaction'));
    }

    public function transactionPdf($id)
    {
        $transaction = Transaction::with('user', 'book')->findOrFail($id);

        return Pdf::loadView('pdf.transaction-id', compact('transaction'))
            ->setPaper('A5')
            ->stream("transaksi-$id.pdf");
    }

    // =====================================================
    // 🔹 TRANSAKSI - LAPORAN KESELURUHAN
    // =====================================================

    public function transactionReport(Request $request)
    {
        $transactions = Transaction::with('user', 'book')
            ->when($request->type, fn ($q) =>
                $q->where('type', $request->type)
            )->get();

        return view('print.transaction-report', compact('transactions'));
    }

    public function transactionReportPdf(Request $request)
    {
        $transactions = Transaction::with('user', 'book')
            ->when($request->type, fn ($q) =>
                $q->where('type', $request->type)
            )->get();

        return Pdf::loadView('pdf.transaction-report', compact('transactions'))
            ->setPaper('A4', 'landscape')
            ->download('laporan-transaksi.pdf');
    }

    // ✅ EXCEL TRANSAKSI (maatwebsite/excel v3)
    public function transaksiExcel()
    {
        return Excel::download(new TransaksiExport, 'laporan-transaksi.xlsx');
    }

    // =====================================================
    // 🔹 KEHILANGAN BUKU
    // =====================================================

    public function reportPrintById($id)
    {
        $report = Report::with(['user', 'transaction.book'])->findOrFail($id);
        return view('print.report-id', compact('report'));
    }

    public function reportPdfById($id)
    {
        $report = Report::with(['user', 'transaction.book'])->findOrFail($id);

        return Pdf::loadView('pdf.report-id', compact('report'))
            ->setPaper('A5')
            ->stream("kehilangan-$id.pdf");
    }

    public function reportPrint()
    {
        $reports = Report::with(['user', 'transaction.book'])->get();
        return view('print.report', compact('reports'));
    }

    public function reportPdf()
    {
        $reports = Report::with(['user', 'transaction.book'])->get();

        return Pdf::loadView('pdf.report', compact('reports'))
            ->download('laporan-kehilangan.pdf');
    }

    // ✅ EXCEL KEHILANGAN (maatwebsite/excel v3)
    public function reportExcel()
    {
        return Excel::download(new KehilanganExport, 'laporan-kehilangan.xlsx');
    }

    // =====================================================
    // 🔹 KUNJUNGAN
    // =====================================================

    public function visitPrint()
    {
        $visits = Visit::with('user')->get();
        return view('print.visit', compact('visits'));
    }

    public function visitPdf()
    {
        $visits = Visit::with('user')->get();

        return Pdf::loadView('pdf.visit', compact('visits'))
            ->download('laporan-kunjungan.pdf');
    }

    // ✅ EXCEL KUNJUNGAN (maatwebsite/excel v3)
    public function visitExcel()
    {
        return Excel::download(new KunjunganExport, 'laporan-kunjungan.xlsx');
    }

    // =====================================================
    // 🔹 ROUTE ALIASES (match route definitions)
    // =====================================================
    
    public function transaksiPrint() { return $this->transactionReport(request()); }
    public function transaksiPdf() { return $this->transactionReportPdf(request()); }
    public function kehilanganPrint() { return $this->reportPrint(); }
    public function kehilanganPdf() { return $this->reportPdf(); }
    public function kehilanganExcel() { return $this->reportExcel(); }
    public function kunjunganPrint() { return $this->visitPrint(); }
    public function kunjunganPdf() { return $this->visitPdf(); }
    public function kunjunganExcel() { return $this->visitExcel(); }

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
