<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota - {{ ucfirst($jenis ?? 'Transaksi') }}</title>

    @if($jenis === 'peminjaman')
        <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-peminjaman.css') }}">
    @else
        <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-pengembalian.css') }}">
    @endif

    <style>
        /* Style umum tambahan jika diperlukan */
        body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; background: #f9f9f9; }
        .hidden { display: none; }
    </style>
</head>

<body @if($jenis === 'pengembalian') onload="window.print()" @endif>

@if($jenis === 'peminjaman')

    <!-- ================================================== -->
    <!--          CETAK PEMINJAMAN (layout card)           -->
    <!-- ================================================== -->
    <div class="card">
        <!-- HEADER -->
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('img/icon-cetak.png') }}" alt="Logo Perpustakaan">
            </div>
        </div>

        <!-- SUCCESS -->
        <div class="success">
        <div class="check" style="font-family: DejaVu Sans; font-size:70px; color:#fff;">
            &#10004;
        </div>
            <h2>Selamat! Peminjaman Buku Berhasil</h2>
            <p>Anda telah berhasil meminjam buku</p>
        </div>

        <hr class="dashed">

        <!-- DATA PEMINJAM -->
        <div class="content">
            <div class="row">
                <span>Nama Peminjam</span>
                <span>{{ $transaction->user->name ?? 'Nama Peminjam' }}</span>
            </div>
            <div class="row">
                <span>NIS / NISN</span>
                <span>{{ $transaction->user->nis_nisn ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Kelas / Jurusan</span>
                <span>{{ $transaction->user->kelas ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Judul Buku</span>
                <span>{{ $transaction->book->judul ?? 'Judul Buku' }}</span>
            </div>
            <div class="row">
                <span>Kode Buku</span>
                <span>{{ $transaction->book->kode_buku ?? '-' }}</span>
            </div>
        </div>

        <hr class="dashed">

        <!-- DATA TANGGAL -->
        <div class="content">
            <div class="row">
                <span>Tanggal Peminjaman</span>
                <span>{{ $transaction->tanggal_peminjaman ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Batas Pengembalian</span>
                <span>{{ $transaction->tanggal_jatuh_tempo ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Nama Perpustakaan</span>
                <span>PERPUSTAKAAN SMKN 4 BOJONEGORO</span>
            </div>
            <div class="row">
                <span>Info batas pinjam berikutnya</span>
                <span>
                    Peminjam dapat meminjam kembali mulai: 
                    {{ $transaction->tanggal_jatuh_tempo ?? 'Sesuai ketentuan' }}
                </span>
            </div>
        </div>

        <!-- NOTE -->
        <div class="note">
            <img src="{{ public_path('img/icon-warna.png') }}" alt="Icon">
            <p>
                Di antara rak-rak buku ini, kamu mungkin hanya duduk membaca.<br>
                Tapi sebenarnya kamu sedang membangun versi terbaik dari dirimu sendiri.
            </p>
        </div>
    </div>

@else

    <!-- ================================================== -->
    <!--          CETAK PENGEMBALIAN (layout paper)        -->
    <!-- ================================================== -->
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

            <!-- DATA PEMINJAM -->
            <div class="row">
                <div class="label">Nama Peminjam</div>
                <div class="value name">{{ $transaction->user->name ?? 'Nama Peminjam' }}</div>
            </div>

            <div class="row">
                <div class="label">NIS/NISN</div>
                <div class="value">{{ $transaction->user->nis_nisn ?? '-' }}</div>
            </div>

            <div class="row">
                <div class="label">Kelas / Jurusan</div>
                <div class="value">{{ $transaction->user->kelas ?? '-' }}</div>
            </div>

            <!-- RINCIAN BUKU -->
            <div class="section">Rincian Buku</div>

            <div class="row">
                <div class="label">Judul Buku</div>
                <div class="value">{{ $transaction->book->judul ?? 'Judul Buku' }}</div>
            </div>

            <div class="row">
                <div class="label">Kode Buku</div>
                <div class="value">{{ $transaction->book->kode_buku ?? '-' }}</div>
            </div>

            <!-- DATA PEMINJAMAN -->
            <div class="section">Data Peminjaman Awal</div>

            <div class="row">
                <div class="label">Tanggal Pinjam</div>
                <div class="value">
                    {{ $transaction->tanggal_peminjaman ?? '-' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Batas Pengembalian Lama</div>
                <div class="value">
                    {{ $transaction->tanggal_jatuh_tempo ?? '-' }}
                </div>
            </div>

            <!-- PENGEMBALIAN / PERPANJANGAN -->
            <div class="section">Pengembalian</div>

            <div class="row">
                <div class="label">Tanggal Dikembalikan</div>
                <div class="value">
                    {{ $transaction->tanggal_pengembalian ?? 'Belum dikembalikan' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Status</div>
                <div class="value">
                    {{ ucfirst($transaction->status ?? 'Selesai') }}
                </div>
            </div>

        </div>
    </div>

@endif

</body>
</html>