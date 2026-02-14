<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/siswa/profile-siswa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>

       <ul class="menu">
    <li>
        <a href="/pinjam-buku">
            <i class="fa fa-book"></i> Pinjam Buku
        </a>
    </li>
    <li>
        <a href="/kembalikan-buku">
            <i class="fa fa-rotate-left"></i> Kembalikan Buku
        </a>
    </li>
    <li>
        <a href="/laporan-kehilangan">
            <i class="fa fa-file"></i> Laporan Kehilangan
        </a>
    </li>
</ul>

<a href="/profile-siswa" class="btn-profile">
    Profile
</a>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">

        <!-- TOPBAR -->
        <div class="topbar">
            <i class="fa fa-bars"></i>

            <div class="user-area">
                <span>Seulgi</span>
                <small>Admin</small>
                <img src="{{ asset('img/avatar.png') }}" id="userBtn">
            </div>

            <!-- DROPDOWN -->
            <div class="dropdown" id="dropdown">
                <div class="dropdown-header">
                    <img src="{{ asset('img/avatar.png') }}">
                    <div>
                        <strong>aurellya amanda p.a</strong>
                        <small>@aurellyam</small>
                    </div>
                </div>

                <a href="#" class="dropdown-link active">Profile Saya</a>
                <a href="#" class="dropdown-link logout">Log out</a>
            </div>
        </div>

        <!-- HEADER -->
        <div class="header">
            <h2>Profile saya</h2>
        </div>

        <!-- PROFILE CARD -->
        <div class="profile-wrapper">

            <div class="card profile-card">
                <div class="avatar">
                    <img src="{{ asset('img/avatar.png') }}">
                    <span class="edit-icon"><i class="fa fa-pen"></i></span>
                </div>
                <h3>AURELLYA AMANDA P.A</h3>
                <span class="role">SISWA</span>
            </div>

            <div class="card biodata-card">
                <h4>Data diri</h4>
                <div class="biodata">
                    <p><strong>Nama</strong> : aurellya.amanda.p.a</p>
                    <p><strong>Jenis Kelamin</strong> : Perempuan</p>
                    <p><strong>Kelas</strong> : XII - RPL 1</p>
                    <p><strong>Username</strong> : aurellya.m</p>
                    <p><strong>NISN</strong> : 12345678</p>
                    <p><strong>No.Telepon</strong> : 081914547345</p>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card table-card">
            <h4>Riwayat pengunjung</h4>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pengunjung</th>
                        <th>Aktivitas</th>
                        <th>Kelas</th>
                        <th>Tanggal Datang</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aurellya</td>
                        <td>Peminjaman</td>
                        <td>XII RPL 1</td>
                        <td>24/01/2026</td>
                    </tr>
                    <tr>
                        <td>Aurellya</td>
                        <td>Pengembalian</td>
                        <td>XII RPL 1</td>
                        <td>23/01/2026</td>
                    </tr>
                    <tr>
                        <td>Aurellya</td>
                        <td>Peminjaman</td>
                        <td>XII RPL 1</td>
                        <td>22/01/2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="btn-edit"><i class="fa fa-pen"></i> edit profil</button>

    </main>
</div>

<!-- JS (DIGABUNG) -->
<script>
    const userBtn = document.getElementById('userBtn');
    const dropdown = document.getElementById('dropdown');

    userBtn.addEventListener('click', () => {
        dropdown.classList.toggle('show');
    });
</script>

</body>
</html>
