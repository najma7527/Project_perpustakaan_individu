<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Daftar pengunjung</title>
    <style>
@media print {
    .wrap, .topbar, .filter-right, .actions, .top-left, .top-right { display: none !important; }
    .paper { margin: 0; padding: 20px; box-shadow: none; width: 100%; }
}
</style>
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-daftar-pengunjung.css') }}">
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
                <h4>Cetak Daftar pengunjung </h4>
                <span>Laporan Daftar pengunjung</span>
            </div>
        </div>
        <div class="top-right">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
    </div>

    <!-- FILTER (KANAN) -->
     <div></div>
    <form method="GET" action="{{ route('cetak.filter-daftar-kunjungan') }}">
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
            <p>Hal : Laporan Daftar Pengunjung Perpustaakan</p>
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
                    <th>Kelas</th>
                    <th>Judul Buku</th>
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
        <td>{{ $v->transaction->jenis_transaksi ?? '-' }}</td>
        <td>{{ $v->tanggal_datang}}</td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;">Tidak ada data kunjungan</td></tr>
    @endforelse
</tbody>
        </table>

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>halaman 1 dari 3</span>
        </div>

    </div>
<div class="actions">
    <div class="actions-left">
    <button class="btn" id="btnPrint"><i class="fa-solid fa-print"></i> Print</button>
    <a href="{{ route('cetak.kunjungan.pdf', request()->all()) }}" class="btn" id="btnPdf"><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
    <a href="{{ route('cetak.kunjungan.excel', request()->all()) }}" class="btn" id="btnExcel"><i class="fa-solid fa-file-excel"></i> Export Excel</a>
</div>

    <!-- KANAN -->
    <div class="actions-right">
        <button class="btn btn-back" id="btnBack">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
    </div>
</div>

</div>
<script>

    // KEMBALI
    document.getElementById('btnBack').addEventListener('click', function () {
        if (confirm('Yakin ingin kembali?')) {
            window.history.back();
        }
    });
</script>
</body>
</html>
