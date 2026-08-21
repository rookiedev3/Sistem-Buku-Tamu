<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #f4f7fc;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #172033;
        }
        .container {
            max-width: 440px;
            width: 90%;
            margin: auto;
            background: #ffffff;
            padding: 36px 28px;
            border-radius: 16px;
            border: 1px solid #e8edf5;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        .icon-box {
            width: 56px;
            height: 56px;
            background: #fef2f2;
            color: #dc2626;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 24px;
            margin: 0 auto 20px auto;
        }
        h1 {
            color: #172033;
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 10px 0;
        }
        p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
            margin: 0 0 24px 0;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px 20px;
            color: white;
            background: #006B3F;
            text-decoration: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            transition: background 0.2s ease;
            box-sizing: border-box;
        }
        .btn:hover {
            background: #015231;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-box">
            ⌛
        </div>
        <h1>Sesi Telah Berakhir</h1>
        <p>Maaf, sesi Anda telah habis karena tidak ada aktivitas dalam waktu yang lama. Demi keamanan data, silakan login kembali.</p>
        <a href="{{ route('login') }}" class="btn">Kembali ke Halaman Login</a>
    </div>
</body>
</html>