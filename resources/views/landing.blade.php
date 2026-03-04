<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perpustakaan Digital SMKN 4 Bojonegoro - Pusat literasi dan sumber belajar untuk membentuk generasi cerdas">
    <title>AksaPusta || Perpustakaan Digital SMKN 4 Bojonegoro</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="{{ asset('img/logo_aksapusta1.png') }}" alt="AksaPusta Logo" class="logo-nav">
                </div>
                <ul class="nav-menu">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#services">Layanan</a></li>
                    <li><a href="#school">Sekolah</a></li>
                    <li><a href="{{ route('login') }}" class="btn-nav">Login</a></li>
                </ul>
                <div class="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="hero-badge">Perpustakaan Digital</span>
                    <h1 class="hero-title">
                        AksaPusta
                        <br>
                        <!-- <span>SMKN 4 Bojonegoro</span> -->
                    </h1>
                    <p class="hero-subtitle">Membangun Generasi Cerdas melalui Literasi Modern</p>
                    <p class="hero-desc">Akses ribuan koleksi buku, kelola peminjaman, dan tingkatkan minat baca hanya dalam genggaman.</p>
                    
                    <div class="hero-cta">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                        </a>
                        <a href="{{ route('registerAnggota.show') }}" class="btn btn-outline">
                            <i class="fas fa-user-plus"></i> Daftar Anggota
                        </a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('img/landing1.png') }}" alt="Siswa membaca" class="hero-main-img">
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-bottom"></div>
    </section>

    <!-- About -->
    <section class="section about" id="about">
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title">Tentang AksaPusta</h2>
                <p class="section-subtitle">Pusat literasi digital SMKN 4 Bojonegoro</p>
            </div>

            <div class="about-grid">
                <div class="about-image">
                    <img src="{{ asset('img/landing2.png') }}" alt="Suasana perpustakaan">
                    <div class="stats-overlay">
                        <div class="stat-item">
                            <div class="stat-number">5.000+</div>
                            <div class="stat-label">Koleksi Buku</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1.000+</div>
                            <div class="stat-label">Anggota Aktif</div>
                        </div>
                    </div>
                </div>

                <div class="about-content">
                    <p class="lead-text">AksaPusta adalah sistem perpustakaan digital modern yang membantu SMKN 4 Bojonegoro meningkatkan budaya literasi dengan teknologi terkini.</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <i class="fas fa-rocket"></i>
                            <h4>Akses Cepat</h4>
                            <p>Cari & pinjam buku kapan saja</p>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-book-open-reader"></i>
                            <h4>Koleksi Lengkap</h4>
                            <p>Buku terbaru & referensi berkualitas</p>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-shield-halved"></i>
                            <h4>Pengelolaan Aman</h4>
                            <p>Data terstruktur & terlindungi</p>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-heart"></i>
                            <h4>Ruang Nyaman</h4>
                            <p>Tempat baca yang mendukung fokus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="section services" id="services">
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title">Layanan Kami</h2>
                <p class="section-subtitle">Fasilitas unggulan untuk mendukung literasi & pembelajaran</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-exchange-alt"></i></div>
                    <h3>Peminjaman Buku</h3>
                    <p>Pinjam buku secara mudah & cepat sesuai aturan sekolah</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-undo-alt"></i></div>
                    <h3>Pengembalian</h3>
                    <p>Kembalikan buku tepat waktu, jaga koleksi tetap lengkap</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-tasks"></i></div>
                    <h3>Pengelolaan Koleksi</h3>
                    <p>Pendataan, klasifikasi, & pemeliharaan buku rutin</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-school"></i></div>
                    <h3>Kunjungan & Belajar</h3>
                    <p>Ruang baca, diskusi, & belajar mandiri yang nyaman</p>
                </div>
            </div>
        </div>
    </section>

    <!-- School -->
    <section class="section school" id="school">
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title">
                    <img src="{{ asset('img/logo_smk4.png') }}" alt="Logo SMKN 4" class="inline-logo">
                    SMKN 4 Bojonegoro
                </h2>
                <p class="section-subtitle">Mencetak lulusan kompeten, siap kerja & berwirausaha</p>
            </div>

            <div class="school-grid">
                <div class="school-info">
                    <p>SMKN 4 Bojonegoro fokus mengembangkan kompetensi siswa agar siap menghadapi dunia kerja, berwirausaha, atau melanjutkan studi lebih tinggi. Perpustakaan menjadi salah satu pilar utama dalam mendukung proses tersebut.</p>
                    
                    <div class="highlight-list">
                        <div class="highlight-item">
                            <i class="fas fa-briefcase"></i>
                            <div>
                                <h4>Siap Kerja</h4>
                                <p>Keterampilan sesuai kebutuhan industri</p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-lightbulb"></i>
                            <div>
                                <h4>Berwirausaha</h4>
                                <p>Membangun jiwa entrepreneur sejak dini</p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-graduation-cap"></i>
                            <div>
                                <h4>Lanjut Studi</h4>
                                <p>Jalur mudah ke perguruan tinggi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="school-visual">
                    <img src="{{ asset('img/sekolah.jpeg') }}" alt="Gedung SMKN 4 Bojonegoro">
                    <div class="badge-literasi">
                        <i class="fas fa-award"></i> Sekolah Literasi Unggulan
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="cta-final">
        <div class="container">
            <h2><i class="fas fa-rocket"></i> Mulai Petualangan Literasimu Sekarang!</h2>
            <p>Tingkatkan wawasan, dukung prestasi, dan jadilah bagian dari generasi literat SMKN 4 Bojonegoro</p>
            
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login AksaPusta
                </a>
                <a href="{{ route('registerAnggota.show') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-users"></i> Daftar Anggota Baru
                </a>
            </div>
        </div>
        
        <div class="wave-top"></div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="{{ asset('img/logo_aksapusta.png') }}" alt="Logo AksaPusta">
                    <div>
                        <h3>AksaPusta</h3>
                        <p>Perpustakaan Digital SMKN 4 Bojonegoro</p>
                    </div>
                </div>
                
                <div class="footer-contact">
                    <p><i class="fas fa-map-marker-alt"></i> JL. Raya Surabaya – Bojonegoro, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur</p>
                    <p><i class="fas fa-clock"></i> Senin – Jumat: 07.30 – 15.00 WIB</p>
                </div>

                
            </div>
                <div class="footer-social">
                    <a href="https://www.bing.com/ck/a?!&&p=d204843fab92c681205cfb6969743b6a311b47a8ee1dee1c1f6ac825426f9931JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=facebook+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cuZmFjZWJvb2suY29tL29mZmljaWFsc21rbjRiam4vZm9sbG93aW5nLw"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.bing.com/ck/a?!&&p=e249a79ec1f8eaeae0a6d4fd538155ecf0e479764460f15c1af8d0104a6858e3JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=facebook+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cuaW5zdGFncmFtLmNvbS9vZmZpY2lhbF9zbWtuNGJvam9uZWdvcm8v"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.bing.com/ck/a?!&&p=b26bc920ce71554e239c947b90c0574310878f9bae02cc8d51745610420bda75JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=tiktok+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cudGlrdG9rLmNvbS9Ab2ZmaWNpYWxfc21rbjRib2pvbmVnb3Jv"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.bing.com/ck/a?!&&p=a0c41d55a34e07441c6054a0cfad7f7506d55ad161d9dd7afddbc978b21cb2a5JmltdHM9MTc2OTI5OTIwMA&ptn=3&ver=2&hsh=4&fclid=02f5638b-b516-6ced-07ed-7742b4746d8a&psq=youtube+smkn+4+bojonegoro&u=a1aHR0cHM6Ly93d3cueW91dHViZS5jb20vQHNta25lZ2VyaTRib2pvbmVnb3JvMTg5L3ZpZGVvcw"><i class="fab fa-youtube"></i></a>
                </div>
            <div class="footer-bottom">
                <p>© 2026 AksaPusta • Perpustakaan SMKN 4 Bojonegoro • Membangun Generasi Literat</p>
            </div>
        </div>
    </footer>

    <a href="#home" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>