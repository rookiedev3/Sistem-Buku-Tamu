<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Office Panel - Buku Tamu Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f4f7fc;
            display: flex;
            min-height: 100vh;
            color: #172033;
        }

        /* --- SIDEBAR STYLE --- */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e8edf5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100vh;
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
            font-size: 18px;
        }

        .sidebar-menu {
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
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
        }

        .navbar {
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
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">FO</div>
                <div>
                    <div style="font-size: 15px; font-weight: 800; color: #172033;">Front Office</div>
                    <div style="font-size: 11px; color: #778195;">Guest Management</div>
                </div>
            </div>

            <div class="sidebar-menu">
                <div class="menu-category">Utama</div>

                <a href="/frontoffice/dashboard" class="menu-item {{ request()->routeIs('frontoffice.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    <span>Dashboard Antrian</span>
                </a>

                <div class="menu-category">Fitur Staf</div>

                <a href="/frontoffice/history" class="menu-item {{ request()->routeIs('frontoffice.history') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                    <span>Riwayat Kunjungan</span>
                </a>

                <a href="/frontoffice/appointment" class="menu-item {{ request()->routeIs('frontoffice.appointment') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span>Janji Temu (Appointment)</span>
                </a>

                <a href="/frontoffice/pegawai" class="menu-item {{ request()->routeIs('frontoffice.pegawai') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span>Daftar Pegawai / PIC</span>
                </a>
            </div>
        </div>

        <div style="padding: 20px; border-top: 1px solid #e8edf5;">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="display: flex; align-items: center; gap: 10px; color: #dc2626; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 10px; background: #fef2f2; border: none; cursor: pointer; width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">

        <header class="navbar">
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">
                Selamat bertugas, <strong style="color: #172033;">Staf Front Office 1</strong>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="font-size: 12px; background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                    🟢 Operasional Aktif (Sleman)
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; background: #006B3F; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">
                        FO
                    </div>
                </div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>

    </div>
</body>

</html>