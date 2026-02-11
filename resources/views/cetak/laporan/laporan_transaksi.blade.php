<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-transaksi.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

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
            <p>Hal : Laporan Daftar Pengunjung Perpustaakan</p>
            <p>Periode : 01 Januari s/d 31 Januari 2026</p>
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
                <tr>
                    <td>1</td>
                    <td> Putri Himawan</td>
                    <td>X PH1</td>
                    <td>Tahu Bulat Ena</td>
                      <td>pengembalian</td>
                    <td>20/01/2026</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Naila Sobyan</td>
                    <td>XII RPL 2</td>
                    <td>Penggembala Kambing</td>
                    <td>peminjaman</td>
                    <td>20/01/2026</td>
                
                </tr>
                <tr>
                    <td>3</td>
                    <td>Sahrulman</td>
                    <td>X ATR 2</td>
                    <td>kancil buaya</td>
                      <td>pengembalian</td>
                    <td>20/01/2026</td>

                </tr>
            </tbody>
        </table>

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>halaman 1 dari 3</span>
        </div>

    </div>
</body>
</html>