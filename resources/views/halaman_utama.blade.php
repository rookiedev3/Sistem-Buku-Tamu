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
            padding: 28px;
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

        .card-content-wrapper {
            display: flex;
            gap: 22px;
            align-items: center;
            margin-bottom: 20px;
        }

        /* 🟢 UKURAN FOTO DIIPERBESAR DI SINI (130px) */
        .card-photo-area {
            width: 130px; 
            height: 130px; 
            flex-shrink: 0; 
            border-radius: 18px; 
            overflow: hidden;
            border: 3px solid #ffffff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            background: #f8fafc;
        }

        .card-photo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .path-card:hover .card-photo-area img {
            transform: scale(1.05); /* Efek zoom halus saat card disorot */
        }

        .card-text-area {
            flex-grow: 1;
        }

        .path-card h3 {
            font-size: 16.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .path-card p {
            color: #64748b;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
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
            margin-top: auto;
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
            .welcome-card-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .welcome-title {
                font-size: 28px;
            }
            .card-content-wrapper {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .card-photo-area {
                width: 110px;
                height: 110px;
            }
        }
    </style>
</head>
<body>

    <div class="main-content-wrapper">
        <div class="welcome-container">

          <div data-aos="fade-down" data-aos-delay="150" class="mb-2">
                <span style="color: #006B3F; font-size: 12.5px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase;">
                    Sistem Informasi Terintegrasi
                </span>
            </div>

            <h1 data-aos="fade-up" data-aos-delay="250" class="welcome-title">
                Portal Buku Tamu & Manajemen Perusahaan <br><span style="color: #006B3F;">IT Solution Yogyakarta</span>
            </h1>

            <p data-aos="fade-up" data-aos-delay="400" class="welcome-desc">
                Silakan pilih jalur akses di bawah ini sesuai dengan peran atau keperluan kunjungan Anda.
            </p>

            <div class="welcome-card-grid">

                <a href="{{ route('check-in.step1') }}" class="path-card" data-aos="fade-right" data-aos-delay="550">
                    <div class="card-content-wrapper">
                        <div class="card-photo-area">
                            <img src="{{ asset('images/card-guest.jpg') }}" alt="Registrasi Tamu">
                        </div>
                        <div class="card-text-area">
                            <h3>Registrasi Tamu (Guest)</h3>
                            <p>Layanan check-in mandiri yang cepat dan praktis bagi tamu eksternal yang berkunjung ke perusahaan.</p>
                        </div>
                    </div>
                    <div class="action-btn" style="background: linear-gradient(135deg, #006B3F 0%, #004d2e 100%);">
                        <span>Mulai Kunjungan</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('login') }}" class="path-card" data-aos="fade-left" data-aos-delay="700">
                    <div class="card-content-wrapper">
                        <div class="card-photo-area">
                            <img src="{{ asset('images/card-employee.jpg') }}" alt="Autentikasi Pegawai">
                        </div>
                        <div class="card-text-area">
                            <h3>Autentikasi Pegawai</h3>
                            <p>Gerbang akses khusus internal: Manajemen, Front Office, PIC, Security, dan Administrator sistem.</p>
                        </div>
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