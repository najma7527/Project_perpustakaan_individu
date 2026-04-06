<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Kehilangan</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kehilangan.css') }}">
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
            <p><strong>Hal : Laporan Kehilangan Buku</strong></p>
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
                    <th>Transaksi</th>
                    <th>Tanggal Laporan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r->user->name ?? $r->transaction->user->name ?? '-' }}</td>
                    <td>{{ $r->user->kelas ?? $r->transaction->user->kelas ?? '-' }}</td>
                    <td>{{ $r->transaction->book->judul ?? '-' }}</td>
                    <td>{{ $r->jenis_transaksi ?? ($r->transaction->jenis_transaksi ?? '-') }}</td>
                    <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
                    <td class="status {{ $r->status == 'sudah_dikembalikan' ? 'done' : 'pending' }}">
                        {{ $r->status == 'sudah_dikembalikan' ? 'Sudah Diganti' : 'Belum Diganti' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;">Tidak ada data kehilangan</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="paper-footer">
            <span>Dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>
</body>
</html>