<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Transaction;
use App\Models\Report;
use App\Models\Visit;


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

    // ✅ EXCEL TRANSAKSI (VERSI LAMA)
    public function transaksiExcel()
    {
        $transactions = Transaction::with('user', 'book')->get();

        Excel::create('laporan-transaksi', function ($excel) use ($transactions) {

            $excel->sheet('Transaksi', function ($sheet) use ($transactions) {

                $sheet->row(1, [
                    'No', 'Nama User', 'Judul Buku', 'Tipe', 'Tanggal'
                ]);

                $row = 2;
                foreach ($transactions as $t) {
                    $sheet->row($row++, [
                        $row - 2,
                        $t->user->name ?? '-',
                        $t->book->title ?? '-',
                        $t->type,
                        $t->created_at
                    ]);
                }

                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });

            });

        })->export('xlsx');
    }

    // =====================================================
    // 🔹 KEHILANGAN BUKU
    // =====================================================

    public function reportPrintById($id)
    {
        $report = Report::with('user', 'book')->findOrFail($id);
        return view('print.report-id', compact('report'));
    }

    public function reportPdfById($id)
    {
        $report = Report::with('user', 'book')->findOrFail($id);

        return Pdf::loadView('pdf.report-id', compact('report'))
            ->setPaper('A5')
            ->stream("kehilangan-$id.pdf");
    }

    public function reportPrint()
    {
        $reports = Report::with('user', 'book')->get();
        return view('print.report', compact('reports'));
    }

    public function reportPdf()
    {
        $reports = Report::with('user', 'book')->get();

        return Pdf::loadView('pdf.report', compact('reports'))
            ->download('laporan-kehilangan.pdf');
    }

    // ✅ EXCEL KEHILANGAN
    public function reportExcel()
    {
        $reports = Report::with('user', 'book')->get();

        Excel::create('laporan-kehilangan', function ($excel) use ($reports) {

            $excel->sheet('Kehilangan', function ($sheet) use ($reports) {

                $sheet->row(1, [
                    'No', 'Nama User', 'Judul Buku', 'Keterangan', 'Tanggal'
                ]);

                $row = 2;
                foreach ($reports as $r) {
                    $sheet->row($row++, [
                        $row - 2,
                        $r->user->name ?? '-',
                        $r->book->title ?? '-',
                        $r->description,
                        $r->created_at
                    ]);
                }

                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });

            });

        })->export('xlsx');
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

    // ✅ EXCEL KUNJUNGAN
    public function visitExcel()
    {
        $visits = Visit::with('user')->get();

        Excel::create('laporan-kunjungan', function ($excel) use ($visits) {

            $excel->sheet('Kunjungan', function ($sheet) use ($visits) {

                $sheet->row(1, [
                    'No', 'Nama User', 'Tanggal'
                ]);

                $row = 2;
                foreach ($visits as $v) {
                    $sheet->row($row++, [
                        $row - 2,
                        $v->user->name ?? '-',
                        $v->created_at
                    ]);
                }

                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });

            });

        })->export('xlsx');
    }
}
