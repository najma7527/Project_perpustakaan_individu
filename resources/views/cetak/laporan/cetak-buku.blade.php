<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Buku</title>
    <style>
@media print {
    .wrap, .topbar, .filter-right, .actions, .top-left, .top-right { display: none !important; }
    .paper { margin: 0; padding: 20px; box-shadow: none; width: 100%; }
}
</style>
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="wrap">

    <!-- HEADER -->
    <div class="topbar">
        <div class="top-left">
            <div class="top-icon">
                <i class="fa-solid fa-print"></i>
            </div>
            <div class="top-text">
                <h4>Cetak Data Buku</h4>
                <span>Daftar buku</span>
            </div>
        </div>
        <div class="top-right">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
    </div>

    <!-- FILTER (KANAN) -->
    <form method="GET" action="{{ route('cetak.filter-buku') }}">
    <div class="filter-right">
        <div class="date-box">
            <i class="fa-regular fa-calendar"></i>
            <input type="date" name="start_date" value="{{ request('start_date') }}">
        </div>
        <i class="fa-solid fa-arrows-left-right"></i>
        <div class="date-box">
            <i class="fa-regular fa-calendar"></i>
            <input type="date" name="end_date" value="{{ request('end_date') }}">
        </div>
        <button type="submit" class="btn-filter">Pilih Tanggal</button>
    </div>
</form>


    <!-- KERTAS -->
    <div class="paper">

        <!-- KOP -->
        <div class="kop">
            <img src="{{ asset('img/logo_smk4.png') }}" class="logo">
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
            <p>Hal : Data Buku Perpustakaan</p>
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

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>halaman 1 dari 3</span>
        </div>
 </div>
<div class="actions">
    <!-- KIRI -->
    <div class="actions-left">
    <a href="{{ route('cetak.buku.pdf', request()->all()) }}" class="btn" id="btnPdf"><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
    <a href="{{ route('cetak.buku.excel', request()->all()) }}" class="btn" id="btnExcel"><i class="fa-solid fa-file-excel"></i> Export Excel</a>
</div>

    <!-- KANAN -->
    <div class="actions-right">
        <button class="btn btn-back" id="btnBack">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
    </div>
</div>

</div>

</body>
<script>

    // KEMBALI
    document.getElementById('btnBack').addEventListener('click', function () {
        if (confirm('Yakin ingin kembali?')) {
            window.history.back();
        }
    });
</script>

</html>