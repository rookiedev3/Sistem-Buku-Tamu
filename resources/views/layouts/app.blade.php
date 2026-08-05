{{-- layout dashboard owner --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Buku Tamu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f4f7fc;
            margin: 0;
            display: flex;
            min-height: 100vh;
            color: #172033;
        }

        /* Pembungkus utama agar konten di sebelah kanan sidebar secara presisi */
        .main-wrapper {
            margin-left: 260px; /* Menyesuaikan lebar sidebar fixed */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        .content-area {
            padding: 32px;
            flex-grow: 1;
            background-color: #f4f7fc;
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-wrapper">
        
        @include('partials.navbar')

        <main class="content-area">
            @yield('content')
        </main>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>