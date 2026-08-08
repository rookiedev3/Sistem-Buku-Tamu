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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Lebar card pembungkus sama persis dengan Login (950px) */
        .login-wrapper {
            width: 100%;
            max-width: 950px; 
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(31, 53, 97, 0.1);
            overflow: hidden;
            display: flex;
            border: 1px solid rgba(255, 255, 255, 0.95);
        }

        /* Sisi Kiri: Branding */
        .login-brand-side {
            flex: 1;
            background: linear-gradient(135deg, #006B3F 0%, #004d2e 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Sisi Kanan: Form Register (Padding dibuat lega agar card naik tingginya) */
        .login-form-side {
            flex: 1.1;
            padding: 45px 50px;
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

        .form-control, .form-select {
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 13px;
            border: 1px solid #e8edf5;
            background-color: #fafbfc;
        }

        .form-control:focus, .form-select:focus {
            border-color: #006B3F;
            box-shadow: 0 0 0 4px rgba(0, 107, 63, 0.1);
            background-color: #fff;
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
            padding-right: 45px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .password-toggle-btn:hover {
            color: #006B3F;
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
            
            <h3 class="fw-bold mb-1" style="color: #172033; font-size: 22px;">Buat Akun Baru 🚀</h3>
            <p class="text-secondary mb-3" style="font-size: 13px;">Silakan lengkapi data untuk mendaftar.</p>

            <form method="POST" action="{{ route('register.proses') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; font-weight: 700; color: #172033;">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; font-weight: 700; color: #172033;">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Masukkan email Anda" required>
                        @error('email')
                            <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; font-weight: 700; color: #172033;">No. WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                        @error('phone')
                            <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; font-weight: 700; color: #172033;">Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; font-weight: 700; color: #172033;">Password <span class="text-danger">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 6 karakter" required>
                            <button type="button" class="password-toggle-btn" id="togglePassword">
                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label mb-1" style="font-size: 12px; font-weight: 700; color: #172033;">Konfirmasi <span class="text-danger">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                placeholder="Ulangi password" required>
                            <button type="button" class="password-toggle-btn" id="togglePasswordConfirm">
                                <i class="bi bi-eye-slash" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-custom-login w-100 shadow-sm mt-1">
                    Daftar Akun
                </button>
            </form>

            <div class="text-center mt-3" style="font-size: 13px; color: #64748b;">
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