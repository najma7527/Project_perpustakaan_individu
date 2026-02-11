@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/dashboard-siswa.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    
@section('content')
<!-- 
<div class="wrapper"> -->


    <!-- MAIN -->
    <main class="main-content">

          <!-- HEADER CARD -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <h3>Hello Seulgi 👋</h3>
                <p>Selamat datang diperpustakaan </p>
            </div>
        </div>
        <img src="{{ asset('img/book.png') }}" class="header-img">
    </div>

        <!-- stats -->
<section class="stats-modern">

    <div class="stat-box blue">
        <div class="stat-left">
            <p class="title">Total Buku</p>
            <h2>2,543</h2>
            <span class="up">↑ 12% dari bulan lalu</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-book"></i>
        </div>
    </div>

    <div class="stat-box green">
        <div class="stat-left">
            <p class="title">Anggota Aktif</p>
            <h2>1,245</h2>
            <span class="up">↑ 8% dari bulan lalu</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-users"></i>
        </div>
    </div>

    <div class="stat-box purple">
        <div class="stat-left">
            <p class="title">Peminjaman Hari Ini</p>
            <h2>92</h2>
            <span class="down">↓ 5% dari kemarin</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-right-left"></i>
        </div>
    </div>

    <div class="stat-box orange">
        <div class="stat-left">
            <p class="title">Buku Terlambat</p>
            <h2>23</h2>
            <span class="down">↓ 7% dari kemarin</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-circle-exclamation"></i>
        </div>
    </div>

</section>
<section class="hadir-section">

    <div class="hadir-card">
        <div class="hadir-left">
            <div class="hadir-icon">
                <i class="fa fa-user-check"></i>
            </div>
            <div class="hadir-text">
                <h3>Absensi Kehadiran</h3>
                <p>Klik tombol hadir untuk menambah anggota aktif hari ini</p>
            </div>
        </div>

        <button class="btn-hadir-action" id="btnHadir">
            <i class="fa fa-check-circle"></i>
            Hadir Sekarang
        </button>
    </div>

</section>





        <!-- CONTENT -->
        <section class="dashboard-content">
            <div class="card visitor-card">
        <h4 class="card-title">
            <i class="fa fa-users"></i>list Pengunjung
        </h4>

        <ul class="visitor-list">
            <li>
                <a href="/laporan_kehilangan" class="visitor-item">
                    <span class="icon warning">
                        <i class="fa fa-triangle-exclamation"></i>
                    </span>
                    <span class="text">Laporan Kehilangan</span>
                </a>
            </li>

            <li>
                <a href="/pengembalian-buku" class="visitor-item">
                    <span class="icon primary">
                        <i class="fa fa-book"></i>
                    </span>
                    <span class="text">Kembali Buku</span>
                </a>
            </li>

            <li>
                <a href="/pinjam-buku" class="visitor-item">
                    <span class="icon danger">
                        <i class="fa fa-flag"></i>
                    </span>
                    <span class="text">Pinjam Buku</span>
                </a>
            </li>
        </ul>
    </div>

       <div class="card modern-card">
    <div class="card-header">
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

        </section>

    </main>
</div>

</body>
<script>
    const btnHadir = document.getElementById('btnHadir');
    const anggotaAktif = document.getElementById('anggotaAktif');

    btnHadir.addEventListener('click', () => {
        let jumlah = parseInt(anggotaAktif.innerText);
        anggotaAktif.innerText = jumlah + 1;

        btnHadir.innerHTML = '<i class="fa fa-check"></i> Sudah Hadir';
        btnHadir.disabled = true;
    });
</script>


</html>
@endsection
