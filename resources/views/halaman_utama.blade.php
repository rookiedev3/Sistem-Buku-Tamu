<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Buku Tamu Digital</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f4f7fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #172033;
            padding: 20px;
        }

        .welcome-container {
            width: 100%;
            max-width: 850px;
            text-align: center;
        }

        .welcome-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 32px;
        }

        .path-card {
            background: #ffffff;
            border: 1px solid #e8edf5;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .path-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }

        .card-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .welcome-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="welcome-container">
        <!-- Bagian Judul Utama -->
        <div style="margin-bottom: 8px;">
            <span style="background: #e2e8f0; color: #334155; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                Sistem Informasi Terintegrasi
            </span>
        </div>
        <h1 style="font-size: 28px; font-weight: 900; color: #172033; margin-top: 12px; margin-bottom: 8px;">
            Buku Tamu & Portal Perusahaan
        </h1>
        <p style="font-size: 14px; color: #778195; max-width: 500px; margin: 0 auto;">
            Silakan pilih jalur akses di bawah ini sesuai dengan keperluan Anda hari ini.
        </p>

        <!-- Dua Jalur Pilihan (Dual Pathway) -->
        <div class="welcome-card-grid">
            
            <!-- Jalur 1: Tamu / Guest -->
            <a href="{{ route('check-in.step1') }}" class="path-card">
                <div class="card-icon" style="background: #e6f4ed; color: #006B3F;">
                    👋
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 8px;">
                    Daftar Tamu (Guest)
                </h3>
                <p style="font-size: 13px; color: #778195; margin-bottom: 24px; line-height: 1.5;">
                    Untuk pengunjung atau tamu yang ingin melakukan check-in kunjungan ke perusahaan.
                </p>
                <span style="background: #006B3F; color: white; padding: 10px 24px; border-radius: 12px; font-size: 13px; font-weight: 700; width: 100%;">
                    Mulai Daftar Tamu &rarr;
                </span>
            </a>

            <!-- Jalur 2: Login Pegawai / Internal -->
            <a href="{{ route('login') }}" class="path-card">
                <div class="card-icon" style="background: #eff6ff; color: #1e3a8a;">
                    🔐
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 8px;">
                    Login Pegawai & Manajemen
                </h3>
                <p style="font-size: 13px; color: #778195; margin-bottom: 24px; line-height: 1.5;">
                    Akses khusus Owner, Manager, PIC/Sales, Security, dan Administrator.
                </p>
                <span style="background: #1e3a8a; color: white; padding: 10px 24px; border-radius: 12px; font-size: 13px; font-weight: 700; width: 100%;">
                    Masuk Portal &rarr;
                </span>
            </a>

        </div>

        <!-- Footer Kecil -->
        <div style="margin-top: 40px; font-size: 12px; color: #94a3b8; font-weight: 600;">
            &copy; {{ \Carbon\Carbon::now()->year }} Digital Guest Book System. All rights reserved.
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>