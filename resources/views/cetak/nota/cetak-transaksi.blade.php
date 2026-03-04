<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ ucfirst($jenis) }} Perpustakaan</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-peminjaman.css') }}">
</head>
<body onload="{{ $jenis === 'pengembalian' ? 'window.print()' : '' }}">

<div class="nota">
    <!-- Header -->
    <div class="header-nota">
        <h1>NOTA {{ strtoupper($jenis) }}</h1>
    </div>

    <!-- Data Anggota -->
    <div class="data-anggota">
        <table class="table-anggota">
            <tr>
                <td class="label">Nama Anggota</td>
                <td>: {{ $transaction->user->name ?? 'Nama Anggota' }}</td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td>: {{ $transaction->user->kelas ?? 'Kelas' }}</td>
            </tr>
        </table>
    </div>

    <!-- Perkara -->
    <div class="perkara">
        @if($jenis === 'peminjaman')
            PEMINJAMAN BUKU PERPUSTAKAAN
        @else
            PENGEMBALIAN BUKU PERPUSTAKAAN
        @endif
    </div>
    <div class="ayat">
        Berikut adalah rincian buku yang {{ $jenis === 'peminjaman' ? 'dipinjam' : 'dikembalikan' }}:
    </div>

    <!-- Tabel Buku (dinamis berdasarkan jenis) -->
    @if($jenis === 'peminjaman')
    <table class="table-buku">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Buku</th>
                <th>Nama Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $books = $transaction->books ?? collect([$transaction->book ?? null])->filter();
                $no = 1;
            @endphp
            @forelse($books as $book)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $book->kode_buku ?? '-' }}</td>
                <td>{{ $book->judul ?? 'Buku' }}</td>
                <td>{{ $transaction->tanggal_peminjaman ?? '-' }}</td>
                <td>{{ $transaction->tanggal_jatuh_tempo ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td>1</td>
                <td>-</td>
                <td>Contoh Buku</td>
                <td>{{ $transaction->tanggal_peminjaman ?? '-' }}</td>
                <td>{{ $transaction->tanggal_jatuh_tempo ?? '-' }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <table class="table-buku">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Buku</th>
                <th>Nama Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tanggal Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @php
                $books = $transaction->books ?? collect([$transaction->book ?? null])->filter();
                $no = 1;
            @endphp
            @forelse($books as $book)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $book->kode_buku ?? '-' }}</td>
                <td>{{ $book->judul ?? 'Buku' }}</td>
                <td>{{ $transaction->tanggal_peminjaman ?? '-' }}</td>
                <td>{{ $transaction->tanggal_jatuh_tempo ?? '-' }}</td>
                <td>{{ $transaction->tanggal_pengembalian ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td>1</td>
                <td>-</td>
                <td>Contoh Buku</td>
                <td>{{ $transaction->tanggal_peminjaman ?? '-' }}</td>
                <td>{{ $transaction->tanggal_jatuh_tempo ?? '-' }}</td>
                <td>{{ $transaction->tanggal_pengembalian ?? '-' }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif

    <!-- Ucapan Terima Kasih -->
    <div class="terima-kasih">
        Terima kasih telah {{ $jenis === 'peminjaman' ? 'meminjam' : 'mengembalikan' }} buku.
    </div>
</div>

</body>
</html>