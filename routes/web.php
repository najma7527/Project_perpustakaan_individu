<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookshelfController;
use App\Http\Controllers\RowController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanKehilanganController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landing');
})->name('home');


// ADMIN
// Route::get('/dashboard', action: function () {
//     if (Auth::user()?->role !== 'admin') {
//     abort(403);
//     }   

//     return view('admin.dashboard_admin');
// })->name('dashboard.admin')->middleware('auth');

Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin')->middleware('auth');

// Pinjam Buku - Route ke controller
Route::get('/pinjam-buku', [BookController::class, 'browse'])->name('pinjam-buku')->middleware('auth');

// Pengembalian Buku - Route ke controller
Route::get('/pengembalian-buku', [TransactionController::class, 'myTransactions'])->name('anggota.pengembalian')->middleware('auth');

Route::get('/crud_kelola_buku', function () {
    return view('admin.CRUD_kelola_buku');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/laporan-kunjungan', function () {
        return view('admin.laporan-kunjungan');
    })->name('admin.laporan.kunjungan');

});




// VERIFIKASI ANGGOTA
Route::get('/kelola_anggota-verifikasi', function () {
    if (Auth::user()?->role !== 'admin') {
        abort(403);
    }

    return view('admin.kelola_data_anggota-verifikasi');
})->middleware('auth');

Route::get('/kelola_anggota-ditolak', function () {
    if (Auth::user()?->role !== 'admin') {
        abort(403);
    }

    return view('admin.kelola_data_anggota-ditolak');
})->middleware('auth');

// LAPORAN KEHILANGAN
Route::get('/laporan_data_kehilangan', [ReportController::class, 'index'])
    ->name('admin.laporan_data_kehilangan')
    ->middleware('auth');

// TRANSAKSI (BUKAN ADMIN)
Route::get('/transaksi', function () {
    if (Auth::user()?->role === 'admin') {
        abort(403);
    }

    return view('dashboard.transaksi');
})->name('transaksi.user')->middleware('auth');

// ANGGOTA
Route::get('/dashboard-anggota', [SiswaDashboardController::class, 'index'])
    ->name('dashboard.anggota')
    ->middleware('auth');


// PROFILE ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::get('/profile/edit', function() { return view('auth.profile.edit'); })->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

// Route::get('/profile_admin', [ProfileController::class, 'show'])
//         ->name('profile.show');


//         // Profile-siswa
//      Route::get('/profile-siswa', function () {
//     return view('auth.profile.siswa.profile-siswa');
// })->name('profile');
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
Route::get('/succes', function () {
return view('auth.succes_register');
})->name('succes.register');

