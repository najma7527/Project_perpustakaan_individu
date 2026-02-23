<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Transaksi</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-transaksi.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- KERTAS -->
    <!-- KERTAS -->
    <div class="paper">

        <!-- KOP -->
        <div class="kop">
            <img src="{{ public_path('img/logo_smk4.png') }}" class="logo">
            <div class="kop-text">
                <h2>SMK NEGERI 4 BOJONEGORO</h2>
                <h3>PERPUSTAKAAN</h3>
                <p>
                    JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                    Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id
                </p>
            </div>
        </div>

        <hr>

        <div class="info">
            <p>Hal : Laporan Transaksi Perpustakaan</p>
            <p>Periode : 
        {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} 
        s/d 
        {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
    </p>
        </div>

        <!-- TABEL -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th>Kelas</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Tanggal Dikembalikan</th>
                    <th>Status</th>
                </tr>
            </thead>
<tbody>
    @forelse($transactions as $index => $t)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $t->user->name ?? '-' }}</td>
        <td>{{ $t->book->judul ?? '-' }}</td>
        <td>{{ $t->user->kelas ?? '-' }}</td>
        <td>{{ optional($t->tanggal_peminjaman)->format('d/m/Y') }}</td>
        <td>{{ optional($t->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
        <td>{{ optional($t->tanggal_pengembalian)->format('d/m/Y') ?: '-' }}</td>
        <td class="{{ $t->status == 'sudah_dikembalikan' ? 'done' : ($t->status == 'buku_hilang' ? 'lost' : 'pending') }}">
            @if($t->status == 'sudah_dikembalikan') Sudah dikembalikan
            @elseif($t->status == 'buku_hilang') Buku Hilang
            @else Belum dikembalikan
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;">Tidak ada data transaksi</td></tr>
    @endforelse
</tbody>
        </table>

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>{{ now()->format('d/m/Y H:i') }}</span>
        </div>

    </div>
</body>
</html>