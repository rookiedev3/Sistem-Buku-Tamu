<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sesi Berakhir</title>
    <!-- Tambahkan link CSS kamu di sini -->
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background-color: #f8f9fa; }
        .container { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
        .btn { display: inline-block; padding: 10px 20px; color: white; background: #0d6efd; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sesi Telah Berakhir</h1>
        <p>Maaf, sesi Anda telah habis karena tidak ada aktivitas dalam waktu yang lama. Demi keamanan, silakan login kembali.</p>
        <a href="{{ route('login') }}" class="btn">Kembali ke Halaman Login</a>
    </div>
</body>
</html>