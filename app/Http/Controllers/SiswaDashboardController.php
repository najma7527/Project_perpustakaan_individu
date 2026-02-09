<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Visit;
use App\Models\Report;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()?->role !== 'anggota') abort(403);

        $userId = Auth::id();

        /* =======================
         * RINGKASAN DATA
         * ======================= */

        // Total buku yang sedang dipinjam siswa
        $totalDipinjam = Transaction::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        // Total buku terlambat
        $totalTerlambat = Transaction::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_pengembalian', '<', Carbon::today())
            ->count();

        // Status kunjungan hari ini
        $kunjunganHariIni = Visit::where('user_id', $userId)
            ->whereDate('tanggal_datang', Carbon::today())
            ->exists();

        /* =======================
         * LIST DATA TERBARU
         * ======================= */

        // Riwayat peminjaman terakhir
        $riwayatPeminjaman = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Laporan kehilangan terbaru
        $laporanKehilanganTerbaru = Report::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        /* =======================
         * KIRIM KE VIEW
         * ======================= */

        return view('siswa.dashboard-siswa', compact(
            'totalDipinjam',
            'totalTerlambat',
            'kunjunganHariIni',
            'riwayatPeminjaman',
            'laporanKehilanganTerbaru'
        ));
    }
}
