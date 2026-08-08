<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Portal Buku Tamu Digital</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
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
            /* Background disesuaikan: Gradasi hijau korporat yang hidup dan elegan */
            background-color: #f0f7f4;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(0, 107, 63, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.14) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(0, 107, 63, 0.06) 0%, transparent 60%),
                linear-gradient(to right, rgba(203, 213, 225, 0.4) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(203, 213, 225, 0.4) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 35px 35px, 35px 35px;
            position: relative;
            overflow-x: hidden;
        }
        /* --- NAVBAR HIJAU KORPORAT --- */
        /* .top-navbar {
            width: 100%;
            height: 75px;
            background: #006B3F;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 45px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 107, 63, 0.15);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-box {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-text h2 {
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
        }

        .brand-text span {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }

        .nav-status {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.2);
        } */

        /* --- KONTEN UTAMA --- */
        .main-content-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .welcome-container {
            width: 100%;
            max-width: 860px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .badge-pill {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #006B3F;
            padding: 7px 18px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            border: 1px solid rgba(0, 107, 63, 0.15);
            box-shadow: 0 4px 20px rgba(0, 107, 63, 0.06);
            display: inline-block;
            text-transform: uppercase;
        }

        .welcome-title {
            margin-top: 16px;
            font-size: 38px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .welcome-desc {
            color: #64748b;
            max-width: 500px;
            margin: 12px auto 0 auto;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.5;
        }

        /* Grid Card */
        .welcome-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            margin-top: 35px;
        }

        .path-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-radius: 22px;
            padding: 30px 24px;
            text-decoration: none;
            color: inherit;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.04);
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .path-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(15, 23, 42, 0.1);
            border-color: rgba(0, 107, 63, 0.3);
            background: rgba(255, 255, 255, 0.98);
        }

        .card-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            margin-bottom: 18px;
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.8);
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }

        .path-card h3 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .path-card p {
            color: #64748b;
            font-size: 12.5px;
            line-height: 1.55;
            margin-bottom: 22px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 11px 18px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 12.5px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }

        .path-card:hover .action-btn {
            padding-left: 21px;
            padding-right: 21px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .footer {
            margin-top: 35px;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        @media (max-width: 768px) {
            .top-navbar {
                padding: 0 20px;
            }
            .welcome-card-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .welcome-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    {{-- <header class="top-navbar" data-aos="fade-down" data-aos-duration="600">
        <a href="#" class="nav-brand">
            <div class="brand-logo-box">
                IT
            </div>
            <div class="brand-text">
                <h2>IT Solution</h2>
                <span>Digital Guest System</span>
            </div>
        </a>

        <div class="nav-status">
            <span style="width: 8px; height: 8px; background: #34d399; border-radius: 50%; display: inline-block;"></span>
            <span>Sistem Online</span>
        </div>
    </header> --}}

    <div class="main-content-wrapper">
        <div class="welcome-container">

            <div data-aos="fade-down" data-aos-delay="150">
                <span class="badge-pill">
                    <i class="bi bi-stars me-1"></i> Sistem Informasi Terintegrasi
                </span>
            </div>

            <h1 data-aos="fade-up" data-aos-delay="250" class="welcome-title">
                Portal Buku Tamu & Manajemen Perusahaan <br><span style="color: #006B3F;">IT Solution</span>
            </h1>

            <p data-aos="fade-up" data-aos-delay="400" class="welcome-desc">
                Silakan pilih jalur akses di bawah ini sesuai dengan peran atau keperluan kunjungan Anda.
            </p>

            <div class="welcome-card-grid">

                <a href="{{ route('check-in.step1') }}" class="path-card" data-aos="fade-right" data-aos-delay="550">
                    <div>
                        <div class="card-icon-wrapper" style="background: linear-gradient(135deg, #e6f4ea 0%, #c8e6d3 100%); color: #006B3F;">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <h3>Registrasi Tamu (Guest)</h3>
                        <p>Layanan check-in mandiri yang cepat dan praktis bagi tamu eksternal yang berkunjung ke perusahaan.</p>
                    </div>
                    <div class="action-btn" style="background: linear-gradient(135deg, #006B3F 0%, #004d2e 100%);">
                        <span>Mulai Kunjungan</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('login') }}" class="path-card" data-aos="fade-left" data-aos-delay="700">
                    <div>
                        <div class="card-icon-wrapper" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #1E40AF;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3>Autentikasi Pegawai</h3>
                        <p>Gerbang akses khusus internal: Manajemen, Front Office, PIC, Security, dan Administrator sistem.</p>
                    </div>
                    <div class="action-btn" style="background: linear-gradient(135deg, #1E40AF 0%, #1e3a8a 100%);">
                        <span>Masuk Portal</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

            </div>

            <div class="footer" data-aos="fade-up" data-aos-delay="850">
                © {{ \Carbon\Carbon::now()->year }} Digital Guest Book System. All rights reserved.
            </div>

        </div>
    </div>

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