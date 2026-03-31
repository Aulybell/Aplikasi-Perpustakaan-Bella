<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-50:  #EFF6FF;
            --blue-100: #DBEAFE;
            --blue-200: #BFDBFE;
            --blue-300: #93C5FD;
            --blue-400: #60A5FA;
            --blue-500: #3B82F6;
            --blue-600: #2563EB;
            --blue-700: #1D4ED8;
            --blue-900: #1E3A5F;
            --white: #FFFFFF;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-400: #94A3B8;
            --gray-600: #475569;
            --gray-900: #0F172A;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--white);
            color: var(--gray-900);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: rgba(255,255,255,0.92) !important;
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--blue-100);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--blue-700) !important;
            letter-spacing: -0.3px;
        }
        .navbar-brand i { color: var(--blue-400); }

        .nav-link {
            color: var(--gray-600) !important;
            font-weight: 500;
            font-size: 0.92rem;
            padding: 6px 14px !important;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--blue-600) !important;
            background: var(--blue-50);
        }

        .btn-nav-login {
            background: var(--blue-600);
            color: white !important;
            border-radius: 10px;
            padding: 7px 20px !important;
            font-weight: 600;
            font-size: 0.88rem;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-nav-login:hover {
            background: var(--blue-700);
            color: white !important;
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero-section {
            background: linear-gradient(160deg, var(--blue-50) 0%, var(--white) 60%);
            padding: 90px 0 70px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 420px; height: 420px;
            background: radial-gradient(circle, var(--blue-200) 0%, transparent 70%);
            opacity: 0.5;
            pointer-events: none;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, var(--blue-100) 0%, transparent 70%);
            opacity: 0.6;
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--blue-100);
            color: var(--blue-700);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 20px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 700;
            line-height: 1.18;
            color: var(--gray-900);
            margin-bottom: 18px;
        }
        .hero-title span {
            color: var(--blue-600);
            position: relative;
        }
        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 0;
            width: 100%; height: 3px;
            background: var(--blue-300);
            border-radius: 4px;
        }

        .hero-subtitle {
            color: var(--gray-600);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 36px;
            font-weight: 400;
        }

        .btn-hero-primary {
            background: var(--blue-600);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.25);
        }
        .btn-hero-primary:hover {
            background: var(--blue-700);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
        }

        .btn-hero-secondary {
            background: white;
            color: var(--blue-600);
            border: 1.5px solid var(--blue-200);
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: border-color 0.2s, background 0.2s, transform 0.15s;
        }
        .btn-hero-secondary:hover {
            border-color: var(--blue-400);
            background: var(--blue-50);
            color: var(--blue-700);
            transform: translateY(-2px);
        }

        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .hero-stat-item {
            display: flex;
            flex-direction: column;
        }
        .hero-stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--blue-700);
            line-height: 1;
        }
        .hero-stat-label {
            font-size: 0.78rem;
            color: var(--gray-400);
            font-weight: 500;
            margin-top: 3px;
        }

        /* ── HERO IMAGE (FIXED) ── */
        .hero-image-wrap {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-image-circle {
            width: 380px;
            height: 380px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--blue-100);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.18);
            border: 6px solid var(--blue-200);
            position: relative;
            z-index: 1;
        }

        .hero-image-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform 0.4s ease;
        }

        .hero-image-circle:hover img {
            transform: scale(1.05);
        }

        /* Decorative ring behind the circle */
        .hero-image-wrap::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            border: 2px dashed var(--blue-200);
            animation: rotate-ring 20s linear infinite;
            z-index: 0;
        }

        @keyframes rotate-ring {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ── SECTION LABEL ── */
        .section-label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--blue-500);
            margin-bottom: 10px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 10px;
        }
        .section-subtitle {
            color: var(--gray-400);
            font-size: 0.97rem;
            max-width: 440px;
            margin: 0 auto;
        }

        /* ── KATEGORI ── */
        .kategori-section {
            padding: 80px 0;
            background: var(--gray-50);
        }
        .category-card {
            background: white;
            border: 1.5px solid var(--blue-100);
            border-radius: 18px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            display: block;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .category-card:hover {
            border-color: var(--blue-400);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.1);
        }
        .category-icon {
            width: 56px; height: 56px;
            background: var(--blue-50);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            transition: background 0.2s;
        }
        .category-card:hover .category-icon {
            background: var(--blue-100);
        }
        .category-icon i {
            font-size: 1.4rem;
            color: var(--blue-500);
        }
        .category-name {
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--gray-900);
            margin-bottom: 4px;
        }
        .category-desc {
            font-size: 0.78rem;
            color: var(--gray-400);
        }

        /* ── BUKU TERBARU ── */
        .buku-section {
            padding: 80px 0;
            background: white;
        }
        .book-card {
            background: white;
            border: 1.5px solid var(--blue-100);
            border-radius: 18px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(37,99,235,0.12);
            border-color: var(--blue-300);
        }
        .book-cover {
            background: var(--blue-50);
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .book-cover img {
            max-height: 190px;
            object-fit: contain;
            transition: transform 0.3s;
        }
        .book-card:hover .book-cover img {
            transform: scale(1.04);
        }
        .book-cover i {
            font-size: 3rem;
            color: var(--blue-200);
        }
        .book-body {
            padding: 18px 18px 20px;
        }
        .book-badge {
            display: inline-block;
            background: var(--blue-50);
            color: var(--blue-600);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 100px;
            margin-bottom: 10px;
            letter-spacing: 0.3px;
        }
        .book-title {
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--gray-900);
            margin-bottom: 6px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .book-meta {
            font-size: 0.78rem;
            color: var(--gray-400);
            margin-bottom: 3px;
            line-height: 1.5;
        }
        .btn-pinjam {
            background: var(--blue-600);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 0;
            font-size: 0.85rem;
            font-weight: 600;
            width: 100%;
            margin-top: 14px;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-pinjam:hover {
            background: var(--blue-700);
            color: white;
            transform: translateY(-1px);
        }
        .btn-pinjam-outline {
            background: white;
            color: var(--blue-600);
            border: 1.5px solid var(--blue-200);
            border-radius: 10px;
            padding: 8px 0;
            font-size: 0.85rem;
            font-weight: 600;
            width: 100%;
            margin-top: 14px;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-pinjam-outline:hover {
            border-color: var(--blue-400);
            background: var(--blue-50);
            color: var(--blue-700);
        }

        .btn-lihat-semua {
            background: white;
            color: var(--blue-600);
            border: 1.5px solid var(--blue-300);
            border-radius: 12px;
            padding: 13px 32px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-lihat-semua:hover {
            background: var(--blue-600);
            color: white;
            border-color: var(--blue-600);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.2);
        }

        /* ── DIVIDER ── */
        .section-divider {
            height: 1px;
            background: var(--blue-100);
            margin: 0;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--blue-900);
            color: rgba(255,255,255,0.75);
            padding: 60px 0 30px;
        }
        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        .footer-brand i { color: var(--blue-300); }
        .footer-desc {
            font-size: 0.88rem;
            line-height: 1.7;
            color: rgba(255,255,255,0.55);
            max-width: 260px;
        }
        .footer-heading {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 16px;
        }
        footer a {
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.88rem;
            display: block;
            margin-bottom: 9px;
            transition: color 0.2s;
        }
        footer a:hover { color: var(--blue-300); }
        .footer-contact p {
            font-size: 0.88rem;
            margin-bottom: 9px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-contact i { color: var(--blue-300); width: 14px; }
        .footer-divider {
            border-color: rgba(255,255,255,0.08);
            margin: 36px 0 20px;
        }
        .footer-copy {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.3);
            text-align: center;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 991px) {
            .hero-image-circle {
                width: 280px;
                height: 280px;
            }
            .hero-image-wrap::before {
                width: 315px;
                height: 315px;
            }
        }

        @media (max-width: 768px) {
            .hero-section { padding: 60px 0 50px; }
            .hero-subtitle { max-width: 100%; }
            .hero-image-wrap { margin-top: 40px; }
            .hero-image-circle {
                width: 260px;
                height: 260px;
            }
            .hero-image-wrap::before {
                width: 295px;
                height: 295px;
            }
            .hero-stats { gap: 20px; }
            .btn-hero-primary, .btn-hero-secondary { padding: 12px 20px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>

    <!-- ── NAVBAR ── -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-open me-2"></i>ReadMe
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('koleksi') }}">Koleksi Buku</a>
                    </li>
                </ul>
                <ul class="navbar-nav gap-2 align-items-center">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('koleksi') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('login.keluar') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link px-2">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-nav-login" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ── HERO ── -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-6 order-2 order-lg-1">
                    <div class="hero-badge">
                        <i class="fas fa-star" style="font-size:0.65rem;"></i> Platform Buku Terpercaya
                    </div>
                    <h1 class="hero-title">
                        Temukan Buku<br><span>Impian Anda</span><br>di Sini
                    </h1>
                    <p class="hero-subtitle">
                        Platform perpustakaan buku terlengkap dengan koleksi ribuan judul dari berbagai kategori. Mulai petualangan membaca Anda hari ini.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#kategori" class="btn-hero-primary">
                            <i class="fas fa-search"></i> Jelajahi Kategori
                        </a>
                        <a href="{{ route('koleksi') }}" class="btn-hero-secondary">
                            <i class="fas fa-book-open"></i> Lihat Semua Buku
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-number">5K+</span>
                            <span class="hero-stat-label">Koleksi Buku</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-number">20+</span>
                            <span class="hero-stat-label">Kategori</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-number">10K+</span>
                            <span class="hero-stat-label">Pengguna Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- ── HERO IMAGE (FIXED) ── -->
                <div class="col-12 col-lg-6 order-1 order-lg-2">
                    <div class="hero-image-wrap">
                        <div class="hero-image-circle">
                            <img src="{{ asset('assets/img/book.jpg') }}"
                                 alt="Books"
                                 onerror="this.src='https://via.placeholder.com/380x380/DBEAFE/2563EB?text=ReadMe'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- ── KATEGORI ── -->
    <section id="kategori" class="kategori-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-label">Kategori</p>
                <h2 class="section-title">Temukan Buku Favorit Anda</h2>
                <p class="section-subtitle">Pilih dari berbagai kategori buku yang tersedia untuk Anda</p>
            </div>
            <div class="row g-3">
                @foreach($kategoris as $kategori)
                <div class="col-6 col-md-3">
                    <a href="{{ route('koleksi', ['filterKategori' => $kategori->nama_kategori]) }}" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <p class="category-name">{{ $kategori->nama_kategori }}</p>
                        <p class="category-desc mb-0">Koleksi buku {{ strtolower($kategori->nama_kategori) }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- ── BUKU TERBARU ── -->
    <section class="buku-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-label">Terbaru</p>
                <h2 class="section-title">Buku Terbaru</h2>
                <p class="section-subtitle">Koleksi buku terbaru yang siap untuk dipinjam</p>
            </div>
            <div class="row g-4">
                @forelse($bukus as $buku)
                <div class="col-6 col-md-3">
                    <div class="book-card">
                        <div class="book-cover">
                            @if($buku->foto)
                                <img src="{{ asset('storage/' . $buku->foto) }}" alt="{{ $buku->judul }}">
                            @else
                                <i class="fas fa-book"></i>
                            @endif
                        </div>
                         <div class="book-body">
                                <span class="ul-book-meta-key"></span>
                                <span class="ul-kategori-pill">
                                    {{ optional($buku->kategori)->nama_kategori ?? '-' }}
                                </span>
                            <p class="book-title">{{ $buku->judul }}</p>
                            <p class="book-meta"><i class="fas fa-user-pen me-1" style="color:var(--blue-300);font-size:0.72rem;"></i>{{ $buku->pengarang }}</p>
                            <p class="book-meta"><i class="fas fa-building me-1" style="color:var(--blue-300);font-size:0.72rem;"></i>{{ $buku->penerbit }}</p>
                            <p class="book-meta"><i class="fas fa-calendar me-1" style="color:var(--blue-300);font-size:0.72rem;"></i>{{ $buku->tahun_terbit }}</p>
                            @auth
                                <a href="{{ route('pinjam.create', $buku) }}" class="btn-pinjam">
                                    <i class="fas fa-bookmark me-1"></i>Pinjam Buku
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-pinjam-outline">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login untuk Pinjam
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div style="background:var(--blue-50);border-radius:16px;padding:40px;display:inline-block;">
                        <i class="fas fa-books fa-3x" style="color:var(--blue-200);margin-bottom:12px;display:block;"></i>
                        <p style="color:var(--gray-400);margin:0;font-size:0.92rem;">Belum ada buku tersedia saat ini.</p>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('koleksi') }}" class="btn-lihat-semua">
                    Lihat Semua Buku <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-md-4">
                    <div class="footer-brand">
                        <i class="fas fa-book-open me-2"></i>ReadMe
                    </div>
                    <p class="footer-desc">Platform perpustakaan buku terpercaya untuk memenuhi kebutuhan membaca Anda setiap hari.</p>
                </div>
                <div class="col-md-4">
                    <p class="footer-heading">Navigasi</p>
                    <a href="#">Beranda</a>
                    <a href="{{ route('koleksi') }}">Koleksi Buku</a>
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}">Daftar Akun</a>
                </div>
                <div class="col-md-4 footer-contact">
                    <p class="footer-heading">Kontak</p>
                    <p><i class="fas fa-envelope"></i>info@readme.com</p>
                    <p><i class="fas fa-phone"></i>+62 123 456 789</p>
                    <p><i class="fas fa-map-marker-alt"></i>Jakarta, Indonesia</p>
                </div>
            </div>
            <hr class="footer-divider">
            <p class="footer-copy">&copy; 2026 ReadMe. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>