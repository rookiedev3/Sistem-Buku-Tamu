<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Guest Registration</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-page">

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-5 col-md-7">

                <div class="login-card shadow">

                    <div class="text-center mb-4">

                        <div class="logo-circle">

                            IT

                        </div>

                        <h3 class="fw-bold mt-3">

                            IT Solution

                        </h3>

                        <p class="text-secondary">

                            Guest Registration System

                        </p>

                    </div>

                    <h4 class="fw-bold mb-1">
                     Buat Akun
                    </h4>

                    <p class="text-secondary mb-4">
                        Silakan isi data untuk membuat akun.
                    </p>

                    <form action="{{ route('login') }}" method="GET">
                         {{-- //jika sudah ada controllernya nanti ditambahkan @csrf --}}
                    
                

    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" placeholder="Masukkan email">
    </div>

    <div class="mb-3">
        <label class="form-label">Nomor WhatsApp</label>
        <input type="text" class="form-control" placeholder="08xxxxxxxxxx">
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" placeholder="Masukkan password">
    </div>

    <div class="mb-4">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" placeholder="Ulangi password">
    </div>

    <button type="submit" class="btn btn-primary-custom w-100">
        Daftar
    </button>

</form>

                    <div class="text-center mt-4">

                        Sudah punya akun?

<a href="{{ route('login') }}">
    Login
</a>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>