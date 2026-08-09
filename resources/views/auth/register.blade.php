<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | IT Solution - Guest Registration System</title>

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

        /* Sisi Kiri: Branding */
        .login-brand-side {
            background: linear-gradient(135deg, #013220, #159A5C);
            color: white;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
        }

        /* Sisi Kanan: Form Register (Diperlebar dan disesuaikan agar lebih lega ke atas) */
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

        /* Input & Select Diperbesar */
        .form-control, .form-select {
            border-radius: 12px;
            padding: 13px 18px;
            font-size: 14.5px;
            border: 1px solid #d1d9e2;
            background-color: #fbfcfe;
            color: #172033;
        }

        .form-control:focus, .form-select:focus {
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

        /* Menghilangkan ikon mata bawaan dari browser (Edge & Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            position: absolute;
            right: 0;
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
        }

        .password-toggle-btn:hover {
            color: #006B3F;
        }

        /* Tombol Daftar Diperbesar */
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
            
            <h3 class="fw-bold mb-1" style="color: #172033; font-size: 24px;">Buat Akun Baru 🚀</h3>
            <p class="text-secondary mb-4" style="font-size: 14px;">Silakan lengkapi data untuk mendaftar ke sistem.</p>

            <form method="POST" action="{{ route('register.proses') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Masukkan email aktif" required>
                        @error('email')
                            <div class="invalid-feedback" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                        @error('phone')
                            <div class="invalid-feedback" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 6 karakter" required>
                            <button type="button" class="password-toggle-btn" id="togglePassword">
                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                placeholder="Ulangi password" required>
                            <button type="button" class="password-toggle-btn" id="togglePasswordConfirm">
                                <i class="bi bi-eye-slash" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-custom-login w-100 shadow-sm mt-2">
                    Daftar Akun Sekarang
                </button>
            </form>

            <div class="text-center mt-4" style="font-size: 14px; color: #64748b;">
                Sudah punya akun? 
                <a href="{{ route('login') }}" style="color: #006B3F; text-decoration: none; font-weight: 700;">Login di sini</a>
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

        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirm = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        togglePasswordConfirm.addEventListener('click', function () {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            eyeIconConfirm.className = type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>