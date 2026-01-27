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
        $query = Visit::with('user', 'transaction.book')->get();
        // 🔎 Search nama user
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // 🏫 Filter kelas
    if ($request->filled('kelas')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('kelas', $request->kelas);
        });
    }

    // 🔄 Filter status transaksi
    if ($request->filled('status')) {
        $query->whereHas('transaction', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    // 📅 Filter tanggal kunjungan
    if ($request->filled('date')) {
        $query->whereDate('tanggal_datang', $request->date);
    }

    $visits = $query->orderBy('tanggal_datang', 'desc')->get();

    // Data untuk dropdown filter
    $kelasList = User::select('kelas')->distinct()->pluck('kelas');
    $users     = User::where('role', 'anggota')->get();

    return view('visits.index', compact('visits', 'kelasList'));
    }
    public function destroy(Visit $visit)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $visit->delete();
        return response()->json(['message' => 'Visit deleted']);
    }

    /**
     * Check-in kunjungan untuk anggota
     * Otomatis ambil transaksi aktif (status peminjaman) jika ada
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        
        // Validasi user adalah anggota
        if ($user?->role !== 'anggota') {
            return response()->json(['message' => 'Hanya anggota yang bisa melakukan check-in'], 403);
        }

        // Cek apakah sudah pernah visit hari ini
        $existingVisit = Visit::whereDate('tanggal_datang', now()->toDateString())
            ->where('user_id', $user->id)
            ->first();

        if ($existingVisit) {
            return response()->json(['message' => 'Anda sudah melakukan check-in hari ini'], 400);
        }

        // Cari transaksi aktif (peminjaman) dari user
        $activeTransaction = Transaction::where('user_id', $user->id)
            ->where('status', 'peminjaman')
            ->latest('tanggal_peminjaman')
            ->first();

        // Buat record visit
        $visit = Visit::create([
            'user_id' => $user->id,
            'transactios_id' => $activeTransaction?->id,
            'tanggal_datang' => now()->toDateString(),
        ]);

        return back()->with('success', 'Berhasil check-in kunjungan');
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
