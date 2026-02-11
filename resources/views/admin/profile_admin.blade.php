<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">

<!-- ================= SIDEBAR ================= -->
<aside class="sidebar">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}">
    </div>

    <ul class="menu">
        <li><a href="/kelola_data_buku"><i class="fa fa-book"></i> Kelola Data Buku</a></li>
        <li><a href="/kelola_anggota"><i class="fa fa-users"></i> Kelola Anggota</a></li>
        <li><a href="/transactions"><i class="fa fa-right-left"></i> Transaksi</a></li>
        <li><a href="/daftar_pengunjung"><i class="fa fa-list"></i> Daftar Pengunjung</a></li>
        <li><a href="/laporan_data_kehilangan"><i class="fa fa-file"></i> Laporan Kehilangan</a></li>

        <li class="menu-profile">
            <a href="/profile_admin">
                <i class="fa fa-user"></i> Profil
            </a>
        </li>
    </ul>
</aside>

<!-- ================= MAIN CONTENT ================= -->
<main class="main-content">

<!-- TOPBAR -->
<header class="topbar">
    <i class="fa fa-bars"></i>
    <div class="user">
        <span>Seulgi</span>
        <small>Admin</small>
        <img src="{{ asset('img/profile.png') }}">
    </div>
</header>

<!-- HEADER PROFIL -->
<div class="profile-header">
    <div class="profile-info" id="profileToggle">
        <img src="{{ asset('img/profile.png') }}">
        <div class="user-text">
            <h3>Seulgi Putri</h3>
            <p>@seulgi123 <span class="badge">Admin</span></p>
        </div>
    </div>
    <div class="icon-right">📚</div>
</div>

<!-- POPUP PROFILE -->
<div class="profile-popup hidden" id="profilePopup">
    <p class="popup-title">Profile admin</p>
    <div class="popup-user">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
        <div>
            <strong>Seulgi Putri</strong><br>
            <span>@seulgi123</span>
        </div>
    </div>
    <a href="/profile_admin" class="popup-btn blue">Profile Saya</a>
    <form action="/logout" method="POST">
        @csrf
        <button class="popup-btn red">Log out</button>
    </form>
</div>

<!-- TAB MENU -->
<div class="tab-menu">
    <button class="tab-btn active" id="btnProfil" onclick="showTab('profil')">
        👤 Profil
    </button>
    <button class="tab-btn" id="btnPassword" onclick="showTab('password')">
        🔒 Ubah Password
    </button>
</div>

<!-- ================= PROFIL ================= -->
<div class="card" id="profilForm">
    <h2>Profil Admin</h2>

    <label>Nama :</label>
    <input type="text" value="Seulgi Putri" readonly>

    <label>No. Telepon :</label>
    <input type="text" value="08184567823" readonly>

    <label>Username :</label>
    <input type="text" value="seulgi123" readonly>

    <button class="action-btn">✏ Edit Profil</button>
</div>

<!-- ================= PASSWORD ================= -->
<div class="card hidden" id="passwordForm">
    <h2>Ubah Password</h2>

    <label>Password Lama :</label>
    <input type="password">

    <label>Password Baru :</label>
    <input type="password">

    <label>Konfirmasi Password :</label>
    <input type="password">

    <button class="action-btn">🔁 Ganti Password</button>
</div>

</main>
</div>

<!-- ================= JS ================= -->
<script>
/* POPUP PROFILE */
const profileToggle = document.getElementById('profileToggle');
const profilePopup = document.getElementById('profilePopup');

profileToggle.addEventListener('click', function (e) {
    e.stopPropagation();
    profilePopup.classList.toggle('hidden');
});

document.addEventListener('click', function (e) {
    if (!profileToggle.contains(e.target) && !profilePopup.contains(e.target)) {
        profilePopup.classList.add('hidden');
    }
});

/* TAB SWITCH */
function showTab(tab) {
    const profilForm = document.getElementById('profilForm');
    const passwordForm = document.getElementById('passwordForm');
    const btnProfil = document.getElementById('btnProfil');
    const btnPassword = document.getElementById('btnPassword');

    if (tab === 'profil') {
        profilForm.classList.remove('hidden');
        passwordForm.classList.add('hidden');
        btnProfil.classList.add('active');
        btnPassword.classList.remove('active');
    }

    if (tab === 'password') {
        passwordForm.classList.remove('hidden');
        profilForm.classList.add('hidden');
        btnPassword.classList.add('active');
        btnProfil.classList.remove('active');
    }
}

const userToggle = document.getElementById('userToggle');
const userPopup = document.getElementById('userPopup');

userToggle.addEventListener('click', function (e) {
    e.stopPropagation();
    userPopup.classList.toggle('hidden');
});

document.addEventListener('click', function (e) {
    if (!userToggle.contains(e.target) && !userPopup.contains(e.target)) {
        userPopup.classList.add('hidden');
    }
});

</script>

</body>
</html>
