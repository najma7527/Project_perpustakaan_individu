<?php

namespace App\Http\Controllers;

use App\Models\Bookshelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookshelfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $bookshelves = Bookshelf::with('rows')->get();
        return response()->json(['data' => $bookshelves]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['ok' => true]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'no_rak' => 'required|integer|unique:bookshelf',
            'keterangan' => 'required|string|max:255',
        ]);

        $bookshelf = Bookshelf::create($data);
        return response()->json(['message' => 'Bookshelf created', 'data' => $bookshelf], 201);
    }

    public function show(Bookshelf $bookshelf)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $bookshelf->load('rows')]);
    }

    public function edit(Bookshelf $bookshelf)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $bookshelf]);
    }

    public function update(Request $request, Bookshelf $bookshelf)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'no_rak' => 'required|integer|unique:bookshelf,no_rak,' . $bookshelf->id,
            'keterangan' => 'required|string|max:255',
        ]);

        $bookshelf->update($data);
        return response()->json(['message' => 'Bookshelf updated', 'data' => $bookshelf]);
    }

    public function destroy(Bookshelf $bookshelf)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $bookshelf->delete();
        return response()->json(['message' => 'Bookshelf deleted']);
    }

    public function search(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $query = $request->query('q');
        $bookshelves = Bookshelf::where('no_rak', 'like', "%$query%")
            ->orWhere('keterangan', 'like', "%$query%")
            ->get();
        return response()->json(['data' => $bookshelves]);
    }
}
