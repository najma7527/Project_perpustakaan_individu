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

    public function index()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $visits = Visit::with('user', 'transaction.book')->get();
        return response()->json(['data' => $visits]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $users = User::where('role', 'anggota')->get();
        $transactions = Transaction::all();
        return response()->json(['users' => $users, 'transactions' => $transactions]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'transactios_id' => 'required|exists:transactions,id',
            'tanggal_datang' => 'required|date',
        ]);

        $visit = Visit::create($data);
        return response()->json(['message' => 'Visit created', 'data' => $visit->load('user', 'transaction')], 201);
    }

    public function show(Visit $visit)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $visit->load('user', 'transaction.book')]);
    }

    public function edit(Visit $visit)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $users = User::where('role', 'anggota')->get();
        $transactions = Transaction::all();
        return response()->json(['data' => $visit, 'users' => $users, 'transactions' => $transactions]);
    }

    public function update(Request $request, Visit $visit)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'transactios_id' => 'required|exists:transactions,id',
            'tanggal_datang' => 'required|date',
        ]);

        $visit->update($data);
        return response()->json(['message' => 'Visit updated', 'data' => $visit->load('user', 'transaction')]);
    }

    public function destroy(Visit $visit)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $visit->delete();
        return response()->json(['message' => 'Visit deleted']);
    }

    public function getByUser(User $user)
    {
        if (Auth::user()?->role !== 'admin' && Auth::id() !== $user->id) abort(403);
        $visits = Visit::where('user_id', $user->id)->with('transaction.book')->get();
        return response()->json(['data' => $visits]);
    }

    public function getByDate(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $date = $request->query('date');
        $visits = Visit::whereDate('tanggal_datang', $date)->with('user', 'transaction.book')->get();
        return response()->json(['data' => $visits]);
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
}
