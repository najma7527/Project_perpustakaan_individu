<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Visit;
use App\Models\Report;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Book;

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
            ->where('status', 'belum_dikembalikan')
            ->count();
        //total buku hilang
        $totalBukuHilang = Report::whereHas('transaction', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        //total pengembalian buku
        $totalPengembalian = Transaction::where('user_id', $userId)
            ->where('jenis_transaksi', 'dikembalikan')
            ->count();

        // Total buku terlambat
                $totalTerlambat = Transaction::where('user_id', $userId)
                    ->where('jenis_transaksi', 'dipinjam')
                    ->where('status', 'terlambat')
                    ->whereDate('tanggal_jatuh_tempo', '<', Carbon::today())
                    ->count();

        /* =======================
         * LIST DATA TERBARU
         * ======================= */

        // Riwayat peminjaman terakhir
        $riwayatPeminjaman = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

            
        // Status kunjungan hari ini
                $kunjunganHariIni = Visit::where('user_id', $userId)
                    ->whereDate('tanggal_datang', Carbon::today())
                    ->exists();

        // Ambil notifikasi terbaru yang belum dibaca
        $notifications = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = $notifications->count();



        /* =======================
         * KIRIM KE VIEW
         * ======================= */

        return view('siswa.dashboard-siswa', compact(
            'totalDipinjam',
            'totalTerlambat',
            'totalPengembalian',
            'totalBukuHilang',
            'kunjunganHariIni',
            'riwayatPeminjaman',
            'notifications'
        ));
    }
}
