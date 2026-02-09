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
        return view('admin.kelola_data_buku', compact('books'));
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        return view('books.create', compact('rows'));
    }


    public function store(Request $request)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    $data = $request->validate([
        'kode_buku' => 'required|unique:books,kode_buku',
        'judul' => 'required|string|max:255',
        'pengarang' => 'required|string|max:255',
        'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
        'kategori_buku' => 'required|in:fiksi,nonfiksi',
        'id_baris' => 'required|exists:row,id',
        'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'deskripsi' => 'required|string',
    ]);

if ($request->hasFile('cover')) {
    $data['cover'] = $request->file('cover')->store('covers', 'public');
}
    $data['status'] = 'tersedia';

    Book::create($data);

    return redirect()->route('books.index')
                     ->with('success', 'Buku berhasil ditambahkan');
}


    public function show(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $book->load('row'); 
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        return view('books.edit', compact('book','rows'));
    }

    public function update(Request $request, Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'required|in:fiksi,nonfiksi',
            'id_baris' => 'required|exists:row,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'required|string',
        ]);

    if ($request->hasFile('cover')) {
        $path = $request->file('cover')->store('covers', 'public');
        $data['cover'] = $path; 
    }

        $book->update($data);

        return redirect()->route('books.index') 
                         ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        $book->delete();
        return redirect()->route('books.index')
                         ->with('success', 'Buku berhasil dihapus');
    }

    public function browse()
    {
        if (Auth::user()?->role !== 'anggota') abort(403);
        $books = Book::where('status', 'tersedia')->with('row')->get();
        return view('siswa.pinjam-buku', compact('books'));
        
    }
}