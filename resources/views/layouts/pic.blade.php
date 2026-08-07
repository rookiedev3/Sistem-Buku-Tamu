<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal PIC / Pegawai - Buku Tamu Digital</title>
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

        /* --- SIDEBAR STYLE KONSISTEN --- */
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
            justify-content: space-between;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #e8edf5;
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: #006B3F;
            color: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
        }

        .sidebar-menu {
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-category {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 12px 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background: #f8fafc;
            color: #006B3F;
        }

        .menu-item.active {
            background: #e6f0eb;
            color: #006B3F;
            font-weight: 700;
        }

        /* --- MAIN CONTENT & NAVBAR --- */
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

    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">PIC</div>
                <div>
                    <div style="font-size: 15px; font-weight: 800; color: #172033;">Portal PIC</div>
                    <div style="font-size: 11px; color: #778195;">Pegawai / Tujuan Tamu</div>
                </div>
            </div>

            <div class="sidebar-menu">
                <div class="menu-category">Menu Utama</div>

                <a href="{{ route('pic.dashboard') }}" class="menu-item {{ request()->is('pic/dashboard*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Dashboard Tamu</span>
                </a>

                <a href="{{ route('pic.followup') }}" class="menu-item {{ request()->is('pic/followup*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                    <span>Follow Up</span>
                </a>

                <a href="{{ route('pic.leads') }}" class="menu-item {{ request()->is('pic/leads*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <span>Lead</span>
                </a>

                <a href="{{ route('pic.riwayat') }}" class="menu-item {{ request()->is('pic/riwayat*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span>Riwayat Kunjungan</span>
                </a>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Menu Navigasi -->
        <div class="sidebar-menu">
            <span style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; padding: 0 12px; margin-bottom: 4px;">Menu Utama</span>

            <a href="{{ route('pic.dashboard') }}" class="menu-item" style="color: {{ request()->is('pic/dashboard*') ? '#006B3F' : '#5c6678' }}; background: {{ request()->is('pic/dashboard*') ? '#e6f0eb' : 'transparent' }}; font-weight: {{ request()->is('pic/dashboard*') ? '700' : '600' }};">
                📊 Dashboard Tamu
            </a>

        <!-- Menu Follow Up -->
            {{-- <a href="{{ route('pic.followup')}}" class="menu-item" style="color: {{ request()->is('pic/followup*') ? '#006B3F' : '#5c6678' }}; background: {{ request()->is('pic/followup*') ? '#e6f0eb' : 'transparent' }}; font-weight: {{ request()->is('pic/followup*') ? '700' : '600' }};">
                🔄 Follow Up
            </a> --}}

            <!-- Menu Leads Baru -->
<a href="{{ route('pic.leads')}}" class="menu-item" style="color: {{ request()->is('pic/leads*') ? '#006B3F' : '#5c6678' }}; background: {{ request()->is('pic/leads*') ? '#e6f0eb' : 'transparent' }}; font-weight: {{ request()->is('pic/leads*') ? '700' : '600' }};">
    📈 Lead & Follow-Up
</a>

            <a href="{{ route('pic.riwayat')}}" class="menu-item" style="color: {{ request()->is('pic/riwayat*') ? '#006B3F' : '#5c6678' }}; background: {{ request()->is('pic/riwayat*') ? '#e6f0eb' : 'transparent' }}; font-weight: {{ request()->is('pic/riwayat*') ? '700' : '600' }};">
                📋 Riwayat Kunjungan
            </a>
        </div>

        <!-- Tombol Keluar di Bawah -->
        <div style="padding: 16px; border-top: 1px solid #e8edf5;">
=======
        <div style="padding: 20px; border-top: 1px solid #e8edf5;">
>>>>>>> 8e427235ec68350fa8830dc330078dc384db6a7d
            <form action="{{ route('logout') }}" method="post" style="margin: 0;">
                @csrf
                <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 10px; color: #dc2626; background: #fef2f2; font-size: 13px; font-weight: 700; border: none; cursor: pointer;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">
        
        <header class="navbar-top" style="height: 70px; background: #ffffff; border-bottom: 1px solid #e8edf5; display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 90; box-sizing: border-box;">
            <div>
                <h1 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Portal PIC</h1>
                <p style="font-size: 11px; font-weight: 600; color: #778195; margin: 0;">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="font-size: 12px; background: #e6f0eb; color: #006B3F; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                    🛡️ PIC
                </div>

                <div style="width: 1px; height: 24px; background: #e8edf5; margin: 0 4px;"></div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; background: #006B3F; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'PIC', 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>