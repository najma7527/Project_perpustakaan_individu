<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard_admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}">
        </div>

        <ul class="menu">
            <li><a href="/dashboard-siswa"><i class="fa fa-home"></i> Dashboard</a></li>
            <li><a href="/pinjam-buku"><i class="fa fa-book"></i> Pinjam Buku</a></li>
            <li><a href="/pengembalian-buku"><i class="fa fa-rotate-left"></i> Kembalikan Buku</a></li>
            <li><a href="/laporan_kehilangan"><i class="fa fa-file"></i> Laporan Kehilangan</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main-content">

      <!-- TOPBAR -->
    <header class="topbar">
        <i class="fa fa-bars"></i>
        <div class="user">
            <span>Aldistira</span>
            <small>Admin</small>
            <img src="{{ asset('img/user.png') }}">
        </div>
    </header>

          <!-- HEADER CARD -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <h3>Hello Aldistira 👋</h3>
                <p>Selamat datang diperpustakaan </p>
            </div>
        </div>
        <img src="{{ asset('img/book.png') }}" class="header-img">
    </div>

        <!-- stats -->
<section class="stats-modern">

    <div class="stat-box blue">
        <div class="stat-left">
            <p class="title">Pengunjung</p>
            <h2>2,543</h2>
            <span class="up">↑ 12% dari bulan lalu</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-book"></i>
        </div>
    </div>

    <div class="stat-box green">
        <div class="stat-left">
            <p class="title">Buku Dipinjam</p>
            <h2>1,245</h2>
            <span class="up">↑ 8% dari bulan lalu</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-users"></i>
        </div>
    </div>

    <div class="stat-box purple">
        <div class="stat-left">
            <p class="title">Buku di Kembalikan</p>
            <h2>92</h2>
            <span class="down">↓ 5% dari kemarin</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-right-left"></i>
        </div>
    </div>

    <div class="stat-box orange">
        <div class="stat-left">
            <p class="title">Total</p>
            <h2>23</h2>
            <span class="down">↓ 7% dari kemarin</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-circle-exclamation"></i>
        </div>
    </div>

</section>

 <!-- CARD TABLE -->
<div class="card modern-card">

    <div class="card-header center">
        <i class="fa fa-rotate-left"></i>
        <h4>Data Pengembalian Buku</h4>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Kode Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Laut Bercerita</td>
                    <td>Fiksi</td>
                    <td>0087</td>
                    <td>24/01/2026</td>
                    <td>24/01/2026</td>
                    <td><span class="badge success">Dikembalikan</span></td>
                </tr>

                <tr>
                    <td>Si Anak Pintar</td>
                    <td>Non Fiksi</td>
                    <td>0087</td>
                    <td>26/01/2026</td>
                    <td>26/01/2026</td>
                    <td><span class="badge danger">Belum</span></td>
                </tr>

                <tr>
                    <td>Kayangan Api</td>
                    <td>Fiksi</td>
                    <td>0087</td>
                    <td>26/01/2026</td>
                    <td>26/01/2026</td>
                    <td><span class="badge warning">Menunggu</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<!-- BOTTOM SECTION (PINDAHKAN KE SINI) -->
<section class="bottom-section">

   <!-- PINJAM BUKU -->
<div class="card pinjam-card">
    <h4 class="section-title">Pinjam Buku</h4>

    <div class="pinjam-grid">

        <!-- CARD BUKU 1 -->
       <div class="pinjam-card-item">

    <!-- COVER -->
    <div class="book-cover">
        <img src="{{ asset('img/buku.png') }}" alt="Filosofi Teras">
    </div>

    <!-- CONTENT -->
    <div class="book-content">

        <h5>Filosofi Teras</h5>
        <small>By Henry Manampiring</small>

        <div class="book-meta">
            <span class="badge-genre">Fiksi</span>
            <span class="meta-item">
                <i class="fa fa-book"></i> 12 Stok Buku
            </span>
        </div>

        <p>
            Filosofi Teras karya Henry Manampiring.
        </p>

        <button class="btn-pinjam">Pinjam Buku</button>
    </div>
</div>

        <!-- CARD BUKU 2 -->
        <div class="pinjam-card-item">
            <img src="{{ asset('img/buku.png') }}" alt="Laut Bercerita">

            <div class="pinjam-content">
                <h5>Laut Bercerita</h5>
                <small>By Leila S. Chudori</small>
                
                <div class="book-meta">
                <span class="badge-genre">Non Fiksi</span>
                <span class="meta-item">
                    <i class="fa fa-book"></i> 12 Stok Buku
                </span>
          </div>
                <p>
                    Novel tentang aktivisme, kehilangan.
                </p>

                <button class="btn-pinjam">Pinjam Buku</button>
            </div>
        </div>

    </div>
</div>


    <!-- TOTAL BUKU HILANG -->
<!-- TOTAL BUKU HILANG -->
<div class="card lost-card">

    <div class="lost-header">
        <h4>Total Buku Hilang Bulan Ini</h4>
        <div class="lost-icon">
<i class="fa-solid fa-book-open"></i>

        </div>
    </div>

    <div class="lost-body">

        <div class="lost-item danger">
            <i class="fa fa-triangle-exclamation"></i>
            <span>5 Buku Hilang</span>
        </div>

        <div class="lost-item warning">
            <i class="fa fa-triangle-exclamation"></i>
            <span>2 Buku Rusak</span>
        </div>

    </div>
</div>

        </section>

    </main>
</div>