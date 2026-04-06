<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Transaksi</title>
    <style>
@media print {
    .wrap, .topbar, .filter-right, .actions, .top-left, .top-right { display: none !important; }
    .paper { margin: 0; padding: 20px; box-shadow: none; width: 100%; }
}
</style>
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-transaksi.css') }}">
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
                <h4>Cetak Laporan Transaksi</h4>
                <span>laporan transaksi</span>
            </div>
        </div>
        <div class="top-right">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
    </div>

    <!-- FILTER (KANAN) -->
    <form method="GET" action="{{ route('cetak.filter-transaksi') }}">
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
                    <th>Judul Buku</th>
                    <th>Kelas</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Tanggal Dikembalikan</th>
                    <th>Status</th>
                </tr>
            </thead>
<tbody id="content-terbatas">
    @forelse($transactions->take(20) as $index => $t)
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
<tbody id="content-semua" style="display:none;">
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
        @if($transactions->count() > 20)
        <div class="read-more-section" style="margin-top: 20px; text-align: center;">
            <button id="toggleBtn" class="btn btn-info" onclick="toggleContent()">📖 Lihat Semua Data</button>
        </div>
        @endif

    </div>

    <!-- BUTTON (KANAN) -->
   <div class="actions">
    <!-- KIRI -->
    <div class="actions-left">
    <a href="{{ route('cetak.transaksi.pdf', request()->all()) }}" class="btn" id="btnPdf"><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
    <a href="{{ route('cetak.transaksi.excel', request()->all()) }}" class="btn" id="btnExcel"><i class="fa-solid fa-file-excel"></i> Export Excel</a>
</div>

    <!-- KANAN -->
    <div class="actions-right">
    <button class="btn" id="btnBack">
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
