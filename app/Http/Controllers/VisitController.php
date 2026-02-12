<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    $query = Visit::with('user', 'transaction.book');

    // 🔎 Search nama user
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // 📅 Filter tanggal
    if ($request->filled('date')) {
        $query->whereDate('tanggal_datang', $request->date);
    }

    $visits = $query->orderBy('tanggal_datang', 'desc')->get();

    return view('admin.daftar_pengunjung', compact('visits'));
}

    public function destroy(Visit $visit)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        try {
            $visit->delete();
            return response()->json(['success' => true, 'message' => 'Visit deleted'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus kunjungan: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Check-in kunjungan untuk anggota
     * Otomatis ambil transaksi aktif (status peminjaman) jika ada
     */
    public function checkIn(Request $request)
{
    $user = Auth::user();

    if ($user?->role !== 'anggota') {
        return response()->json([
            'message' => 'Hanya anggota yang bisa check-in'
        ], 403);
    }

    $existingVisit = Visit::whereDate('tanggal_datang', today())
        ->where('user_id', $user->id)
        ->first();

    if ($existingVisit) {
        return response()->json([
            'message' => 'Sudah check-in hari ini'
        ], 400);
    }

    $activeTransaction = Transaction::where('user_id', $user->id)
        ->where('status', 'dipinjam')
        ->latest('tanggal_peminjaman')
        ->first();

    Visit::create([
        'user_id' => $user->id,
        'transaction_id' => $activeTransaction?->id,
        'tanggal_datang' => today(),
    ]);

    return response()->json([
        'message' => 'Check-in berhasil'
    ]);
}

    /**
     * Get riwayat kunjungan dan transaksi untuk user
     */
    public function history()
    {
        $user = Auth::user();

        if ($user?->role !== 'anggota') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $visits = Visit::where('user_id', $user->id)
            ->with('transaction.book')
            ->orderBy('tanggal_datang', 'desc')
            ->get();

        $transactions = Transaction::where('user_id', $user->id)
            ->with('book')
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();

        return view('anggota.profile', compact('visits', 'transactions'));
    }
}
