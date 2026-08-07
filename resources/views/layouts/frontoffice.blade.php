<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Office Panel - Buku Tamu Digital</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

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

        /* --- NOTIFICATION DROPDOWN --- */
        .notification-dropdown {
            position: relative;
            display: inline-block;
        }

        .btn-bell {
            background: #f1f5f9;
            border: 1px solid #e8edf5;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-bell:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }

        .btn-bell .badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 6px;
            border-radius: 20px;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dropdown-box {
            position: absolute;
            right: 0;
            top: calc(100% + 1px);
            width: 320px;
            background: #ffffff;
            border: 1px solid #e8edf5;
            border-radius: 14px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
        }

        .notification-dropdown:hover .dropdown-box {
            display: flex;
        }

        .notif-item {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s ease;
            cursor: pointer;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item strong {
            display: block;
            font-size: 13px;
            color: #172033;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .notif-item p {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 6px 0;
            line-height: 1.4;
        }

        .notif-item small {
            font-size: 10px;
            color: #94a3b8;
            font-weight: 600;
        }

        .notif-item-empty {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
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

                <a href="/pengguna" class="menu-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                    <span>Manajemen Pengguna</span>
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

       <header class="navbar-top" style="height: 70px; background: #ffffff; border-bottom: 1px solid #e8edf5; display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 90; box-sizing: border-box;">
            
            <div>
                <h1 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Portal Front Office</h1>
                <p style="font-size: 11px; font-weight: 600; color: #778195; margin: 0;">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                @auth
                @php
                // Ambil 5 notifikasi belum dibaca khusus milik user yang sedang login
                $myNotifications = auth()->user()->notifications()
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();

                $unreadCount = auth()->user()->notifications()
                ->whereNull('read_at')
                ->count();
                @endphp

                <div class="notification-dropdown">
                    <!-- Tombol Lonceng dengan Badge Jumlah -->
                    <button type="button" class="btn-bell">
                        🔔
                        @if($unreadCount > 0)
                        <span class="badge">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <!-- List Notifikasi -->
                    <div class="dropdown-box">
                        <!-- Dropdown Header -->
                        <div style="padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                            <span style="font-size: 12px; font-weight: 700; color: #172033;">Notifikasi Baru</span>
                            @if($unreadCount > 0)
                            <form action="{{ route('frontoffice.notifications.readAll') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: #006B3F; font-size: 10px; font-weight: 700; cursor: pointer; padding: 0;">
                                    Tandai semua dibaca
                                </button>
                            </form>
                            @endif
                        </div>

                        <!-- Dropdown Items -->
                        <div style="max-height: 280px; overflow-y: auto; display: flex; flex-direction: column;">
                            @forelse($myNotifications as $notif)
                            <div class="notif-item" style="display: flex; justify-content: space-between; align-items: start; gap: 10px;">
                                <div style="flex-grow: 1;">
                                    <strong style="display: flex; align-items: center; gap: 6px;">
                                        <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block;"></span>
                                        {{ $notif->title }}
                                    </strong>
                                    <p>{{ $notif->body }}</p>
                                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <form action="{{ route('frontoffice.notifications.read', $notif->id) }}" method="POST" style="margin: 0; flex-shrink: 0;">
                                    @csrf
                                    <button type="submit" title="Tandai dibaca" style="background: #e6f4ed; color: #006B3F; border: 1px solid #bbf7d0; border-radius: 6px; padding: 2px 6px; font-size: 10px; font-weight: 700; cursor: pointer;">
                                        ✓
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div class="notif-item-empty">
                                Tidak ada notifikasi baru.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endauth

                <div style="font-size: 12px; background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                    🟢 Operasional Aktif (Sleman)
                </div>

                <div style="width: 1px; height: 24px; background: #e8edf5; margin: 0 4px;"></div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; background: #006B3F; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'FO', 0, 2)) }}
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