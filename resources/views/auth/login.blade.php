<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Guest Registration</title>

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

                        Selamat Datang

                    </h4>

                    <p class="text-secondary mb-4">

                        Silakan login terlebih dahulu.

                    </p>

                    
                    @if (session('success'))
                        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 8px; font-size: 12px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; font-size: 12px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.proses') }}" method="post">
                         {{-- //jika sudah ada controllernya nanti ditambahkan @csrf --}}
                         @csrf

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input type="email" name="email" class="form-control" placeholder="Masukkan email">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Password

                            </label>

                            <input type="password" name="password" class="form-control" placeholder="Masukkan password">

                        </div>

                        <div class="d-flex justify-content-between mb-4">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Ingat Saya
                                </label>

                            </div>

                            <a href="#">

                                Lupa Password?

                            </a>

                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100">

                            Login

                        </button>

                    </form>

                    <div class="text-center mt-4">

                        Belum punya akun?

                       <a href="{{ route('register') }}">
                        Daftar
                    </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</body>

</html>