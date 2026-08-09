<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Buku Tamu Digital</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #172033;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid #e8edf5;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 10px 30px rgba(31, 53, 97, 0.05);
        }

        .form-control {
            padding: 11px 14px;
            border: 1px solid #e8edf5;
            border-radius: 10px;
            font-size: 13px;
            color: #172033;
            background: #fbfcfe;
            outline: none;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #006B3F;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1);
        }

        .btn-custom-submit {
            background: #006B3F;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s ease;
        }

        .btn-custom-submit:hover {
            background: #013220;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 48px; height: 48px; background: #e6f4ed; color: #006B3F; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin-bottom: 12px;">
                🔐
            </div>
            <h3 style="font-size: 20px; font-weight: 800; color: #172033; margin-bottom: 4px;">Reset Password</h3>
            <p style="font-size: 12.5px; color: #778195; margin: 0;">Silakan masukkan password baru untuk akun Anda.</p>
        </div>

        @if ($errors->any())
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 10px; font-size: 12px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 600;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #5c6678; text-transform: uppercase; display: block; margin-bottom: 6px;">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required readonly style="background-color: #f1f5f9; color: #64748b; cursor: not-allowed;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #5c6678; text-transform: uppercase; display: block; margin-bottom: 6px;">Password Baru</label>
                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
            </div>

            <div class="mb-4">
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #5c6678; text-transform: uppercase; display: block; margin-bottom: 6px;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn-custom-submit shadow-sm">Simpan Password Baru</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>