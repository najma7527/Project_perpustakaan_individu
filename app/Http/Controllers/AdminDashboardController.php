<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Visit;
use App\Models\User;
use App\Models\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

class AdminDashboardController extends Controller
{
    public function index()
    {
         if (Auth::user()?->role !== 'admin') abort(403);
        // =====================
        // STATISTIK DASHBOARD
        // =====================

        // Total buku
        $totalBook = Book::count();

        // Total peminjaman
        $totalBorrow = Transaction::where('jenis_transaksi', 'dipinjam')->count();

        // Total pengembalian
        $totalReturn = Transaction::where('jenis_transaksi', 'dikembalikan')->count();

        // Total pengunjung
        $totalVisit = Visit::count();

        //Total Buku Hilang
        $totalLostBooks = Report::where('status', 'buku_hilang')->count();


        // =====================
        // LIST DATA DASHBOARD
        // =====================

        // Pengunjung hari ini
        $todayVisit = Visit::whereDate('tanggal_datang', now())->take(5)->get();


        // Laporan kehilangan terbaru
         $latestReport = Report::with(['transaction.user', 'transaction.book', 'user'])
        ->latest()
        ->take(5)
        ->get();


        // =====================
        // KIRIM KE VIEW
        // =====================

        return view('admin.dashboard_admin', compact(
        'totalBook',
        'totalBorrow',
        'totalReturn',
        'totalVisit',
        'totalLostBooks',
        'todayVisit',
        'latestReport'
    ));
    }     
}
