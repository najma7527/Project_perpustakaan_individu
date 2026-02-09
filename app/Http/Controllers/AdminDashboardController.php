<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Visit;
use App\Models\User;
use App\Models\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $totalBorrow = Transaction::where('status', 'borrow')->count();

        // Total pengembalian
        $totalReturn = Transaction::where('status', 'return')->count();

        // Total pengunjung
        $totalVisit = Visit::count();


        // =====================
        // LIST DATA DASHBOARD
        // =====================

        // Pengunjung hari ini
        $todayVisit = Visit::whereDate('created_at', now())->get();

        // User menunggu approve (misal role siswa)
        $pendingUser = User::where('status', 'pending')->get();

        // Laporan kehilangan terbaru
        $latestReport = Report::latest()->take(5)->get();


        // =====================
        // KIRIM KE VIEW
        // =====================

        return view('admin.dashboard_admin', compact(
            'totalBook',
            'totalBorrow',
            'totalReturn',
            'totalVisit',
            'todayVisit',
            'pendingUser',
            'latestReport'
        ));
    }     
}
