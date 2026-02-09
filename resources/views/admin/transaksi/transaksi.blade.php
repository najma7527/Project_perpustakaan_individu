<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/transaksi.css') }}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">

    <!-- ========== SIDEBAR ========== -->
   <aside class="sidebar">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
    </div>

    <ul class="menu">
        <li class="{{ request()->is('kelola_data_buku*') ? '' : '' }}">
            <a href="/kelola_data_buku">
                <i class="fa fa-book"></i> Kelola Data Buku
            </a>
        </li>

        <li class="{{ request()->is('kelola_anggota*') ? '' : '' }}">
            <a href="/kelola_anggota">
                <i class="fa fa-users"></i> Kelola Anggota
            </a>
        </li>

        <li class="{{ request()->is('transactions*') ? '' : '' }}">
            <a href="/transactions">
                <i class="fa fa-right-left"></i> Transaksi
            </a>
        </li>

        <li class="{{ request()->is('daftar_pengunjung*') ? '' : '' }}">
            <a href="/daftar_pengunjung">
                <i class="fa fa-list"></i> Daftar Pengunjung
            </a>
        </li>

        <li class="{{ request()->is('laporan_kehilangan*') ? '' : '' }}">
            <a href="/laporan_kehilangan">
                <i class="fa fa-file"></i> Laporan Kehilangan
            </a>
        </li>
    </ul>
</aside>

        <!-- ========== TOPBAR ========== -->

<header class="topbar">
    <i class="fa fa-bars"></i>
    <div class="user">
        <span>Seulgi</span>
        <small>Admin</small>
        <img src="{{ asset('img/user.png') }}">
    </div>
</header>
    
    <!-- ========== CONTENT ========== -->
    <main class="main-content">

   <!-- HEADER CARD -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <h3>Transaksi</h3>
                <p>Pengembalian dan Peminjaman Buku</p>
            </div>
        </div>
        <img src="{{ asset('img/book.png') }}" class="header-img">
    </div>

        <!-- TOP ACTION -->
<div class="top-action">

    <!-- TAB -->
    <div class="tabs">
        <a href="?mode=peminjaman"
           class="tab {{ ($mode ?? 'peminjaman') == 'peminjaman' ? 'active' : '' }}">
            Peminjaman
        </a>
        <a href="?mode=pengembalian"
           class="tab {{ ($mode ?? '') == 'pengembalian' ? 'active' : '' }}">
            Pengembalian
        </a>
    </div>

    <!-- FILTER -->
    <div class="filter">
        <div class="input-group">
            <i class="icon fa fa-search"></i>
            <input type="text" placeholder="Cari Sesuatu...">
        </div>

        <div class="input-group">
            <i class="icon fa fa-calendar"></i>
            <input type="date">
        </div>

        <button class="btn-filter">
            <i class="fa fa-sliders"></i>
        </button>
    </div>

</div>

       @if(($mode ?? 'peminjaman') == 'peminjaman')
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Kelas</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Erika Putri</td>
                <td>Tahu Bulat Enak</td>
                <td>X PH 1</td>
                <td>20/01/2026</td>
                <td>27/01/2026</td>
                <td class="aksi">
                      <span class="btn-green btn-approve" data-nama="Erika Putri">✔</span>
                     <span class="btn-red btn-reject" data-nama="Erika Putri">✖</span>
                </td>
            </tr>

            <tr>
                <td>2</td>
                <td>Niko Sabyan</td>
                <td>Pengembala Kambing</td>
                <td>X RPL 2</td>
                <td>20/01/2026</td>
                <td>27/01/2026</td>
                <td class="aksi">
                     <span class="btn-green btn-approve" data-nama="Niko Sabyan">✔</span>
                    <span class="btn-red btn-reject" data-nama="Niko Sabyan">✖</span>
                </td>
            </tr>

            <tr>
                <td>2</td>
                <td>Dina Yumna</td>
                <td>Malang Kota Dingin</td>
                <td>XII RPL 2</td>
                <td>20/01/2026</td>
                <td>27/01/2026</td>
                <td class="aksi">
                      <span class="btn-green btn-approve" data-nama="Dina Yumna">✔</span>
                     <span class="btn-red btn-reject" data-nama="Dina Yumna">✖</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endif
       @if(($mode ?? '') == 'pengembalian')
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Kelas</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>Erika Putri</td>
                <td>Tahu Bulat Enak</td>
                <td>X PH 1</td>
                <td>20/01/2026</td>
                <td>27/01/2026</td>
                <td><span class="status success">Sudah dikembalikan</span></td>
            </tr>

            <tr>
                <td>2</td>
                <td>Niko Sabyan</td>
                <td>Pengembala Kambing</td>
                <td>X RPL 2</td>
                <td>20/01/2026</td>
                <td>27/01/2026</td>
                <td><span class="status danger">Belum dikembalikan</span></td>
            </tr>

            <tr>
                <td>3</td>
                <td>Dina Yumna</td>
                <td>Malang Kota Dingin</td>
                <td>X TKJ 1</td>
                <td>20/01/2026</td>
                <td>27/01/2026</td>
                <td><span class="status warning">Dikembalikan terlambat</span></td>
            </tr>
        </tbody>
    </table>
</div>
@endif
</main>
</div>
        <!-- ========== JAVASCRIPT ========== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    // ambil data yang sudah disetujui
    const approved = JSON.parse(localStorage.getItem("approved_peminjaman")) || {};

    // === LOAD STATUS SAAT HALAMAN DIBUKA ===
    document.querySelectorAll(".btn-approve").forEach(btn => {
        const nama = btn.dataset.nama;
        const row = btn.closest("tr");
        const reject = row.querySelector(".btn-reject");

        if (approved[nama]) {
            row.style.background = "#ecfdf5";
            if (reject) reject.remove();

            btn.style.opacity = "0.6";
            btn.style.cursor = "default";
            btn.replaceWith(btn.cloneNode(true));
        }
    });

    // === KLIK SETUJUI ===
    document.querySelectorAll(".btn-approve").forEach(btn => {
        btn.addEventListener("click", function () {
            const nama = this.dataset.nama;
            const row = this.closest("tr");
            const reject = row.querySelector(".btn-reject");

            if (confirm("Setujui peminjaman?")) {
                approved[nama] = true;
                localStorage.setItem(
                    "approved_peminjaman",
                    JSON.stringify(approved)
                );

                row.style.background = "#ecfdf5";
                if (reject) reject.remove();

                this.style.opacity = "0.6";
                this.style.cursor = "default";
                this.replaceWith(this.cloneNode(true));
            }
        });
    });

    // === KLIK TOLAK ===
    document.querySelectorAll(".btn-reject").forEach(btn => {
        btn.addEventListener("click", function () {
            const nama = this.dataset.nama;

            if (confirm("Tolak peminjaman?")) {
                delete approved[nama];
                localStorage.setItem(
                    "approved_peminjaman",
                    JSON.stringify(approved)
                );

                this.closest("tr").remove();
            }
        });
    });

});
</script>
</body>
</html>