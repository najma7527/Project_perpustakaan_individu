<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Daftar Pengunjung</title>
<link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kunjungan.css') }}">
<style>
    body, html {
        margin: 0;
        padding: 0;
    }
    .paper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        position: relative;
    }
    .table-wrapper {
        flex: 1;
        margin-bottom: 20px;
    }
    .paper-footer {
        page-break-inside: avoid;
        page-break-before: auto;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #cfcfcf;
    }
</style>
</head>
<body>
    <div class="paper">
        <div class="kop">
            <img src="{{ public_path('img/logo_smk4.png') }}" class="logo">
            <div class="kop-text">
                <h2>SMK NEGERI 4 BOJONEGORO</h2>
                <h3>PERPUSTAKAAN</h3>
                <p>JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id</p>
            </div>
        </div>
        <hr>
        <div class="info">
            <p><strong>Hal : Laporan Daftar Pengunjung Perpustakaan</strong></p>
            <p>Periode : 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} 
                s/d 
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
            </p>
        </div>

        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th>Kode Buku</th>
                    <th>Transaksi</th>
                    <th>Tanggal Datang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $index => $v)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $v->user->name ?? '-' }}</td>
                    <td>{{ $v->user->kelas ?? '-' }}</td>
                    <td>{{ $v->transaction->book->judul ?? '-' }}</td>
                    <td>{{ $v->transaction->kodeBuku->kode_buku ?? '-' }}</td>
                    <td>{{ $v->transaction->jenis_transaksi ?? '-' }}</td>
                    <td>{{ $v->tanggal_datang??'-' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;">Tidak ada data kunjungan</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="paper-footer">
            <!-- Signature -->
        <div class="signature-section">
            <p class="name">Pembina Perpustakaan</p>
            <img src="{{ public_path('img/ttd.png') }}" class="signature-image" alt="TTD">
            <p class="title">Ika Susilowati, S. Pd</p>
        </div>
            <span>Dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>
</body>
</html>