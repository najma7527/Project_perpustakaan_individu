<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AksaPusta - Gerbang Literasi Digital SMKN 4 Bojonegoro. Sistem manajemen perpustakaan modern untuk meningkatkan budaya baca siswa.">
    <title>AksaPusta - Perpustakaan Digital SMKN 4 Bojonegoro</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="{{ asset('img/logo_aksapusta1.png') }}" alt="AksaPusta Logo" class="logo-img">
                </div>
                <ul class="nav-menu">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#services">Layanan</a></li>
                    <li><a href="#school">Sekolah</a></li>
                    <li><a href="{{ route('login') }}" class="btn-nav">Login</a></li>
                </ul>
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h3 class="hero-pretitle">PERPUSTAKAAN SMKN 4 BOJONEGORO</h3>
                    <h1 class="hero-title">AksaPusta</h1>
                    <p class="hero-subtitle">pusat literasi dan sumber belajar untuk siswa di SMKN 4 BOJONEGORO</p>
                    <p class="hero-description">Menyediakan berbagai koleksi buku dan layanan perpustakaan yang mendukung kegiatan belajar mengajar serta meningkatkan minat baca warga sekolah.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="{{ route('registerAnggota.show') }}" class="btn-secondary"><i class="fas fa-user-plus"></i> Register anggota</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('img/landing1.png') }}" alt="Ilustrasi siswa membaca di perpustakaan digital" class="hero-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section class="about" id="about">
        <div class="container">
            <h2 class="section-title">Tentang AksaPusta</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>AksaPusta merupakan sistem manajemen perpustakaan digital yang dirancang untuk mempermudah pengelolaan data perpustakaan, serta transaksi peminjaman dan pengembalian.</p>
                    <p>Sistem ini mendukung peningkatan budaya literasi di SMKN 4 Bojonegoro dengan pendekatan teknologi yang modern dan mudah digunakan.</p>
                </div>
                <div class="about-image">
                    <img src="{{ asset('img/landing2.png') }}" alt="Screenshot dashboard AksaPusta" class="about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section class="services" id="services">
        <div class="container">
            <h2 class="section-title">Layanan Perpustakaan</h2>
            <div class="services-grid">
                <div class="service-card">
                    <i class="fas fa-users service-icon"></i>
                    <h4>Kelola Anggota</h4>
                    <p>Mengelola anggota yang terdaftar di perpustakaan.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-book service-icon"></i>
                    <h4>Kelola Buku</h4>
                    <p>Mengelola buku yang ada di perpustakaan.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-exchange-alt service-icon"></i>
                    <h4>Peminjaman dan Pengembalian</h4>
                    <p>Mengelola peminjaman dan pengembalian buku oleh anggota perpustakaan.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-book-dead service-icon"></i>
                    <h4>Kelola Buku Hilang</h4>
                    <p>Mengelola buku hilang atau rusak.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-door-open service-icon"></i>
                    <h4>Kunjungan Perpustakaan</h4>
                    <p>Mengelola kunjungan anggota ke perpustakaan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sekolah Section -->
    <section class="school" id="school">
        <div class="container">
            <div class="school-title-container">
                <img src="{{ asset('img/logo_smk4.png') }}" alt="Logo SMKN 4 Bojonegoro" class="school-logo">
                <h2 class="section-title">SMKN 4 BOJONEGORO</h2>
            </div>
            <div class="school-content">
                <div class="school-text">
                    <p>SMKN 4 Bojonegoro merupakan sekolah menengah kejuruan yang kompeten, terampil, dan siap menghadapi dunia kerja maupun melanjutkan pendidikan.</p>
                    <p>Sekolah ini terus berinovasi dalam pengembangan teknologi pendidikan, termasuk implementasi sistem perpustakaan digital untuk membentuk budaya literasi dan transformasi digital sekolah.</p>
                </div>
                <div class="school-image">
                    <img src="{{ asset('img/sekolah.jpeg') }}" alt="Gedung SMKN 4 Bojonegoro" class="school-img">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2 class="section-title">Web Perpustakaan Sekolah</h2>
            <p class="cta-description">Akses mudah ke ribuan buku digital, pinjam buku kapan saja, dan tingkatkan literasi Anda dengan fitur interaktif yang menyenangkan!</p>
            <div class="cta-features">
                <div class="feature-item">
                    <i class="fas fa-search feature-icon"></i>
                    <p>Cari buku favorit dengan cepat</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-mobile-alt feature-icon"></i>
                    <p>Akses dari perangkat apa saja</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users feature-icon"></i>
                    <p>Gabung komunitas pembaca</p>
                </div>
            </div>
            <div class="cta-images">
                <img src="{{ asset('img/Kelola Anggota.png') }}" alt="Screenshot web perpustakaan" class="cta-img floating">
            </div>
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login Sekarang</a>
                <a href="{{ route('registerAnggota.show') }}" class="btn-secondary"><i class="fas fa-user-plus"></i> Register anggota</a>
            </div>
        </div>
    </section>

    <!-- Back to Top -->
    <a href="#home" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>