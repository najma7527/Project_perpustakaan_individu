<?php

namespace App\Http\Controllers;

use App\Models\Row;
use App\Models\Bookshelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::with('bookshelf', 'books')->get();
        return response()->json(['data' => $rows]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $bookshelves = Bookshelf::all();
        return response()->json(['bookshelves' => $bookshelves]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'rak_id' => 'required|exists:bookshelf,id',
            'baris_ke' => 'required|integer',
            'keterangan' => 'required|string|max:255',
        ]);

        $row = Row::create($data);
        return response()->json(['message' => 'Row created', 'data' => $row->load('bookshelf')], 201);
    }

    public function show(Row $row)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $row->load('bookshelf', 'books')]);
    }

    public function edit(Row $row)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $bookshelves = Bookshelf::all();
        return response()->json(['data' => $row, 'bookshelves' => $bookshelves]);
    }

    public function update(Request $request, Row $row)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'rak_id' => 'required|exists:bookshelf,id',
            'baris_ke' => 'required|integer',
            'keterangan' => 'required|string|max:255',
        ]);

        $row->update($data);
        return response()->json(['message' => 'Row updated', 'data' => $row->load('bookshelf')]);
    }

    public function destroy(Row $row)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $row->delete();
        return response()->json(['message' => 'Row deleted']);
    }

    public function search(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $query = $request->query('q');
        $rows = Row::where('baris_ke', 'like', "%$query%")
            ->orWhere('keterangan', 'like', "%$query%")
            ->orWhereHas('bookshelf', function ($q) use ($query) {
                $q->where('no_rak', 'like', "%$query%");
            })
            ->with('bookshelf')
            ->get();
        return response()->json(['data' => $rows]);
    }
}