/*
|--------------------------------------------------------------------------
| Routes SISWA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])
        ->name('siswa.dashboard');

    Route::get('/laporan-kehilangan', [LaporanKehilanganController::class, 'index'])
        ->name('laporan-kehilangan.index');

    Route::get('/laporan-kehilangan/create', [LaporanKehilanganController::class, 'create'])
        ->name('laporan-kehilangan.create');

    Route::post('/laporan-kehilangan', [LaporanKehilanganController::class, 'store'])
        ->name('laporan-kehilangan.store');

    Route::get('/laporan-kehilangan/{id}/edit', [LaporanKehilanganController::class, 'edit'])
        ->name('laporan-kehilangan.edit');

    Route::put('/laporan-kehilangan/{id}', [LaporanKehilanganController::class, 'update'])
        ->name('laporan-kehilangan.update');

    // Aksi kembalikan buku dari laporan (oleh anggota)
    Route::post('/laporan-kehilangan/{id}/kembalikan', [LaporanKehilanganController::class, 'kembalikan'])
        ->name('laporan-kehilangan.kembalikan');

    Route::delete('/laporan-kehilangan/{id}', [LaporanKehilanganController::class, 'destroy'])
        ->name('laporan-kehilangan.destroy');
});

/*
|--------------------------------------------------------------------------
| User Management Routes (HANYA ADMIN)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Kelola Data Anggota
    Route::get('/anggota', [UserController::class, 'index'])->name('admin.anggota.index');
    //verifikasi, setatus anggota
    Route::post('/anggota/{id}/status', [UserController::class, 'updateStatus'])->name('admin.anggota.status');
    // Hapus anggota
    Route::delete('/anggota/{id}', [UserController::class, 'destroy'])->name('admin.anggota.destroy');
    // Detail anggota
    Route::get('/anggota/{id}', [UserController::class, 'show'])->name('admin.anggota.show');
    // Update status anggota
    Route::put('/anggota/{id}', [UserController::class, 'update'])->name('admin.anggota.update');
    // Reset password anggota
    Route::put('/anggota/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.anggota.resetPassword');
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

    Route::get('/pinjam-buku', [BookController::class, 'browse'])->name('books.browse');
});

/*
|--------------------------------------------------------------------------
| Transaction Routes (PEMINJAMAN - ANGGOTA BISA PINJAM, ADMIN BISA KELOLA)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // List semua peminjaman (untuk admin)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // Form peminjaman buku
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    
    // Simpan peminjaman baru
    Route::post('/books/{book}/pinjam', [TransactionController::class, 'pinjam'])->name('books.pinjam');
    
    // Detail peminjaman
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    
    // Form edit peminjaman (admin)
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    
    // Update peminjaman (admin)
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    
    // Hapus peminjaman (admin)
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    
    // Lihat peminjaman saya (anggota)
    Route::get('/my-transactions', [TransactionController::class, 'myTransactions'])->name('transactions.mine');
    
    // Ajukan pengembalian
    Route::post('/transactions/{id}/ajukan-pengembalian', [TransactionController::class, 'ajukanPengembalian'])->name('transactions.ajukanPengembalian');
    
    // Terima pengembalian (admin)
    Route::post('/transactions/{id}/terima-pengembalian', [TransactionController::class, 'terimaPengembalian'])->name('transactions.terimaPengembalian');
    
    // Tolak pengembalian (admin)
    Route::post('/transactions/{id}/tolak-pengembalian', [TransactionController::class, 'tolakPengembalian'])->name('transactions.tolakPengembalian');
    
    // Ajukan ulang pengembalian (anggota)
    Route::post('/transactions/{id}/ajukan-ulang', [TransactionController::class, 'ajukanUlang'])->name('transactions.ajukanUlang');
    
    // Tandai buku hilang
    Route::post('/transactions/{id}/tandai-hilang', [TransactionController::class, 'tandaiHilang'])->name('transactions.tandaiHilang');
    
    // Perpanjang peminjaman
    Route::post('/transactions/{id}/perpanjang', [TransactionController::class, 'perpanjang'])->name('transactions.perpanjang');
    
    // Cari transaksi
    Route::get('/transactions/cari', [TransactionController::class, 'search'])->name('transactions.search');
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
    Route::post('/check-in', [VisitController::class, 'checkIn'])->name('checkin');

    // Riwayat kunjungan dan transaksi untuk anggota
    Route::get('/my-visits-history', [VisitController::class, 'history'])->name('visit.history');
});

Route::middleware(['auth'])->group(function () {

    // Halaman profil
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // Update profil
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Hapus foto profil
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
        ->name('profile.photo.delete');

});
    Route::get('/Dashboard_Admin', function () {
    return view('Admin.Dashboard_Admin');
});


Route::get('/cetak-peminjaman', function () {
    return view('cetak.cetak-peminjaman');
});


Route::get('/cetak-pengembalian', function () {
    return view('cetak.cetak-pengembalian');
});


Route::get('/dashboard-siswa', function () {
    return view('siswa.dashboard-siswa');
});


Route::get('/edit-profil-user', function () {
    return view('siswa.edit-profil-user');
});


Route::get('/edit-foto-profil', function () {
    return view('siswa.edit-foto-profil');
});


Route::get('/cetak-transaksi', function () {
    return view('cetak.cetak-transaksi');
});

Route::get('/cetak-daftar-pengunjung', function () {
    return view('cetak.cetak-daftar-pengunjung');
});

Route::get('/dashboard-admin', function () {
    return view('admin.dashboard_admin');
});

Route::get('/cetak-kehilangan', function () {
    return view('cetak.cetak-kehilangan');
});
Route::get('/edit-foto-profile-admin', function () {
    return view('admin.edit-foto-profile-admin');
});

//edit profil admin
Route::get('/edit-profil', function () {
    return view('admin.edit-profil');
});

Route::get('/edit-password', function () {
    return view('admin.edit-password');
});
