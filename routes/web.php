<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookshelfController;
use App\Http\Controllers\RowController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/succes', function () {
    return view('auth.succes_register');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register-anggota', [AuthController::class, 'showRegisterAnggota'])->name('registerAnggota.show');
Route::post('/register-anggota', [AuthController::class, 'registerAnggota'])->name('registerAnggota');
Route::get('/register-admin', [AuthController::class, 'showRegisterAdmin'])->name('register-admin.show');
Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('register-admin');

/*
|--------------------------------------------------------------------------
| User Management Routes (HANYA ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua anggota
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Form tambah anggota
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    
    // Simpan data user baru diutamakan untuk menambahkan admin namun bisa untuk anggota juga
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    
    // Form edit anggota
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    
    // Update anggota
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    
    // Hapus anggota
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Approve anggota (ubah status dari nonaktif ke aktif)
    Route::put('/admin/users/{id}/approve', [UserController::class, 'approve']);
});

/*
|--------------------------------------------------------------------------
| Bookshelf Routes (RAK BUKU - HANYA ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua rak buku
    Route::get('/bookshelves', [BookshelfController::class, 'index'])->name('bookshelves.index');
    
    // Form tambah rak buku
    Route::get('/bookshelves/create', [BookshelfController::class, 'create'])->name('bookshelves.create');
    
    // Simpan rak buku baru
    Route::post('/bookshelves', [BookshelfController::class, 'store'])->name('bookshelves.store');
    
    // Detail rak buku
    Route::get('/bookshelves/{bookshelf}', [BookshelfController::class, 'show'])->name('bookshelves.show');
    
    // Form edit rak buku
    Route::get('/bookshelves/{bookshelf}/edit', [BookshelfController::class, 'edit'])->name('bookshelves.edit');
    
    // Update rak buku
    Route::put('/bookshelves/{bookshelf}', [BookshelfController::class, 'update'])->name('bookshelves.update');
    
    // Hapus rak buku
    Route::delete('/bookshelves/{bookshelf}', [BookshelfController::class, 'destroy'])->name('bookshelves.destroy');
});

/*
|--------------------------------------------------------------------------
| Row Routes (BARIS RAK - HANYA ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua baris rak
    Route::get('/rows', [RowController::class, 'index'])->name('rows.index');
    
    // Form tambah baris rak
    Route::get('/rows/create', [RowController::class, 'create'])->name('rows.create');
    
    // Simpan baris rak baru
    Route::post('/rows', [RowController::class, 'store'])->name('rows.store');
    
    // Detail baris rak
    Route::get('/rows/{row}', [RowController::class, 'show'])->name('rows.show');
    
    // Form edit baris rak
    Route::get('/rows/{row}/edit', [RowController::class, 'edit'])->name('rows.edit');
    
    // Update baris rak
    Route::put('/rows/{row}', [RowController::class, 'update'])->name('rows.update');
    
    // Hapus baris rak
    Route::delete('/rows/{row}', [RowController::class, 'destroy'])->name('rows.destroy');
});

/*
|--------------------------------------------------------------------------
| Book Routes (BUKU - ADMIN BISA CRUD, ANGGOTA BISA BACA)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua buku
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    
    // Form tambah buku
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    
    // Simpan buku baru
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    
    // Detail buku
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    // Form edit buku
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    
    // Update buku
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    
    // Hapus buku
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    
    // Cari buku
    Route::get('/books/search/results', [BookController::class, 'search'])->name('books.search');
});

/*
|--------------------------------------------------------------------------
| Transaction Routes (PEMINJAMAN - ANGGOTA BISA PINJAM, ADMIN BISA KELOLA)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua peminjaman
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // Form peminjaman buku
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    
    // Simpan peminjaman baru
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    
    // Detail peminjaman
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    
    // Form edit peminjaman (admin)
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    
    // Update peminjaman (admin)
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    
    // Pengembalian buku
    Route::put('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');
    
    // Hapus peminjaman (admin)
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    
    // Lihat peminjaman saya (anggota)
    Route::get('/my-transactions', [TransactionController::class, 'myTransactions'])->name('transactions.mine');
});

/*
|--------------------------------------------------------------------------
| Report Routes (LAPORAN PENGEMBALIAN - HANYA ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Form tambah laporan
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    
    // Simpan laporan baru
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    
    // Detail laporan
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    
    // Form edit laporan
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    
    // Update laporan
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    
    // Hapus laporan
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    
    // Filter laporan berdasarkan status
    Route::get('/reports/status/{status}', [ReportController::class, 'getByStatus'])->name('reports.by-status');
});

/*
|--------------------------------------------------------------------------
| Visit Routes (KUNJUNGAN - HANYA ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua kunjungan
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
    
    // Form tambah kunjungan
    Route::get('/visits/create', [VisitController::class, 'create'])->name('visits.create');
    
    // Simpan kunjungan baru
    Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
    
    // Detail kunjungan
    Route::get('/visits/{visit}', [VisitController::class, 'show'])->name('visits.show');
    
    // Form edit kunjungan
    Route::get('/visits/{visit}/edit', [VisitController::class, 'edit'])->name('visits.edit');
    
    // Update kunjungan
    Route::put('/visits/{visit}', [VisitController::class, 'update'])->name('visits.update');
    
    // Hapus kunjungan
    Route::delete('/visits/{visit}', [VisitController::class, 'destroy'])->name('visits.destroy');
    
    // Filter kunjungan berdasarkan user
    Route::get('/visits/user/{user}', [VisitController::class, 'getByUser'])->name('visits.by-user');
    
    // Filter kunjungan berdasarkan tanggal
    Route::get('/visits/date/search', [VisitController::class, 'getByDate'])->name('visits.by-date');

    // Check-in kunjungan untuk anggota
    Route::post('/check-in', [VisitController::class, 'checkIn'])->name('visit.check-in');

    // Riwayat kunjungan dan transaksi untuk anggota
    Route::get('/my-visits-history', [VisitController::class, 'history'])->name('visit.history');
});

