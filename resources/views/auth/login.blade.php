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
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Container Card Full Layar dengan Layout Grid */
        .login-wrapper {
            width: 100vw;
            min-height: 100vh;
            background: #ffffff;
            display: grid;
            grid-template-columns: 1.1fr 1.3fr;
            box-sizing: border-box;
        }

        /* Sisi Kiri: Branding Perusahaan */
        .login-brand-side {
            background: linear-gradient(145deg, #01281b 0%, #013220 40%, #006B3F 100%);
            color: white;
            padding: 70px 50px 50px 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        /* Lingkaran / Bola-Bola Besar Transparan di Latar Belakang */
        .login-brand-side::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-brand-side::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Header Branding */
        .brand-header {
            margin-top: 15px; 
        }

        /* Ukuran Gambar Logo Diperkecil & Transparan Putih */
        .logo-box-img {
            max-height: 28px;
            width: auto;
            margin-bottom: 16px;
            display: block;
            filter: brightness(0) invert(1);
        }

        /* Container Visual Ilustrasi di Tengah */
        .brand-illustration {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .brand-illustration img {
            width: 100%;
            max-width: 280px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.25));
        }

        /* Sisi Kanan: Form Login */
        .login-form-side {
            padding: 40px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-sizing: border-box;
            background: #ffffff;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            color: #94a3b8;
            font-size: 17px;
            z-index: 10;
        }

        .form-control {
            border-radius: 12px;
            padding: 13px 18px 13px 50px;
            font-size: 14.5px;
            border: 1px solid #d1d9e2;
            background-color: #fbfcfe;
            color: #172033;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #006B3F;
            box-shadow: 0 0 0 4px rgba(0, 107, 63, 0.1);
            background-color: #fff;
        }

        .form-label {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #172033 !important;
            margin-bottom: 6px !important;
        }

        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

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
            z-index: 10;
        }

        .password-toggle-btn:hover {
            color: #006B3F;
        }

        .btn-custom-login {
            background-color: #006B3F;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 107, 63, 0.25);
        }

        .btn-custom-login:hover {
            background-color: #005431;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-back-home {
            background: #f8fafc;
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
            background: #f1f5f9;
            color: #1e293b;
        }

        @media (max-width: 991px) {
            .login-wrapper {
                grid-template-columns: 1fr !important;
            }
            .login-brand-side {
                padding: 40px 30px !important;
            }
            .brand-illustration {
                display: none;
            }
            .login-form-side {
                padding: 40px 30px !important;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        
        <!-- Sisi Kiri -->
        <div class="login-brand-side">
            <div class="brand-header" style="position: relative; z-index: 2;">
                <!-- Logo Perusahaan -->
                <img src="{{ asset('images/foto-perusahaan.jpg') }}" alt="Logo Perusahaan" class="logo-box-img">
                
                <p class="text-white-50 mb-0" style="font-size: 13.5px; line-height: 1.6;">Sistem Buku Tamu & Registrasi Kunjungan Digital Perusahaan.</p>
            </div>



            <div style="position: relative; z-index: 2;">
                <p class="text-white-50 mb-0" style="font-size: 12px;">&copy; {{ date('Y') }} IT Solution Corp. All rights reserved.</p>
            </div>
        </div>

        <!-- Sisi Kanan -->
        <div class="login-form-side">
            
            <h3 class="fw-bold mb-1" style="color: #172033; font-size: 26px; letter-spacing: -0.5px;">Selamat Datang! </h3>
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
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Masukkan alamat email" autocomplete="email">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-container">
                        <i class="bi bi-lock input-icon"></i>
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