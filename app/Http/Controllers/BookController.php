<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $books = Book::with('row')->get();
        return response()->json(['data' => $books]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        return response()->json(['rows' => $rows]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'required|in:fiksi,nonfiksi',
            'stok_buku' => 'required|integer|min:0',
            'id_baris' => 'required|exists:row,id',
        ]);

        $book = Book::create($data);
        return response()->json(['message' => 'Book created', 'data' => $book->load('row')], 201);
    }

    public function show(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['data' => $book->load('row', 'transactions')]);
    }

    public function edit(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        return response()->json(['data' => $book, 'rows' => $rows]);
    }

    public function update(Request $request, Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'required|in:fiksi,nonfiksi',
            'stok_buku' => 'required|integer|min:0',
            'id_baris' => 'required|exists:row,id',
        ]);

        $book->update($data);
        return response()->json(['message' => 'Book updated', 'data' => $book->load('row')]);
    }

    public function destroy(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $book->delete();
        return response()->json(['message' => 'Book deleted']);
    }

    public function search(Request $request)
    {
        $query = $request->query('q');
        $books = Book::where('judul', 'like', "%$query%")
            ->orWhere('pengarang', 'like', "%$query%")
            ->orWhere('tahun_terbit', 'like', "%$query%")
            ->orWhere('id', 'like', "%$query%")
            ->with('row')
            ->get();
        return response()->json(['data' => $books]);
    }

        public function filter(Request $request)
        {
            $books = Book::query();
            if ($request->filled('kategori_buku')) {
                $books->where('kategori_buku', 'like', '%' . $request->kategori_buku . '%');
            }
            return response()->json(['data' => $books->with('row')->get()]);
        }

    }


