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

    public function index()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $reports = Report::with('transaction.user', 'transaction.book')->get();
        return response()->json(['data' => $reports]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $transactions = Transaction::where('status', 'dikembalikan')->get();
        return response()->json(['transactions' => $transactions]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'transactions_id' => 'required|exists:transactions,id',
            'tanggal_dikembalikan' => 'nullable|date',
            'status' => 'required|in:sudah_dikembalikan,belum_dikembalikan',
            'keterangan' => 'required|string|max:255',
        ]);

        $report = Report::create($data);
        return response()->json(['message' => 'Report created', 'data' => $report->load('transaction')], 201);
    }

    public function show(Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $report->load('transaction.user', 'transaction.book')]);
    }

    public function edit(Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $transactions = Transaction::all();
        return response()->json(['data' => $report, 'transactions' => $transactions]);
    }

    public function update(Request $request, Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'tanggal_dikembalikan' => 'nullable|date',
            'status' => 'required|in:sudah_dikembalikan,belum_dikembalikan',
            'keterangan' => 'required|string|max:255',
        ]);

        $report->update($data);
        return response()->json(['message' => 'Report updated', 'data' => $report->load('transaction')]);
    }

    public function destroy(Report $report)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $report->delete();
        return response()->json(['message' => 'Report deleted']);
    }

    public function getByStatus(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $status = $request->query('status');
        $reports = Report::where('status', $status)->with('transaction.user', 'transaction.book')->get();
        return response()->json(['data' => $reports]);
    }
}
