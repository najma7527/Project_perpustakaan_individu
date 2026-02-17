<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar laporan data hilang (Admin)
     */
    public function index(Request $request)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    $query = Report::with(['transaction.user', 'transaction.book', 'user']);

    // Filter status
    if ($request->status !== null && $request->status !== '') {
        $query->where('status', $request->status);
    }

    // Search
    if ($request->search) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            $q->whereHas('transaction.user', function ($qq) use ($search) {
                $qq->where('name', 'like', "%$search%");
            })
            ->orWhereHas('transaction.book', function ($qq) use ($search) {
                $qq->where('judul', 'like', "%$search%");
            });
        });
    }

    $reports = $query->latest()->paginate();
    $statuses = ['buku_hilang', 'sudah_dikembalikan', 'belum_dikembalikan'];

    return view('admin.laporan_data_kehilangan', compact('reports', 'statuses'));
}

    /**
     * Tampilkan form buat laporan (API)
     */
    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        // Ambil transaksi dengan status belum_dikembalikan yang belum ada laporannya
        $transactions = Transaction::whereDoesntHave('reports')
            ->where('status', 'belum_dikembalikan')
            ->with(['user', 'book'])
            ->get();

        if (request()->wantsJson() || request()->isXmlHttpRequest()) {
            return response()->json(['transactions' => $transactions]);
        }

        return view('admin.reports.create', compact('transactions'));
    }

    /**
     * Simpan laporan baru
     */
    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'transactions_id' => 'required|exists:transactions,id',
            'tanggal_ganti' => 'nullable|date',
            'jenis_transaksi' => 'required|in:dipinjam,dikembalikan',
            'status' => 'required|in:buku_hilang,sudah_dikembalikan,belum_dikembalikan',
            'keterangan' => 'required|string|max:500',
        ]);

        try {
            $data['user_id'] = Auth::id();
            $report = Report::create($data);

            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dibuat',
                    'data' => $report->load('transaction.user', 'transaction.book')
                ], 201);
            }

            return redirect()->route('reports.index')
                ->with('success', 'Laporan berhasil dibuat');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat laporan: ' . $e->getMessage()
                ], 400);
            }

            return back()->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail laporan (API)
     */
    public function show(Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $report->load(['transaction.user', 'transaction.book', 'user']);

        if (request()->wantsJson() || request()->isXmlHttpRequest()) {
            return response()->json(['data' => $report]);
        }

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Tampilkan form edit laporan (API)
     */
    public function edit(Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $report->load(['transaction.user', 'transaction.book']);
        $transactions = Transaction::with(['user', 'book'])->get();

        if (request()->wantsJson() || request()->isXmlHttpRequest()) {
            return response()->json([
                'data' => $report,
                'transactions' => $transactions
            ]);
        }

        return view('admin.reports.edit', compact('report', 'transactions'));
    }

    /**
     * Update laporan
     */
    public function update(Request $request, Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'tanggal_ganti' => 'nullable|date',
            'jenis_transaksi' => 'required|in:dipinjam,dikembalikan',
            'status' => 'required|in:buku_hilang,sudah_dikembalikan,belum_dikembalikan',
            'keterangan' => 'required|string|max:500',
        ]);

        try {
            $report->update($data);

            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diupdate',
                    'data' => $report->load('transaction.user', 'transaction.book')
                ], 200);
            }

            return redirect()->route('reports.index')
                ->with('success', 'Laporan berhasil diupdate');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate laporan: ' . $e->getMessage()
                ], 400);
            }

            return back()->with('error', 'Gagal mengupdate laporan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus laporan
     */
    public function destroy(Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        try {
            $report->delete();

            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dihapus'
                ], 200);
            }

            return redirect()->route('reports.index')
                ->with('success', 'Laporan berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus laporan: ' . $e->getMessage()
                ], 400);
            }

            return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Filter laporan berdasarkan status
     */
    public function getByStatus(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $status = $request->query('status');
        $reports = Report::where('status', $status)
            ->with(['transaction.user', 'transaction.book'])
            ->latest()
            ->get();

        return response()->json(['data' => $reports]);
    }
    
}
