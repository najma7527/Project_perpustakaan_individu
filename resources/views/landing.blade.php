<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perpustakaan Digital SMKN 4 Bojonegoro - Pusat literasi dan sumber belajar untuk membentuk generasi cerdas">
    <title>Perpustakaan Digital SMKN 4 Bojonegoro</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header & Navigation -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-book-open"></i>
                    <span>Perpustakaan <strong>SMKN 4 Bojonegoro</strong></span>
                </div>
                <ul class="nav-menu">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#services">Layanan</a></li>
                    <li><a href="#register">Registrasi</a></li>
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
                    <h2 class="hero-title">
                        <span class="highlight"><i class="fas fa-book"></i> Perpustakaan Digital</span><br>
                        <span class="school-name">SMKN 4 Bojonegoro</span>
                    </h2>
                    <p class="hero-subtitle">Pusat literasi dan sumber belajar untuk membentuk generasi cerdas, terampil, dan berwawasan luas.</p>
                    <p class="hero-description">Perpustakaan SMKN 4 Bojonegoro menyediakan berbagai koleksi buku dan layanan perpustakaan yang mendukung kegiatan belajar mengajar serta meningkatkan minat baca warga sekolah.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login Perpustakaan
                        </a>
                        <a href="{{ route('registerAnggota.show') }}" class="btn-secondary">
                            <i class="fas fa-user-plus"></i> Daftar Anggota Sekarang
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="image-container">
                        <img src="{{ asset('img/landing.png') }}" alt="Siswa membaca di perpustakaan SMKN 4 Bojonegoro" class="hero-img">
                        <div class="floating-elements">
                            <div class="floating-element el1">
                                <i class="fas fa-book"></i>
                                <span>Koleksi Buku</span>
                            </div>
                            <div class="floating-element el2">
                                <i class="fas fa-users"></i>
                                <span>Komunitas Baca</span>
                            </div>
                            <div class="floating-element el3">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Literasi Digital</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Tentang Perpustakaan -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-star"></i> Tentang Perpustakaan</h2>
                <p>Mengenal lebih dekat peran perpustakaan dalam meningkatkan budaya literasi</p>
            </div>
            <div class="about-content">
                <div class="about-image">
                    <img src="{{ asset('img/landing2.jpg') }}" alt="Interior perpustakaan SMKN 4 Bojonegoro">
                    <div class="about-stats">
                        <div class="stat">
                            <h3>5000+</h3>
                            <p>Koleksi Buku</p>
                        </div>
                        <div class="stat">
                            <h3>1000+</h3>
                            <p>Anggota Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="about-text">
                    <p>Perpustakaan SMKN 4 Bojonegoro merupakan fasilitas pendukung pembelajaran yang berperan penting dalam meningkatkan budaya literasi di lingkungan sekolah. Perpustakaan ini dikelola secara sistematis untuk memudahkan akses informasi dan koleksi buku bagi siswa, guru, dan tenaga kependidikan.</p>
                    <div class="about-features">
                        <div class="feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Akses informasi yang mudah dan cepat</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Koleksi buku terlengkap dan terkini</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Pengelolaan sistematis dan profesional</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Lingkungan nyaman untuk membaca</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Perpustakaan -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-book"></i> Layanan Perpustakaan SMKN 4 Bojonegoro</h2>
                <p>Berbagai layanan unggulan untuk mendukung kegiatan literasi dan pembelajaran</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3>Peminjaman Buku</h3>
                    <p>Layanan peminjaman buku bagi anggota perpustakaan untuk mendukung kegiatan belajar, tugas sekolah, dan pengembangan wawasan sesuai dengan peraturan yang berlaku.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <h3>Pengembalian Buku</h3>
                    <p>Layanan pengembalian buku yang dilakukan secara tertib dan tepat waktu guna menjaga ketersediaan koleksi serta kedisiplinan anggota perpustakaan.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Pengelolaan Buku</h3>
                    <p>Pengelolaan koleksi buku oleh petugas perpustakaan meliputi pendataan buku, pengelompokan, pemeliharaan, serta pembaruan data buku secara berkala.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <h3>Kunjungan Perpustakaan</h3>
                    <p>Layanan kunjungan perpustakaan bagi siswa dan guru sebagai sarana membaca, belajar mandiri, diskusi, dan meningkatkan minat literasi di lingkungan sekolah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Register Section -->
    <section class="register" id="register">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-user-plus"></i> Bergabung dengan Perpustakaan</h2>
                <p>Pilih peran Anda untuk mengakses layanan perpustakaan digital kami</p>
            </div>
            <div class="register-cards">
                <div class="register-card admin-card">
                    <div class="card-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Register Admin Perpustakaan</h3>
                    <p>Layanan khusus bagi petugas perpustakaan untuk mengelola data buku, anggota, peminjaman, pengembalian, serta laporan perpustakaan.</p>
                    <a href="{{ route('register-admin.show') }}" class="btn-card">
                        <i class="fas fa-key"></i> Daftar sebagai Admin
                    </a>
                </div>
                <div class="register-card member-card">
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Register Anggota Perpustakaan</h3>
                    <p>Layanan pendaftaran bagi siswa dan warga sekolah untuk dapat meminjam buku, mengakses katalog, serta melihat riwayat peminjaman.</p>
                    <a href="{{ route('registerAnggota.show') }}" class="btn-card">
                        <i class="fas fa-book"></i> Daftar sebagai Anggota
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Sekolah -->
    <section class="school" id="school">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-school"></i> Tentang SMKN 4 Bojonegoro</h2>
                <p>Sekolah berfokus pada pengembangan kompetensi siswa untuk masa depan</p>
            </div>
            <div class="school-content">
                <div class="school-text">
                    <p>SMKN 4 Bojonegoro adalah sekolah menengah kejuruan yang berfokus pada pengembangan kompetensi siswa agar siap memasuki dunia kerja, berwirausaha, dan melanjutkan pendidikan ke jenjang yang lebih tinggi. Perpustakaan sekolah menjadi salah satu sarana pendukung utama dalam proses pembelajaran dan peningkatan literasi.</p>
                    <div class="school-features">
                        <div class="school-feature">
                            <i class="fas fa-briefcase"></i>
                            <div>
                                <h4>Fokus Dunia Kerja</h4>
                                <p>Menyiapkan siswa dengan keterampilan yang dibutuhkan industri</p>
                            </div>
                        </div>
                        <div class="school-feature">
                            <i class="fas fa-lightbulb"></i>
                            <div>
                                <h4>Pengembangan Wirausaha</h4>
                                <p>Mendorong jiwa kewirausahaan sejak dini</p>
                            </div>
                        </div>
                        <div class="school-feature">
                            <i class="fas fa-graduation-cap"></i>
                            <div>
                                <h4>Kesempatan Melanjutkan Studi</h4>
                                <p>Membuka peluang pendidikan ke jenjang lebih tinggi</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="school-image">
                    <img src="{{ asset('img/logo_smk4.png') }}" alt="SMKN 4 Bojonegoro">
                    <div class="school-badge">
                        <i class="fas fa-award"></i>
                        <span>Sekolah Literasi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Penutup -->
    <section class="cta-final">
        <div class="container">
            <div class="cta-content">
                <h2><i class="fas fa-rocket"></i> Ayo Manfaatkan Perpustakaan Sekolah!</h2>
                <p>Tingkatkan minat baca, perluas wawasan, dan dukung prestasi belajar dengan memanfaatkan layanan Perpustakaan SMKN 4 Bojonegoro.</p>
                <div class="cta-buttons">
                    <a href="{{ route('login') }}" class="btn-primary btn-large">
                        <i class="fas fa-sign-in-alt"></i> Login Sekarang
                    </a>
                    <a href="{{ route('registerAnggota.show') }}" class="btn-secondary btn-large">
                        <i class="fas fa-users"></i> Gabung Menjadi Anggota
                    </a>
                </div>
            </div>
        </div>
        <div class="wave-divider-bottom">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="fas fa-book-open"></i>
                    <div>
                        <h3>Perpustakaan Digital</h3>
                        <p>SMKN 4 Bojonegoro</p>
                    </div>
                </div>
                <div class="footer-info">
                    <p><i class="fas fa-map-marker-alt"></i> JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur.</p>
                    <p><i class="fas fa-clock"></i> Senin - Jumat: 07.30 - 15.30 WIB</p>
                </div>
                <div class="footer-social">
                    <a href="https://www.bing.com/ck/a?!&&p=d204843fab92c681205cfb6969743b6a311b47a8ee1dee1c1f6ac825426f9931JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=facebook+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cuZmFjZWJvb2suY29tL29mZmljaWFsc21rbjRiam4vZm9sbG93aW5nLw"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.bing.com/ck/a?!&&p=e249a79ec1f8eaeae0a6d4fd538155ecf0e479764460f15c1af8d0104a6858e3JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=facebook+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cuaW5zdGFncmFtLmNvbS9vZmZpY2lhbF9zbWtuNGJvam9uZWdvcm8v"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.bing.com/ck/a?!&&p=b26bc920ce71554e239c947b90c0574310878f9bae02cc8d51745610420bda75JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=tiktok+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cudGlrdG9rLmNvbS9Ab2ZmaWNpYWxfc21rbjRib2pvbmVnb3Jv"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.bing.com/ck/a?!&&p=a0c41d55a34e07441c6054a0cfad7f7506d55ad161d9dd7afddbc978b21cb2a5JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=youtube+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cueW91dHViZS5jb20vQHNta25lZ2VyaTRib2pvbmVnb3JvMTg5L3ZpZGVvcw"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Perpustakaan SMKN 4 Bojonegoro - Membangun Generasi Cerdas dan Literat</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#home" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>