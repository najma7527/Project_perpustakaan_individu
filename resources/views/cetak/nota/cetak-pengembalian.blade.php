<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-pengembalian.css') }}">
</head>

<body onload="window.print()">

<div class="wrapper">
    <div class="paper">

        <!-- HEADER -->
        <div class="header">
            <img src="{{ asset('img/icon-warna.png') }}" alt="Logo">
            <div class="header-text">
                <h1>PERPUSTAKAAN</h1>
                <p>SMKN 4 BOJONEGORO</p>
            </div>
        </div>

        <div class="line"></div>

        <!-- DATA PEMINJAM -->
        <div class="row">
            <div class="label">Nama Peminjam</div>
            <div class="value name">{{ $pengembalian->nama_peminjam ?? 'NYSI' }}</div>
        </div>

        <div class="row">
            <div class="label">NIS/NISN</div>
            <div class="value">{{ $pengembalian->nis ?? '6742848726' }}</div>
        </div>

        <div class="row">
            <div class="label">Kelas / Jurusan</div>
            <div class="value">{{ $pengembalian->kelas ?? 'XIII RPL 3' }}</div>
        </div>

        <!-- RINCIAN BUKU -->
        <div class="section">Rincian Buku</div>

        <div class="row">
            <div class="label">Judul Buku</div>
            <div class="value">{{ $pengembalian->judul_buku ?? 'se amin tak se iman' }}</div>
        </div>

        <div class="row">
            <div class="label">Kode Buku</div>
            <div class="value">{{ $pengembalian->kode_buku ?? '758743' }}</div>
        </div>

        <!-- DATA PEMINJAMAN -->
        <div class="section">Data Peminjaman Awal</div>

        <div class="row">
            <div class="label">Tanggal Pinjam</div>
            <div class="value">
                {{ $pengembalian->tanggal_pinjam ?? '08 Oktober 2026' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Batas Pengembalian Lama</div>
            <div class="value">
                {{ $pengembalian->batas_lama ?? '08 Oktober 2026' }}
            </div>
        </div>

        <!-- PERPANJANGAN -->
        <div class="section">Perpanjangan</div>

        <div class="row">
            <div class="label">Batas Pengembalian Baru</div>
            <div class="value">
                {{ $pengembalian->batas_baru ?? '08 Oktober 2026' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Jumlah Hari Perpanjangan</div>
            <div class="value">
                {{ $pengembalian->jumlah_hari ?? '0 Hari' }}
            </div>
        </div>

    </div>
</div>

</body>
</html>
