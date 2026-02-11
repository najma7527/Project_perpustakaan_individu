<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Transaksi</title>
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
    <div class="filter">
        <div class="filter-right">
            <div class="date-box">
                <i class="fa-regular fa-calendar"></i>
                <input type="text" placeholder="MM-DD-YY">
            </div>

            <i class="fa-solid fa-arrows-left-right"></i>

            <div class="date-box">
                <i class="fa-regular fa-calendar"></i>
                <input type="text" placeholder="MM-DD-YY">
            </div>

            <button class="btn-filter">Pilih Tanggal</button>
        </div>
    </div>

    <!-- KERTAS -->
    <div class="paper">

        <!-- KOP -->
        <div class="kop">
            <img src="{{ asset('img/logo-smkn4.png') }}" class="logo">
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
            <p>Periode : 01 Januari s/d 31 Januari 2026</p>
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
                <tr>
                    <td>1</td>
                    <td>Erika Putri Himawan</td>
                    <td>Tahu Bulat Enak</td>
                    <td>X PH1</td>
                    <td>20/01/2026</td>
                    <td>20/01/2026</td>
                    <td>20/01/2026</td>
                    <td class="done">Sudah dikembalikan</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Naila Sobyan</td>
                    <td>Penggembala Kambing</td>
                    <td>XII RPL 2</td>
                    <td>20/01/2026</td>
                    <td>20/01/2026</td>
                    <td>-</td>
                    <td class="pending">Belum dikembalikan</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Sahrulman</td>
                    <td>Teh Jahe Kopi</td>
                    <td>X ATR 2</td>
                    <td>20/01/2026</td>
                    <td>20/01/2026</td>
                    <td>-</td>
                    <td class="lost">Buku Hilang</td>
                </tr>
            </tbody>
        </table>

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>halaman 1 dari 3</span>
        </div>

    </div>

    <!-- BUTTON (KANAN) -->
   <div class="actions">
    <button class="btn" id="btnPrint">
        <i class="fa-solid fa-print"></i> Print
    </button>

    <button class="btn" id="btnPdf">
        <i class="fa-solid fa-file-pdf"></i> Export PDF
    </button>

    <button class="btn" id="btnExcel">
        <i class="fa-solid fa-file-excel"></i> Export Excel
    </button>

    <button class="btn" id="btnBack">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </button>
</div>

</div>

</body>
<script>
    // PRINT
    document.getElementById('btnPrint').addEventListener('click', function () {
        window.print();
    });

    // EXPORT PDF
    document.getElementById('btnPdf').addEventListener('click', function () {
        alert('Export PDF sedang diproses...');
        // arahkan ke route Laravel kalau sudah ada
        // window.location.href = '/laporan/export-pdf';
    });

    // EXPORT EXCEL
    document.getElementById('btnExcel').addEventListener('click', function () {
        alert('Export Excel sedang diproses...');
        // window.location.href = '/laporan/export-excel';
    });

    // KEMBALI
    document.getElementById('btnBack').addEventListener('click', function () {
        if (confirm('Yakin ingin kembali?')) {
            window.history.back();
        }
    });
</script>

</html>
