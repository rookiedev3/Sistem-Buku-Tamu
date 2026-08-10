<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Solution Yogyakarta - Portal Akses Terpadu</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #00261a;
            --bg-light: #ffffff;
            --text-dark: #0f172a;
            --text-light: #ffffff;
            --accent-gold: #c4a77d;
            --accent-green: #006b3f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8fafc;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* --- HEADER & ALAMAT / LOGO KIRI-KANAN --- */
        .brand-header {
            background-color: var(--bg-light);
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
            z-index: 10;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-address {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }
        .brand-logo {
            height: 25px;
            width: auto;
            object-fit: contain;
        }

        /* --- BUTTONS --- */
        .btn-gold {
            background-color: var(--accent-gold);
            color: var(--bg-dark);
            font-weight: 700;
            border: none;
            padding: 12px 26px;
            border-radius: 8px;
            font-size: 13.5px;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background-color: #b0956f;
            transform: translateY(-2px);
        }

        .btn-dark-outline {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            color: var(--text-light);
            font-weight: 600;
            padding: 12px 26px;
            border-radius: 8px;
            font-size: 13.5px;
            transition: all 0.3s ease;
        }
        .btn-dark-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.8);
        }

        /* --- HERO SECTION (FULL SCREEN DENGAN FOTO REDUP & TEKS DI ATASNYA) --- */
        .hero-section {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            /* Foto latar belakang dengan efek redup menggunakan linear-gradient */
            background: linear-gradient(rgba(0, 38, 26, 0.55), rgba(0, 38, 26, 0.55)),
            url('{{ asset("images/foto-dashboard.jpg") }}');
            background-size: 100% auto;
            background-position: 100% 75%;
            background-repeat: no-repeat;
            color: var(--text-light);
            padding: 80px 20px;
        }
        .hero-label {
            color: var(--accent-gold);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 16px;
            display: block;
        }
        .hero-title {
            font-size: 42px;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-desc {
            font-size: 15px;
            opacity: 0.9;
            margin-bottom: 35px;
            line-height: 1.6;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }

        /* --- PORTAL SECTION --- */
        .portal-section {
            padding: 70px 0;
            background-color: #f8fafc;
        }
        .section-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .section-subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin-bottom: 40px;
        }

        .portal-card {
            border-radius: 20px;
            padding: 32px;
            text-decoration: none;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);

        /* Membuat bagian atas card (ikon & judul) menjadi sejajar (horizontal) */
        .card-header-flex {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }
        .card-header-flex .icon-box {
            margin-bottom: 0 !important; /* Menghilangkan jarak bawah bawaan ikon */
        }
        .card-header-flex .card-title {
            margin-bottom: 0 !important; /* Menghilangkan jarak bawah bawaan judul */
        }

        }
        .portal-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        }

        /* Card Kiri (Guest) */
        .card-guest {
            background-color: var(--bg-light);
            color: var(--text-dark);
        }
        .card-guest .icon-box {
            background-color: #fef3c7;
            color: #d97706;
        }
        .card-guest .portal-btn {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }
        .card-guest:hover {
            border-color: rgba(196, 167, 125, 0.5);
        }

        /* Card Kanan (Employee) */
        .card-employee {
            background-color: var(--bg-dark);
            color: var(--text-light);
            border-color: var(--bg-dark);
        }
        .card-employee .icon-box {
            background-color: rgba(0, 107, 63, 0.3);
            color: var(--accent-gold);
            border: 1px solid rgba(0, 107, 63, 0.5);
        }
        .card-employee .portal-btn {
            background-color: var(--accent-green);
            color: var(--text-light);
        }
        .card-employee .card-title, 
        .card-employee .card-desc {
            color: var(--text-light);
        }
        .card-employee:hover {
            border-color: rgba(0, 107, 63, 0.8);
        }

        .icon-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 22px;
        }
        .card-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .card-desc {
            font-size: 12.5px;
            color: #64748b;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .portal-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 11px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            transition: all 0.3s ease;
        }
        .portal-card:hover .portal-btn {
            padding-left: 20px;
            padding-right: 20px;
        }

        /* --- STANDAR LAYANAN --- */
        .standar-section {
            padding: 50px 0;
            background-color: var(--bg-light);
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .standar-item {
            text-align: center;
            padding: 0 15px;
        }
        .standar-icon {
            font-size: 20px;
            color: var(--accent-gold);
            margin-bottom: 12px;
        }
        .standar-title {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--text-dark);
        }
        .standar-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }

        /* --- FOOTER --- */
        .site-footer {
            background-color: var(--bg-dark);
            color: #94a3b8;
            font-size: 12px;
            padding: 40px 0 30px 0;
            margin-top: auto;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        .footer-brand {
            color: white;
            font-weight: 800;
            font-size: 15px;
            margin-bottom: 4px;
        }
        .footer-desc {
            font-size: 11.5px;
            opacity: 0.7;
            margin: 0;
        }
        .footer-brand {
            color: white;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 8px;
        }
        .footer-desc {
            font-size: 11.5px;
            opacity: 0.7;
        }
        .footer-links {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
        }
        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 12px;
            transition: color 0.2s;
        }
        .footer-link:hover { color: white; }
        .powered-banner {
            background-color: #001a11;
            text-align: center;
            padding: 10px 0;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--accent-gold);
            font-weight: 700;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 32px; }
            .footer-links { justify-content: flex-start; margin-top: 20px; }
            .header-container { flex-direction: column; gap: 8px; text-align: center; }
        }

        /* Styling Tombol Konsultasi Footer */
