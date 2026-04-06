<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Row;
use App\Models\Bookshelf;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    // Ambil filter dari request + default
    $search = $request->input('search', '');
    $date = $request->input('date', '');
    $filter = $request->input('filter', '');

    // Query Builder (BELUM get)
    $query = Book::with('row.bookshelf');

    // Search
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('pengarang', 'like', "%{$search}%")
              ->orWhere('tahun_terbit', 'like', "%{$search}%")
              ->orWhere('kode_buku', 'like', "%{$search}%");
        });
    }

    // Filter Tahun Terbit
    if ($date) {
        $query->whereYear('tahun_terbit', $date);
    }

    // Filter Kategori
    if ($filter) {
        $query->where('kategori_buku', $filter);
    }

    // Paginate results (terbaru ke terlama)
    $books = $query->latest()->paginate(10);

    return view('admin.kelola_data_buku', compact(
        'books',
        'search',
        'date',
        'filter'
    ));
}

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        $bookshelves = Bookshelf::all();
        return view('admin.CRUD_kelola_buku', [
         'book' => null,
         'rows' => $rows,
         'bookshelves' => $bookshelves,
        ]);
    }

    // API untuk autocomplete search
    public function autocompleteSearch(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $books = Book::where('judul', 'like', "%{$query}%")
            ->orWhere('pengarang', 'like', "%{$query}%")
            ->orWhere('kode_buku', 'like', "%{$query}%")
            ->select('id', 'judul', 'pengarang', 'kode_buku', 'kategori_buku')
            ->limit(10)
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'label' => "{$book->judul} ({$book->kode_buku}) - {$book->pengarang}",
                    'value' => $book->judul
                ];
            });

        return response()->json($books);
    }

    public function generateKode()
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        do {
            $generated = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);
        } while (Book::where('kode_buku', $generated)->exists());

        return response()->json(['kode_buku' => $generated]);
    }

    public function createRow(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'nomor_rak' => 'required|string|max:50',
            'keterangan_rak' => 'nullable|string|max:255',
            'baris_ke' => 'required|integer|min:1',
            'keterangan_baris' => 'nullable|string|max:255',
        ]);

        $bookshelf = Bookshelf::firstOrCreate(
            ['no_rak' => $data['nomor_rak']],
            ['keterangan' => $data['keterangan_rak'] ?? null]
        );

        $row = Row::firstOrCreate(
            ['rak_id' => $bookshelf->id, 'baris_ke' => $data['baris_ke']],
            ['keterangan' => $data['keterangan_baris'] ?? null]
        );

        return response()->json([
            'success' => true,
            'row' => [
                'id' => $row->id,
                'rak' => $bookshelf->no_rak,
                'baris_ke' => $row->baris_ke,
                'label' => "Rak {$bookshelf->no_rak} - Baris {$row->baris_ke}"
            ]
        ]);
    }

    public function store(Request $request)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    $data = $request->validate([
        'kode_buku' => 'nullable|string|unique:books,kode_buku',
        'judul' => 'required|string|max:255',
        'pengarang' => 'required|string|max:255',
        'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
        'kategori_buku' => 'required|in:fiksi,nonfiksi',
        'id_baris' => 'nullable|exists:row,id',
        'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'deskripsi' => 'required|string',
        // optional creation of bookshelf/row
        'new_bookshelf_no' => 'nullable|string|max:50',
        'new_bookshelf_keterangan' => 'nullable|string|max:255',
        'new_row_baris' => 'nullable|integer',
        'new_row_keterangan' => 'nullable|string|max:255',
        'nomor_rak' => 'nullable|string|max:50',
    ]);
    if (empty($data['kode_buku'])) {
        do {
            $generated = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);
        } while (Book::where('kode_buku', $generated)->exists());

        $data['kode_buku'] = $generated;
    }

    if ($request->hasFile('cover')) {
        $data['cover'] = $request->file('cover')->store('covers', 'public');
    }
    $data['status'] = 'tersedia';

    // If user requested a new bookshelf, create it (and optional row)
    if (!empty($data['new_bookshelf_no'])) {
        $bookshelf = Bookshelf::create([
            'no_rak' => $data['new_bookshelf_no'],
            'keterangan' => $data['new_bookshelf_keterangan'] ?? null,
        ]);

        if (!empty($data['new_row_baris'])) {
            $row = Row::create([
                'rak_id' => $bookshelf->id,
                'baris_ke' => $data['new_row_baris'],
                'keterangan' => $data['new_row_keterangan'] ?? null,
            ]);

            $data['id_baris'] = $row->id;
        }
    } elseif (!empty($data['new_row_baris'])) {
        // If nomor_rak provided, try to attach the new row to existing bookshelf
        if (!empty($data['nomor_rak'])) {
            $bookshelf = Bookshelf::where('no_rak', $data['nomor_rak'])->first();
            if ($bookshelf) {
                $row = Row::create([
                    'rak_id' => $bookshelf->id,
                    'baris_ke' => $data['new_row_baris'],
                    'keterangan' => $data['new_row_keterangan'] ?? null,
                ]);
                $data['id_baris'] = $row->id;
            }
        }
    }

    // Ensure id_baris exists before creating book
    if (empty($data['id_baris'])) {
        return back()->withInput()->withErrors(['id_baris' => 'Baris rak harus dipilih atau dibuat terlebih dahulu.']);
    }

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
        $bookshelves = Bookshelf::all();
        return view('admin.CRUD_kelola_buku', [
            'book' => $book,
            'rows' => $rows,
            'bookshelves' => $bookshelves
        ]);
    }

    public function update(Request $request, Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'kode_buku' => 'nullable|string|unique:books,kode_buku,' . $book->id,
            'judul' => 'nullable|string|max:255',
            'pengarang' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'nullable|in:fiksi,nonfiksi',
            'id_baris' => 'nullable|exists:row,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path; 
        }

        // If kode_buku was not provided in the update, keep existing
        if (empty($data['kode_buku'])) {
            unset($data['kode_buku']);
        }

        // Remove empty fields so existing values are preserved
        $data = array_filter($data, fn($value) => !is_null($value));

        $book->update($data);

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil diupdate',
                'book' => $book
            ]);
        }

        return redirect()->route('books.index') 
                         ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        try {
            $book->delete();
            
            // Return JSON for AJAX requests
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Buku berhasil dihapus'
                ], 200);
            }
            
            return redirect()->route('books.index')
                             ->with('success', 'Buku berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus buku: ' . $e->getMessage()
                ], 400);
            }
            
            return redirect()->route('books.index')
                             ->with('error', 'Gagal menghapus buku');
        }
    }

    public function browse(Request $request)
    {
        if (Auth::user()?->role !== 'anggota') abort(403);

        // build query with optional filters
        $query = Book::where('status', 'tersedia')->with('row');

        $search = $request->input('search', '');
        $category = $request->input('category', '');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('kategori_buku', $category);
        }

        $books = $query->get();

        // Cek apakah siswa sudah meminjam buku (masih aktif)
        $hasActiveLoan = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])
            ->exists();

        return view('siswa.pinjam-buku', compact('books', 'hasActiveLoan', 'search', 'category'));
    }
}