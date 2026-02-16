<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota</title>
  <link rel="stylesheet" href="{{ asset('css/cetak/cetak-kartu.css') }}">

</head>
<body>

<div class="card-wrapper">
    <div class="library-card">

        <!-- Header -->
        <div class="card-header">
            <img src="{{ asset('img/logo_smk4.png') }}" class="logo" alt="Logo">
            <div class="header-text">
                <h2>KARTU PERPUSTAKAAN</h2>
                <h3>SMK NEGERI 4 BOJONEGORO</h3>
                <p>
                     JL. RAYA SURABAYA BOJONEGORO,Sukowati,Kec.Kapas,Kab.Bojonegoro,Jawa Timur.<br>
                    Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id.
                </p>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="left">
                    <img src="{{ asset('img/profile.png') }}" class="photo" alt="Foto">
            </div>

            <div class="center">
                <table>
                    <tr><td>Nama</td><td>: Angie Hida Silkyara</td></tr>
                    <tr><td>NIS</td><td>: 6550/147.089</td></tr>
                    <tr><td>Kelas</td><td>: XII RPL 1</td></tr>
                    <tr><td>Status</td><td>: Aktif</td></tr>
                    <tr><td>No. Telp</td><td>: 083826752625</td></tr>
                    <tr><td>Alamat</td><td>: Ds. Kauman Dsn. Dalem Kec. Baureno Kab.Bojonegoro</td></tr>
                </table>
            </div>
        </div>

        <!-- Footer TTD -->
        <div class="card-footer">
            <div class="ttd">
                <p>Alfian Tambal Ban</p>
                
                <span>Pembina Perpustakaan</span>
            </div>
        </div>

        <!-- Watermark -->
             <img src="{{ asset('img/icon-warna.png') }}"class="watermark" alt="Watermark">

    </div>
</div>


</body>
</html>