.btn-footer-wa {
    background-color: var(--accent-gold);
    color: var(--bg-dark);
    font-weight: 800;
    font-size: 14px;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    margin-top: 15px;
}
.btn-footer-wa:hover {
    background-color: #b0956f;
    color: var(--bg-dark);
    transform: translateY(-2px);
}
    </style>
</head>
<body>

    <!-- ALAMAT DI KIRI & LOGO DI KANAN ATAS -->
    <!-- LOGO DI KIRI & ALAMAT DI KANAN ATAS -->
    <header class="brand-header">
        <div class="container header-container">
            <div>
                <!-- Logo Perusahaan di Kiri -->
                <img src="{{ asset('images/foto-perusahaan.jpg') }}" alt="Logo Perusahaan" class="brand-logo">
            </div>
            <div class="brand-address">
                <!-- Alamat di Kanan -->
                <i class="bi bi-geo-alt-fill text-success me-1"></i> Jl. Turi Km. 1 Kepitu Trimulyo Sleman, Yogyakarta
            </div>
        </div>
    </header>

    <!-- HERO SECTION (FULL SCREEN DENGAN FOTO REDUP & TEKS DI ATASNYA) -->
    <section class="hero-section">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <span class="hero-label">INTEGRATED VISITOR MANAGEMENT</span>
            <h1 class="hero-title">Sistem Manajemen Tamu Terintegrasi</h1>
            <p class="hero-desc">
                Solusi berdaulat untuk institusi modern. Mengelola arus tamu dan akses pegawai dengan presisi, keamanan tingkat tinggi, dan efisiensi tanpa kompromi.
            </p>
            <div class="d-flex justify-content-center gap-3">
               
            </div>
        </div>
    </section>

    <!-- PORTAL AKSES TERPADU -->
    <section class="portal-section">
        <div class="container text-center">
            <div data-aos="fade-up" data-aos-duration="600">
                <h2 class="section-title">Portal Akses Terpadu</h2>
                <p class="section-subtitle">Pilih jalur akses sesuai dengan kredensial Anda.</p>
            </div>

           <div class="row justify-content-center g-4">
                <div class="col-md-6 col-lg-5" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                    <a href="{{ route('check-in.step1') }}" class="portal-card card-guest">
                        <div>
                            <div class="card-header-flex">
                                <div class="icon-box">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <h3 class="card-title">Registrasi Tamu</h3>
                            </div>
                            <p class="card-desc">
                                Layanan lapor diri mandiri untuk pengunjung, vendor, dan tamu VIP. Proses cepat dan terdata secara real-time.
                            </p>
                        </div>
                        <div class="portal-btn">
                            <span>Mulai Kunjungan</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-5" data-aos="fade-left" data-aos-duration="800" data-aos-delay="300">
                    <a href="{{ route('login') }}" class="portal-card card-employee">
                        <div>
                            <div class="card-header-flex">
                                <div class="icon-box">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <h3 class="card-title">Akses Pegawai</h3>
                            </div>
                            <p class="card-desc">
                                Portal internal aman untuk staf dan manajemen. Memerlukan autentikasi untuk masuk ke sistem terpusat.
                            </p>
                        </div>
                        <div class="portal-btn">
                            <span>Masuk Portal</span>
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

               
            </div>
        </div>
    </section>

    <!-- STANDAR LAYANAN KAMI -->
    <section class="standar-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 standar-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <div class="standar-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4 class="standar-title">Efisiensi</h4>
                    <p class="standar-desc">Memangkas waktu antrean dan birokrasi manual melalui otomatisasi alur kerja digital yang presisi.</p>
                </div>
                <div class="col-md-4 standar-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="standar-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="standar-title">Keamanan</h4>
                    <p class="standar-desc">Protokol enkripsi standar industri dan verifikasi identitas berlapis untuk melindungi aset fisik dan digital.</p>
                </div>
                <div class="col-md-4 standar-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <div class="standar-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h4 class="standar-title">Profesionalisme</h4>
                    <p class="standar-desc">Meningkatkan citra institusi dengan pengalaman front-desk yang modern, elegan, dan representatif.</p>
                </div>
            </div>
        </div>
    </section>

   
    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="container footer-container">
            <div>
                <div class="footer-brand">IT Solution Yogyakarta</div>
                <p class="footer-desc">© {{ \Carbon\Carbon::now()->year }} IT Solution Yogyakarta. All rights reserved.</p>
            </div>
            
            <div>
                <a href="https://wa.me/6281239048517" target="_blank" class="btn-footer-wa">
                    <i class="bi bi-whatsapp"></i> Konsultasi
                </a>
            </div>
        </div>
    </footer>
    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic'
        });
    </script>
</body>
</html>