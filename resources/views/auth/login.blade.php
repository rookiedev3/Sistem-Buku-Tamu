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

        /* Sisi Kanan: Form Login (Diperlebar dan disesuaikan) */
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

        /* Input Diperbesar */
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

        /* Label Diperbesar */
        .form-label {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #172033 !important;
            margin-bottom: 6px !important;
        }

        /* Sembunyikan ikon mata bawaan browser (Edge/Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            position: absolute;
            right: 0;
        }

        /* Kotak Khusus Password untuk ikon mata custom */
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container .form-control {
            padding-right: 48px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 16px;
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .password-toggle-btn:hover {
            color: #006B3F;
        }

        /* Tombol Masuk Diperbesar */
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

        /* Tombol Kembali ke Beranda */
        .btn-back-home {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-back-home:hover {
            background: #e2e8f0;
            color: #1e293b;
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
            
            <h3 class="fw-bold mb-1" style="color: #172033; font-size: 24px;">Selamat Datang! 👋</h3>
            <p class="text-secondary mb-4" style="font-size: 14px;">Silakan masukkan akun Anda untuk melanjutkan ke sistem.</p>

            @if (session('success'))
                <div style="background-color: #e6f4ea; color: #15803d; padding: 12px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #c8e6d3; font-weight: 600;">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Masukkan email Anda" autocomplete="email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password Anda">
                        <button type="button" class="password-toggle-btn" id="togglePassword">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 14px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" style="cursor: pointer; width: 18px; height: 18px; margin-top: 2px;">
                        <label class="form-check-label ms-1" for="remember" style="color: #64748b; font-weight: 600; cursor: pointer;">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" style="color: #006B3F; text-decoration: none; font-weight: 700;">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-custom-login w-100 shadow-sm mb-3">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mb-4">
                <a href="{{ url('/') }}" class="btn-back-home shadow-sm">
                    <span style="font-size: 16px; line-height: 1; color: #475569;">&#8592;</span> Kembali ke Beranda
                </a>
            </div>

            <div class="text-center" style="font-size: 14px; color: #64748b;">
                Belum punya akun? 
                <a href="{{ route('register') }}" style="color: #006B3F; text-decoration: none; font-weight: 700;">Daftar Sekarang</a>
            </div>

        </div>

    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.className = type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>