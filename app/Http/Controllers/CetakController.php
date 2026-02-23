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
    // 🔹 LAPORAN KESELURUHAN + EXPORT
    // =====================================================
    public function transactionReport(Request $request)
    {
        $transactions = Transaction::with('user', 'book')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->get();
        return view('print.transaction-report', compact('transactions'));
    }

    public function transaksiExportPdf(Request $request)
    {
        $transactions = $this->getTransactions($request);
        $pdf = Pdf::loadView('cetak.pdf.transaction-report', compact('transactions'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_transaksi.pdf');
    }

    public function transaksiExportExcel(Request $request)
    {
        $transactions = $this->getTransactions($request);

        Excel::create('laporan-transaksi', function ($excel) use ($transactions) {
            $excel->sheet('Transaksi', function ($sheet) use ($transactions) {
                $sheet->row(1, ['No', 'Nama Anggota', 'Judul Buku', 'Kelas', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status']);
                $row = 2;
                foreach ($transactions as $t) {
                    $sheet->row($row++, [
                        $row - 2,
                        $t->user->name ?? '-',
                        $t->book->judul ?? '-',
                        $t->user->kelas ?? '-',
                        $t->tanggal_peminjaman,
                        $t->tanggal_jatuh_tempo,
                        $t->tanggal_pengembalian,
                        $t->status
                    ]);
                }
                $sheet->row(1, function ($r) { $r->setFontWeight('bold'); });
            });
        })->export('xlsx');
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
    public function reportPrintById($id)
    {
        $report = Report::with(['user', 'transaction.book'])->findOrFail($id);
        return view('print.report-id', compact('report'));
    }

    public function reportPdfById($id)
    {
        $report = Report::with(['user', 'transaction.book'])->findOrFail($id);
        return Pdf::loadView('report-id', compact('report'))
            ->setPaper('A5')
            ->stream("kehilangan-$id.pdf");
    }

    public function reportPrint()
    {
        $reports = Report::with(['user', 'transaction.book'])->get();
        return view('print.report', compact('reports'));
    }

    public function kehilanganExportPdf(Request $request)
    {
        $reports = $this->getReports($request);
        $pdf = Pdf::loadView('cetak.pdf.report', compact('reports'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_kehilangan.pdf');
    }

    public function kehilanganExportExcel(Request $request)
    {
        $reports = $this->getReports($request);

        Excel::create('laporan-kehilangan', function ($excel) use ($reports) {
            $excel->sheet('Kehilangan', function ($sheet) use ($reports) {
                $sheet->row(1, ['No', 'Nama Anggota', 'Kelas', 'Judul Buku', 'Transaksi', 'Tanggal Laporan', 'Status']);
                $row = 2;
                foreach ($reports as $r) {
                    $sheet->row($row++, [
                        $row - 2,
                        $r->user->name ?? $r->transaction->user->name ?? '-',
                        $r->user->kelas ?? $r->transaction->user->kelas ?? '-',
                        $r->transaction->book->judul ?? '-',
                        $r->jenis_transaksi ?? ($r->transaction->jenis_transaksi ?? '-'),
                        $r->created_at->format('Y-m-d'),
                        $r->status == 'sudah_dikembalikan' ? 'Sudah Diganti' : 'Belum Diganti'
                    ]);
                }
                $sheet->row(1, function ($r) { $r->setFontWeight('bold'); });
            });
        })->export('xlsx');
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
    public function visitPrint()
    {
        $visits = Visit::with('user')->get();
        return view('print.visit', compact('visits'));
    }

    public function kunjunganExportPdf(Request $request)
    {
        $visits = $this->getVisits($request);
        $pdf = Pdf::loadView('cetak.pdf.visit', compact('visits'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_kunjungan.pdf');
    }

    public function kunjunganExportExcel(Request $request)
    {
        $visits = $this->getVisits($request);

        Excel::create('laporan-kunjungan', function ($excel) use ($visits) {
            $excel->sheet('Kunjungan', function ($sheet) use ($visits) {
                $sheet->row(1, ['No', 'Nama Anggota', 'Kelas', 'Judul Buku', 'Transaksi', 'Tanggal Datang']);
                $row = 2;
                foreach ($visits as $v) {
                    $sheet->row($row++, [
                        $row - 2,
                        $v->user->name ?? '-',
                        $v->user->kelas ?? '-',
                        $v->transaction->book->judul ?? '-',
                        $v->transaction->jenis_transaksi ?? '-',
                        $v->tanggal_datang
                    ]);
                }
                $sheet->row(1, function ($r) { $r->setFontWeight('bold'); });
            });
        })->export('xlsx');
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
        return view('cetak.kartu-siswa', compact('user'));
    }
}