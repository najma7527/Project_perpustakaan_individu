<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\books;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = books::all();
        return response()->json(['data' => $books]);
    }

    public function create()
    {
        // only admin can create
        if (Auth::user()?->role !== 'admin') abort(403);
        // Return any data needed by the frontend form
        return response()->json(['ok' => true]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'genre' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        $book = books::create($data);
        return response()->json(['message' => 'Book created', 'data' => $book], 201);
    }

    public function edit(books $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $book]);
    }

    public function update(Request $request, books $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'genre' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        $book->update($data);
        return response()->json(['message' => 'Book updated', 'data' => $book]);
    }

    public function destroy(books $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $book->delete();
        return response()->json(['message' => 'Book deleted']);
    }
}
