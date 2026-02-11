@extends('layouts.app')

@section('title', 'Transaksi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/transaksi.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/card.css') }}">
@endpush

<<<<<<< HEAD
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
=======
@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-user"></i>
        </div>
        <div>
            <h3>Transaksi</h3>
            <p>Pengembalian dan Peminjaman Buku</p>
>>>>>>> 601054ce57ff2da34cf9ad5dce5558ed0e5b2919
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" class="header-img">
</div>

<!-- TAB -->
<div class="top-action">
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
</div>

<!-- FILTER -->
<div class="filter">
    <div class="search">
        <i class="icon fa fa-search"></i>
        <input type="text" placeholder="Cari Sesuatu...">
    </div>

    <div class="date">
        <i class="icon fa fa-calendar"></i>
        <input type="date">
    </div>

    <button class="btn-filter">
        <i class="fa fa-sliders"></i>
    </button>

    @auth
    <a href="" class="btn-print">
        <i class="fa-solid fa-print"></i>
        Cetak Laporan
    </a>
    @endauth
</div>

{{-- ================= PEMINJAMAN ================= --}}
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
    <th>Status</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
@if($trx->status == 'belum_dikembalikan')
    <span class="status danger">Belum Dikembalikan</span>

@elseif($trx->status == 'buku_hilang')
    <span class="status warning">Buku Hilang</span>

@elseif($trx->status == 'sudah_dikembalikan')
    <span class="status success">Sudah Dikembalikan</span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@endif

{{-- ================= PENGEMBALIAN ================= --}}
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
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $trx->user->nama ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->jatuh_tempo)->format('d/m/Y') }}</td>
    <td class="aksi">
@if($trx->status == 'menunggu_konfirmasi')
    <span class="btn-green btn-approve" data-nama="{{ $trx->user->nama }}">✔</span>
    <span class="btn-red btn-reject" data-nama="{{ $trx->user->nama }}">✖</span>

@elseif($trx->status == 'sudah_dikembalikan')
    <span class="status success">Selesai</span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@endif
<script>
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
</script>
@endsection
