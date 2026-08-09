<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | IT Solution - Guest Registration System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            position: relative;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Container Card Full Layar */
        .login-wrapper {
            width: 100vw;
            min-height: 100vh;
            background: #ffffff;
            box-shadow: none;
            border-radius: 0;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            box-sizing: border-box;
            border: none;
        }

        /* Sisi Kiri: Branding / Ilustrasi Hijau Korporat (#006B3F) */
        .login-brand-side {
            background: linear-gradient(135deg, #013220, #159A5C);
            color: white;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
        }

        /* Sisi Kanan: Form Lupa Password */
        .login-form-side {
            padding: 40px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-sizing: border-box;
            background: #ffffff;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 22px;
            color: #fff;
            margin-bottom: 24px;
        }

        .form-control {
            border-radius: 12px;
            padding: 13px 18px;
            font-size: 14.5px;
            border: 1px solid #d1d9e2;
            background-color: #fbfcfe;
            color: #172033;
        }

        .form-control:focus {
            border-color: #006B3F;
            box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1);
            background-color: #fff;
        }

        .form-label {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #172033 !important;
            margin-bottom: 6px !important;
        }

        .btn-custom-login {
            background-color: #006B3F;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 15px;
            transition: 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 107, 63, 0.25);
        }

        .btn-custom-login:hover {
            background-color: #004d2e;
            color: #fff;
        }

        /* Responsif untuk layar HP & Tablet */
        @media (max-width: 991px) {
            .login-wrapper {
                grid-template-columns: 1fr !important;
            }
            .login-brand-side {
                padding: 40px 30px !important;
            }
            .login-form-side {
                padding: 40px 30px !important;
            }
        }
        @media (max-width: 480px) {
            .login-brand-side {
                padding: 30px 20px !important;
            }
            .login-form-side {
                padding: 30px 20px !important;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        
        <div class="login-brand-side">
            <div>
                <div class="logo-box">IT</div>
                <h2 class="fw-bold mb-2" style="font-size: 32px; line-height: 1.3;">IT Solution</h2>
                <p class="text-white-50" style="font-size: 14px; line-height: 1.6;">Sistem Buku Tamu & Registrasi Kunjungan Digital Perusahaan.</p>
            </div>
            <div>
                <p class="text-white-50 mb-0" style="font-size: 12px;">&copy; {{ date('Y') }} IT Solution Corp. All rights reserved.</p>
            </div>
        </div>

        <div class="login-form-side">
            
            <h3 class="fw-bold mb-1" style="color: #172033; font-size: 24px;">Lupa Password? 🔑</h3>
            <p class="text-secondary mb-4" style="font-size: 14px; line-height: 1.5;">Masukkan email terdaftar Anda, kami akan mengirimkan tautan untuk mereset password akun Anda.</p>

            @if (session('status'))
                <div style="background-color: #e6f4ea; color: #15803d; padding: 12px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #c8e6d3; font-weight: 600;">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="nama@perusahaan.com" autocomplete="email">
                </div>

                <button type="submit" class="btn btn-custom-login w-100 shadow-sm mb-3">
                    Kirim Link Reset Password
                </button>
            </form>

            <div class="text-center" style="font-size: 14px;">
                <a href="{{ route('login') }}" style="color: #006B3F; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Login
                </a>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>