<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Kehilangan</title>
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
                <h4>Cetak laporan kehilangan </h4>
                <span>Laporan kehilanngan</span>
            </div>
        </div>
        <div class="top-right">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
    </div>

    <!-- FILTER (KANAN) -->
    <form method="GET" action="{{ route('cetak.filter-kehilangan') }}">
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
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th>Transaksi</th>
                    <th>Tanggal Datang</th>
                    <th>Setatus</th>
            
                </tr>
            </thead>
            <!-- Tabel -->
<tbody id="content-terbatas">
    @forelse($reports->take(20) as $index => $r)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $r->user->name ?? $r->transaction->user->name ?? '-' }}</td>
        <td>{{ $r->user->kelas ?? $r->transaction->user->kelas ?? '-' }}</td>
        <td>{{ $r->transaction->book->judul ?? '-' }}</td>
        <td>{{ $r->jenis_transaksi ?? '-' }}</td>
        <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
        <td class="status {{ $r->status == 'sudah_dikembalikan' ? 'done' : 'pending' }}">
            {{ $r->status == 'sudah_dikembalikan' ? 'Sudah Diganti' : 'Belum Diganti' }}
        </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;">Tidak ada data kehilangan</td></tr>
    @endforelse
</tbody>
<tbody id="content-semua" style="display:none;">
    @forelse($reports as $index => $r)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $r->user->name ?? $r->transaction->user->name ?? '-' }}</td>
        <td>{{ $r->user->kelas ?? $r->transaction->user->kelas ?? '-' }}</td>
        <td>{{ $r->transaction->book->judul ?? '-' }}</td>
        <td>{{ $r->jenis_transaksi ?? '-' }}</td>
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
        @if($reports->count() > 20)
        <div class="read-more-section" style="margin-top: 20px; text-align: center;">
            <button id="toggleBtn" class="btn btn-info" onclick="toggleContent()">📖 Lihat Semua Data</button>
        </div>
        @endif
 </div>
<div class="actions">
    <!-- KIRI -->
    <div class="actions-left">
    <a href="{{ route('cetak.kehilangan.pdf', request()->all()) }}" class="btn" id="btnPdf"><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
    <a href="{{ route('cetak.kehilangan.excel', request()->all()) }}" class="btn" id="btnExcel"><i class="fa-solid fa-file-excel"></i> Export Excel</a>
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

    // Toggle Content
    function toggleContent() {
        const contentTerbatas = document.getElementById('content-terbatas');
        const contentSemua = document.getElementById('content-semua');
        const toggleBtn = document.getElementById('toggleBtn');
        
        if (contentSemua.style.display === 'none') {
            contentTerbatas.style.display = 'none';
            contentSemua.style.display = 'table-row-group';
            toggleBtn.textContent = '📖 Sembunyikan';
        } else {
            contentTerbatas.style.display = 'table-row-group';
            contentSemua.style.display = 'none';
            toggleBtn.textContent = '📖 Lihat Semua Data';
        }
    }

    // KEMBALI
    document.getElementById('btnBack').addEventListener('click', function () {
        if (confirm('Yakin ingin kembali?')) {
            window.history.back();
        }
    });
</script>

</html>