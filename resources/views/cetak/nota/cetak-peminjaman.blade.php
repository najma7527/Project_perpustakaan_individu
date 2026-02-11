<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Peminjaman Buku</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-peminjaman.css') }}">
</head>
<body>

<div class="card">
    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="{{ asset('img/icon-cetak.png') }}" alt="Logo">
        </div>
    </div>

    <!-- SUCCESS -->
    <div class="success">
        <div class="check">
            <svg viewBox="0 0 24 24">
                <path d="M9 16.2l-3.5-3.5-1.4 1.4L9 19 20.3 7.7l-1.4-1.4z"/>
            </svg>
        </div>
        <h2>Selamat Peminjaman Buku</h2>
        <p>Anda Telah Berasil</p>
    </div>

    <hr class="dashed">

    <!-- DATA PEMINJAM -->
    <div class="content">
        <div class="row">
            <span>Nama Peminjam</span>
            <span>{{ $peminjam->nama ?? 'SAMSUL' }}</span>
        </div>
        <div class="row">
            <span>NIS / NISN</span>
            <span>{{ $peminjam->nis ?? '88926465372536' }}</span>
        </div>
        <div class="row">
            <span>Kelas</span>
            <span>{{ $peminjam->kelas ?? 'XII 67' }}</span>
        </div>
        <div class="row">
            <span>Nama Buku</span>
            <span>{{ $buku->judul ?? 'NENEK LAMPIR' }}</span>
        </div>
        <div class="row">
            <span>Kode Buku</span>
            <span>{{ $buku->kode ?? '087' }}</span>
        </div>
    </div>

    <hr class="dashed">

    <!-- DATA TANGGAL -->
    <div class="content">
        <div class="row">
            <span>Tanggal Peminjaman</span>
            <span>{{ $peminjaman->tgl_pinjam ?? '09 Oktober 2026' }}</span>
        </div>
        <div class="row">
            <span>Tanggal Pengembalian</span>
            <span>{{ $peminjaman->tgl_kembali ?? '02 Desember 2026' }}</span>
        </div>
        <div class="row">
            <span>Nama Perpustakaan</span>
            <span>PERPUSTAKAAN SMKN 7 KIYOWO</span>
        </div>
        <div class="row">
            <span>Info batas pinjam berikutnya</span>
            <span>
                Peminjam dapat meminjam kembali mulai:
                {{ $peminjaman->batas_pinjam ??  '8/5/2028' }}
            </span>
        </div>
    </div>

    <!-- NOTE -->
    <div class="note">
        <img src="{{ asset('img/icon-warna.png') }}" alt="Icon">
        <p>
            Di antara rak-rak buku ini, kamu mungkin hanya duduk membaca.
            Tapi sebenarnya kamu sedang membangun versi terbaik dari dirimu sendiri.
        </p>
    </div>
</div>

</body>
</html>
