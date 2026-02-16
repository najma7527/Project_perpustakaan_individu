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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanKehilanganController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CetakController;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    
    Route::get('/register-anggota', [AuthController::class, 'showRegisterAnggota'])->name('registerAnggota.show');
    Route::post('/register-anggota', [AuthController::class, 'registerAnggota'])->name('registerAnggota');
    
    Route::get('/register-admin', [AuthController::class, 'showRegisterAdmin'])->name('register-admin.show');
    Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('register-admin');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/succes', function () {
    return view('auth.succes_register');
})->name('succes.register');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- Profile Management ---
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', function() { return view('auth.profile.edit'); })->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
        Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    });

    /*
    |----------------------------------------------------------------------
    | ADMIN ROUTES
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

        // User Management (Kelola Anggota)
        Route::prefix('anggota')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.anggota.index');
            Route::get('/{id}', [UserController::class, 'show'])->name('admin.anggota.show');
            Route::put('/{id}', [UserController::class, 'update'])->name('admin.anggota.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.anggota.destroy');
            Route::post('/{id}/status', [UserController::class, 'updateStatus'])->name('admin.anggota.status');
            Route::put('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.anggota.resetPassword');
            Route::get('/kartu/{id}/export', [UserController::class, 'exportKartuAdmin'])->name('admin.kartu.export');
        });

        // Verification View Routes (Legacy)
        Route::get('/kelola_anggota-verifikasi', function () {
            if (Auth::user()?->role !== 'admin') abort(403);
            return view('admin.kelola_data_anggota-verifikasi');
        });
        Route::get('/kelola_anggota-ditolak', function () {
            if (Auth::user()?->role !== 'admin') abort(403);
            return view('admin.kelola_data_anggota-ditolak');
        });

        // Resources (CRUD)
        Route::resource('bookshelves', BookshelfController::class);
        Route::resource('rows', RowController::class);
        Route::resource('books', BookController::class);
        Route::get('/books/search/results', [BookController::class, 'search'])->name('books.search');
        Route::get('/crud_kelola_buku', function () { return view('admin.CRUD_kelola_buku'); });

        // Transactions & Reports
        Route::resource('transactions', TransactionController::class);
        Route::get('/cek-jatuh-tempo', [TransactionController::class, 'cekJatuhTempo']);
        Route::get('/cek-terlambat', [TransactionController::class, 'cekKeterlambatan']);
        Route::put('/transactions/{transaction}/accept-return', [TransactionController::class, 'terimaPengembalian'])->name('transactions.terimaPengembalian');
        Route::put('/transactions/{transaction}/reject-return', [TransactionController::class, 'tolakPengembalian'])->name('transactions.tolakPengembalian');

        Route::resource('reports', ReportController::class);
        Route::get('/reports/status/{status}', [ReportController::class, 'getByStatus'])->name('reports.by-status');

        Route::resource('visits', VisitController::class);
        Route::get('/visits/user/{user}', [VisitController::class, 'getByUser'])->name('visits.by-user');
        Route::get('/visits/date/search', [VisitController::class, 'getByDate'])->name('visits.by-date');

        // Cetak / Exports
        Route::prefix('cetak')->group(function () {
            // Transaksi
            Route::get('/transaksi/print', [CetakController::class, 'transaksiPrint']);
            Route::get('/transaksi/pdf', [CetakController::class, 'transaksiPdf']);
            Route::get('/transaksi/excel', [CetakController::class, 'transaksiExcel']);
            Route::get('/filter-transaksi', function () { return view('cetak.laporan.cetak-transaksi'); })->name('cetak.filter-transaksi');

            // Kehilangan
            Route::get('/kehilangan/print', [CetakController::class, 'kehilanganPrint']);
            Route::get('/kehilangan/pdf', [CetakController::class, 'kehilanganPdf']);
            Route::get('/kehilangan/excel', [CetakController::class, 'kehilanganExcel']);
            Route::get('/filter-kehilangan', function () { return view('cetak.laporan.cetak-kehilangan'); })->name('cetak.filter-kehilangan');

            // Kunjungan
            Route::get('/kunjungan/print', [CetakController::class, 'kunjunganPrint']);
            Route::get('/kunjungan/pdf', [CetakController::class, 'kunjunganPdf']);
            Route::get('/kunjungan/excel', [CetakController::class, 'kunjunganExcel']);
            Route::get('/filter-daftar-kunjungan', function () { return view('cetak.laporan.cetak-daftar-pengunjung'); })->name('cetak.filter-daftar-kunjungan');
        });

        // External/Legacy Export
        Route::get('/laporan_data_kehilangan', function () {
            return view('admin.laporan_data_kehilangan');
        });

        // Admin Dashboard (Legacy URL)
        Route::get('/dashboard-admin', function () {
            return view('admin.dashboard_admin');
        });

        // Admin Profile Editing
        Route::get('/edit-foto-profile-admin', function () {
            return view('admin.edit-foto-profile-admin');
        });
        Route::get('/edit-profil', function () {
            return view('admin.edit-profil');
        });
        Route::get('/edit-password', function () {
            return view('admin.edit-password');
        });

        // Cetak View Pages
        Route::get('/cetak-transaksi', function () {
            return view('cetak.cetak-transaksi');
        });
        Route::get('/cetak-daftar-pengunjung', function () {
            return view('cetak.cetak-daftar-pengunjung');
        });
        Route::get('/cetak-kehilangan', function () {
            return view('cetak.cetak-kehilangan');
        });
    });

    /*
    |----------------------------------------------------------------------
    | SISWA / ANGGOTA ROUTES
    |----------------------------------------------------------------------
    */
    Route::group([], function () {
        // Dashboard
        Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
        Route::get('/dashboard-anggota', [SiswaDashboardController::class, 'index'])->name('dashboard.anggota');

        // Personal History & Card
        Route::get('/my-transactions', [TransactionController::class, 'myTransactions'])->name('transactions.mine');
        Route::get('/my-visits-history', [VisitController::class, 'history'])->name('visit.history');
        Route::get('/kartu/export', [CetakController::class, 'kartuSiswa'])->name('kartu.export');

        // Peminjaman & Pengembalian
        Route::get('/pinjam-buku', [BookController::class, 'browse'])->name('books.browse');
        Route::post('/pinjam-buku/{bookId}', [TransactionController::class, 'pinjam'])->name('transactions.pinjam');
        Route::get('/pengembalian-buku', function () {
            if (Auth::user()?->role !== 'anggota') abort(403);
            return view('siswa.pengembalian-buku');
        })->name('anggota.pengembalian');
        
        Route::post('/transactions/{transaction}/ajukan-pengembalian', [TransactionController::class, 'returnBook'])->name('transactions.ajukanPengembalian');
        Route::post('/transactions/{transaction}/perpanjang', [TransactionController::class, 'perpanjang'])->name('transactions.perpanjang');

        // Laporan Kehilangan
        Route::resource('laporan-kehilangan', LaporanKehilanganController::class)->names([
            'index' => 'laporan-kehilangan.index',
            'create' => 'laporan-kehilangan.create',
            'store' => 'laporan-kehilangan.store',
            'edit' => 'laporan-kehilangan.edit',
            'update' => 'laporan-kehilangan.update',
        ]);
        
        // Additional Loss Report Views (Legacy)
        Route::get('/laporan_kehilangan', function () { 
            if (Auth::user()?->role !== 'anggota') abort(403);
            return view('siswa.laporan_kehilangan'); 
        });
        Route::get('/kehilangan-buku', function () { 
            if (Auth::user()?->role !== 'anggota') abort(403);
            return 'Halaman Kehilangan Buku (anggota)'; 
        });

        // Visits
        Route::post('/check-in', [VisitController::class, 'checkIn'])->name('checkin');
        
        // Transaksi View
        Route::get('/transaksi', function () {
            if (Auth::user()?->role === 'admin') abort(403);
            return view('dashboard.transaksi');
        })->name('transaksi.user');
        
        // Books Legacy Browse
        Route::get('/books/browse', [BookController::class, 'browse'])->name('books.browse_legacy');
        
        // Cetak Kartu
        Route::get('/cetak-kartu', function () {
            return view('cetak.cetak-kartu');
        })->name('kartu.siswa');

        // Siswa Profile Editing
        Route::get('/edit-profil-user', function () {
            return view('siswa.edit-profil-user');
        });
        Route::get('/edit-foto-profil', function () {
            return view('siswa.edit-foto-profil');
        });
    });

});