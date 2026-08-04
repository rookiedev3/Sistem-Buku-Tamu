{{-- layout dashboard owner --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Buku Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body style="background-color: #f8fafc; margin: 0; font-family: Inter, sans-serif;">

<div class="d-flex" style="min-height: 100vh; width: 100%;">

    @include('partials.sidebar')

    <div class="d-flex flex-column flex-fill" style="min-width: 0; width: 100%;">
        
        @include('partials.navbar')

        <main class="p-4 w-100" style="flex: 1; box-sizing: border-box;">
            @yield('content')
        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>