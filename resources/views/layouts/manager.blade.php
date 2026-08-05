<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Manager - Buku Tamu Digital</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            min-height: 100vh;
            color: #172033;
        }

        /* SIDEBAR KHUSUS MANAGER */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e8edf5;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-sizing: border-box;
        }

        .sidebar-brand {
            padding: 24px;
            border-bottom: 1px solid #e8edf5;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu {
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s;
        }

        /* KONTEN SEBELAH KANAN */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        .navbar-top {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #e8edf5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .content-area {
            padding: 32px;
            flex-grow: 1;
            background-color: #f4f7fc;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR KHUSUS MANAGER -->
    <aside class="sidebar">
        <!-- Logo / Header Sidebar -->
        <div class="sidebar-brand">
            <div style="width: 38px; height: 38px; background: #1e3a8a; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px;">
                M
            </div>
            <div>
                <h2 style="font-size: 14px; font-weight: 800; color: #172033; margin: 0;">Portal Manager</h2>
                <span style="font-size: 11px; color: #778195; font-weight: 600;">Pengawasan & Laporan</span>
            </div>
        </div>

        <!-- Menu Navigasi Manager -->
        <div class="sidebar-menu">
            <span style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; padding: 0 12px; margin-bottom: 4px;">Menu Utama</span>

            <a href="{{ url('/manager/dashboard') }}" class="menu-item" style="color: {{ request()->is('manager/dashboard*') ? '#1e3a8a' : '#5c6678' }}; background: {{ request()->is('manager/dashboard*') ? '#eff6ff' : 'transparent' }}; font-weight: {{ request()->is('manager/dashboard*') ? '700' : '600' }};">
                📊 Dashboard Monitoring
            </a>

            <a href="{{ url('/manager/kunjungan') }}" class="menu-item" style="color: {{ request()->is('manager/kunjungan*') ? '#1e3a8a' : '#5c6678' }}; background: {{ request()->is('manager/kunjungan*') ? '#eff6ff' : 'transparent' }}; font-weight: {{ request()->is('manager/kunjungan*') ? '700' : '600' }};">
                📋 Semua Kunjungan
            </a>

            <a href="{{ url('/manager/leads') }}" class="menu-item" style="color: {{ request()->is('manager/leads*') ? '#1e3a8a' : '#5c6678' }}; background: {{ request()->is('manager/leads*') ? '#eff6ff' : 'transparent' }}; font-weight: {{ request()->is('manager/leads*') ? '700' : '600' }};">
                📈 Pipeline Lead Tim
            </a>

            <a href="{{ url('/manager/laporan') }}" class="menu-item" style="color: {{ request()->is('manager/laporan*') ? '#1e3a8a' : '#5c6678' }}; background: {{ request()->is('manager/laporan*') ? '#eff6ff' : 'transparent' }}; font-weight: {{ request()->is('manager/laporan*') ? '700' : '600' }};">
                📥 Laporan & Export
            </a>
        </div>

        <!-- Tombol Keluar di Bawah -->
        <div style="padding: 16px; border-top: 1px solid #e8edf5;">
            <form action="{{ route('logout') }}" method="get" style="margin: 0;">
                @csrf
                <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 10px; color: #dc2626; background: #fef2f2; font-size: 13px; font-weight: 700; border: none; cursor: pointer;">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN SEBELAH KANAN -->
    <div class="main-wrapper">
        
        <!-- Navbar Atas -->
        <header class="navbar-top">
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">
                Selamat bertugas, <strong style="color: #172033;">Bapak/Ibu Manager Operasional</strong>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="font-size: 12px; background: #eff6ff; color: #1e3a8a; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                    🛡️ Akses: Pengawasan Penuh
                </div>

                <div style="width: 36px; height: 36px; background: #1e3a8a; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">
                    MGR
                </div>
            </div>
        </header>

        <!-- Tempat Konten Dinamis -->
        <main class="content-area">
            @yield('content')
        </main>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>