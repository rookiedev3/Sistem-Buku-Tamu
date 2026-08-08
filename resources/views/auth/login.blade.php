<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | IT Solution - Guest Registration System</title>

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
            background-color: #f4f7fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container Card Memanjang */
        .login-wrapper {
            width: 100%;
            max-width: 950px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(31, 53, 97, 0.1);
            overflow: hidden;
            display: flex;
            border: 1px solid #e8edf5;
        }

        /* Sisi Kiri: Branding / Ilustrasi Hijau Korporat (#006B3F) */
        .login-brand-side {
            flex: 1;
            background: linear-gradient(135deg, #006B3F 0%, #004d2e 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Sisi Kanan: Form Login */
        .login-form-side {
            flex: 1.1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-box {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            color: #fff;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13.5px;
            border: 1px solid #e8edf5;
            background-color: #fafbfc;
        }

        .form-control:focus {
            border-color: #006B3F;
            box-shadow: 0 0 0 4px rgba(0, 107, 63, 0.1);
            background-color: #fff;
        }

        .btn-custom-login {
            background-color: #006B3F;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: 0.2s ease;
        }

        .btn-custom-login:hover {
            background-color: #004d2e;
            color: #fff;
        }

        /* Responsif untuk layar HP (otomatis menumpuk ke bawah jika di mobile) */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 100%;
                margin: 20px;
                border-radius: 16px;
            }
            .login-brand-side {
                padding: 30px;
            }
            .login-form-side {
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        
        <div class="login-brand-side">
            <div>
                <div class="logo-box">IT</div>
                <h2 class="fw-bold mb-2" style="font-size: 24px;">IT Solution</h2>
                <p class="text-white-50" style="font-size: 13.5px;">Sistem Buku Tamu & Registrasi Kunjungan Digital Perusahaan.</p>
            </div>
            <div>
                <p class="text-white-50 mb-0" style="font-size: 12px;">&copy; 2026 IT Solution Corp. All rights reserved.</p>
            </div>
        </div>

        <div class="login-form-side">
            
            <h3 class="fw-bold mb-1" style="color: #172033; font-size: 22px;">Selamat Datang! 👋</h3>
            <p class="text-secondary mb-4" style="font-size: 13px;">Silakan masukkan akun Anda untuk melanjutkan.</p>

            @if (session('success'))
                <div style="background-color: #e6f4ea; color: #15803d; padding: 12px 14px; border-radius: 10px; font-size: 12.5px; margin-bottom: 20px; border: 1px solid #c8e6d3; font-weight: 600;">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 10px; font-size: 12.5px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #172033;">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="nama@perusahaan.com">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #172033;">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 13px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" style="cursor: pointer;">
                        <label class="form-check-label" for="remember" style="color: #64748b; font-weight: 600; cursor: pointer;">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="#" style="color: #006B3F; text-decoration: none; font-weight: 700;">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-custom-login w-100 shadow-sm">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="text-center mt-4" style="font-size: 13px; color: #64748b;">
                Belum punya akun? 
                <a href="{{ route('register') }}" style="color: #006B3F; text-decoration: none; font-weight: 700;">Daftar Sekarang</a>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>