<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian Buku Hilang</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-pengembalian.css') }}">
</head>
<body onload="window.print()">

<div class="wrapper">
    <div class="paper">

        <!-- HEADER -->
        <div class="header">
            <img src="{{ public_path('img/icon-warna.png') }}" alt="Logo">
            <div class="header-text">
                <h1>PERPUSTAKAAN</h1>
                <p>SMKN 4 BOJONEGORO</p>
            </div>
        </div>

        <div class="line"></div>

        <h2 style="text-align:center; margin:20px 0 30px;">Pengembalian Buku Hilang</h2>

        <!-- DATA PEMINJAM -->
        <div class="row">
            <div class="label">Nama Peminjam</div>
            <div class="value name">{{ $report->user->name }}</div>
        </div>
        <div class="row">
            <div class="label">NIS/NISN</div>
            <div class="value">{{ $report->user->nis_nisn }}</div>
        </div>
        <div class="row">
            <div class="label">Kelas / Jurusan</div>
            <div class="value">{{ $report->user->kelas }}</div>
        </div>

        <!-- RINCIAN BUKU -->
        <div class="section">Rincian Buku</div>
        <div class="row">
            <div class="label">Judul Buku</div>
            <div class="value">{{ $report->transaction->book->judul }}</div>
        </div>
        <div class="row">
            <div class="label">Kode Buku</div>
            <div class="value">{{ $report->transaction->book->kode_buku }}</div>
        </div>

        <!-- DATA TANGGAL -->
        <div class="section">Data Peminjaman</div>
        <div class="row">
            <div class="label">Tanggal Peminjaman</div>
            <div class="value">{{ \Carbon\Carbon::parse($report->transaction->tanggal_peminjaman)->format('d F Y') }}</div>
        </div>
        <div class="row">
            <div class="label">Batas Pengembalian</div>
            <div class="value">{{ \Carbon\Carbon::parse($report->transaction->tanggal_jatuh_tempo)->format('d F Y') }}</div>
        </div>
        <div class="row">
            <div class="label">Tanggal Dikembalikan</div>
            <div class="value">{{ \Carbon\Carbon::parse($report->tanggal_ganti ?? $report->created_at)->format('d F Y') }}</div>
        </div>

        <!-- KETERANGAN -->
        <div class="row">
            <div class="label">Keterangan</div>
            <div class="value" style="font-style:italic;">
                {{ $report->keterangan ?? 'Buku yang hilang telah diganti sesuai ketentuan perpustakaan' }}
            </div>
        </div>

    </div>
</div>

</body>
</html>