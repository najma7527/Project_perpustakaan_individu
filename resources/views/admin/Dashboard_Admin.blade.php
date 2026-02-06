<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/Dashboard_Admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png">
        </div>

        <ul class="menu">
            <li class="active"><i class="fa fa-house"></i> Dashboard</li>
            <li><i class="fa fa-book"></i> Pinjam Buku</li>
            <li><i class="fa fa-rotate-left"></i> Kembalikan Buku</li>
            <li><i class="fa fa-circle-exclamation"></i> Laporan Kehilangan</li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <i class="fa fa-bars"></i>
            <div class="profile">
                <span>Seuji</span>
                <img src="https://i.pravatar.cc/40">
            </div>
        </div>

        <!-- WELCOME -->
        <div class="welcome">
            <div class="welcome-left">
                <i class="fa fa-lock"></i>
                <span>Hello Seuji, Selamat Datang Di Perpustakaan</span>
            </div>
            <img class="welcome-img" src="{{ asset('img/book.png') }}">
        </div>

        <!-- STAT -->
        <div class="stat-grid">
            <div class="stat-box">
                <h1>34</h1>
                <p>Pengunjung</p>
            </div>
            <div class="stat-box">
                <h1>20</h1>
                <p>Buku Dipinjam</p>
            </div>
            <div class="stat-box">
                <h1>16</h1>
                <p>Buku Dikembalikan</p>
            </div>
            <div class="stat-box">
                <h1>90</h1>
                <p>Total Buku</p>
            </div>
        </div>

        <!-- TABLE -->
        <div class="panel">
            <h4>Data Pengembalian Buku</h4>
            <table>
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Kode Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laut Bercerita</td>
                        <td>Novel</td>
                        <td>001</td>
                        <td>24/01/2024</td>
                        <td>29/01/2024</td>
                        <td><span class="pill green">Selesai</span></td>
                    </tr>
                    <tr>
                        <td>Bumi</td>
                        <td>Fiksi</td>
                        <td>002</td>
                        <td>20/01/2024</td>
                        <td>25/01/2024</td>
                        <td><span class="pill red">Terlambat</span></td>
                    </tr>
                    <tr>
                        <td>Negeri 5 Menara</td>
                        <td>Novel</td>
                        <td>003</td>
                        <td>22/01/2024</td>
                        <td>27/01/2024</td>
                        <td><span class="pill orange">Proses</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BOTTOM -->
        <div class="bottom">

            <div class="panel">
                <h4>Pinjam Buku</h4>

                <div class="book-card">
                    <img src="https://covers.openlibrary.org/b/id/10523338-L.jpg">
                    <div>
                        <h5>Filosofi Teras</h5>
                        <p>Buku pengembangan diri</p>
                        <button>Pinjam</button>
                    </div>
                </div>

                <div class="book-card">
                    <img src="https://covers.openlibrary.org/b/id/8231856-L.jpg">
                    <div>
                        <h5>Laut Bercerita</h5>
                        <p>Novel sosial</p>
                        <button>Pinjam</button>
                    </div>
                </div>
            </div>

            <div class="panel alert">
                <h4>Total Buku Hilang Bulan Ini</h4>
                <p><i class="fa fa-triangle-exclamation"></i> 5 Buku Hilang</p>
                <p><i class="fa fa-triangle-exclamation"></i> 2 Buku Rusak</p>
            </div>

        </div>

    </main>
</div>

</body>
</html>
