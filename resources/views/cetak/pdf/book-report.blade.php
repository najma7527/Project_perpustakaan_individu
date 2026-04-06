<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Buku</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

    <div style="clear: both;"></div>
</div>

        <hr>

        <div class="info">
            <p>Hal : Data Buku Perpustakaan</p>
            <p>Kategori : 
        @if($kategori == 'fiksi')
            Fiksi
        @elseif($kategori == 'nonfiksi')
            Non Fiksi
        @else
            Semua Kategori
        @endif
    </p>
        </div>

        <!-- TABEL -->
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Buku</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun Terbit</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Rak</th>
                </tr>
            </thead>
<tbody>
    @forelse($books as $index => $book)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $book->kode_buku ?? '-' }}</td>
        <td>{{ $book->judul }}</td>
        <td>{{ $book->pengarang }}</td>
        <td>{{ $book->tahun_terbit }}</td>
        <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
        <td>{{ ucfirst($book->status ?? '-') }}</td>
        <td>
            @if($book->row && $book->row->bookshelf)
                {{ $book->row->bookshelf->no_rak }} - {{ $book->row->baris_ke }}
            @else
                -
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;">Tidak ada data buku</td></tr>
    @endforelse
</tbody>
        </table>
        </div>

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>

    </div>
</body>
</html>