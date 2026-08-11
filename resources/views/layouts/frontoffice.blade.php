<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Office Panel - Buku Tamu Digital</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            overflow-x: hidden; /* Mencegah halaman bisa digeser ke kanan/kiri */
        }

        /* --- SIDEBAR STYLE (DISAMAKAN DENGAN MANAGER) --- */
        .sidebar {
            width: 260px;
            background: #013220;
            border-right: 1px solid #04472d;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Kondisi saat Sidebar Dikecilkan di Laptop (Menyusut jadi Ikon Saja) */
        .sidebar.collapsed {
            width: 80px !important;
        }
        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .menu-category,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed form button span {
            display: none !important;
        }
        .sidebar.collapsed .sidebar-brand {
            justify-content: center !important;
            padding: 20px 10px !important;
        }
        .sidebar.collapsed .sidebar-menu {
            padding: 16px 10px !important;
            align-items: center !important;
        }
        .sidebar.collapsed .menu-item {
            justify-content: center !important;
            padding: 12px !important;
        }
        .sidebar.collapsed .sidebar-footer {
            padding: 16px 10px !important;
        }

        /* --- MAIN CONTENT & NAVBAR WRAPPER --- */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-wrapper.expanded {
            margin-left: 80px !important;
            width: calc(100% - 80px) !important;
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
            width: 100%;
            box-sizing: border-box;
        }

        .content-area {
            padding: 32px;
            flex-grow: 1;
            background-color: #f4f7fc;
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
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s ease;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item-content {
            flex-grow: 1;
            min-width: 0;
        }

        .notif-item strong {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #172033;
            font-weight: 700;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .notif-item p {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 6px 0;
            line-height: 1.5;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: pre-line;
        }

        .notif-item small {
            font-size: 10px;
            color: #94a3b8;
            font-weight: 600;
        }

        .notif-item-mark-btn {
            background: #e6f4ed;
            color: #006B3F;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 3px 7px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            flex-shrink: 0;
            line-height: 1;
            margin-top: 2px;
        }

        .notif-item-empty {
            padding: 24px 16px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* --- MEDIA QUERY KHUSUS HP / TABLET (Layar di bawah 992px) --- */
        @media(max-width: 992px) {
            .sidebar {
                width: 260px !important;
                transform: translateX(-100%); /* Tertutup rapi di luar layar sebelah kiri */
            }
            .sidebar.mobile-show {
                transform: translateX(0) !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .main-wrapper.mobile-shifted {
                margin-left: 260px !important;
                width: calc(100% - 260px) !important;
            }
            .content-area {
                padding: 16px;
            }
            .navbar-top {
                padding: 0 16px !important;
            }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
            
            <div class="sidebar-brand" style="padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #04472d; flex-shrink: 0;">
                <div class="sidebar-brand-icon" style="width: 40px; height: 40px; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #ffffff; border: 1px solid #04472d; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex-shrink: 0;">
                    <img src="{{ asset('images/logo-perusahaan.jpg') }}" alt="Logo Perusahaan" style="width: 100%; height: 100%; object-fit: contain;">
                </div> 

                <div class="sidebar-brand-text" style="display: flex; flex-direction: column; overflow: hidden;">
                    <span style="font-size: 14px; font-weight: 800; color: #ffffff; text-transform: capitalize; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ Auth::user()->name ?? 'Portal Front Office' }}
                    </span>
                    <span style="font-size: 11px; font-weight: 600; color: #C7AB6B; text-transform: uppercase; letter-spacing: 0.5px;">
                        Front Office
                    </span>
                </div>
            </div>

            <div class="sidebar-menu" style="padding: 16px; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; flex-grow: 1;">
                <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 6px 12px 2px;">Utama</div>

                <a href="/frontoffice/dashboard" class="menu-item {{ request()->routeIs('frontoffice.dashboard') ? 'active' : '' }}" title="Dashboard Antrian" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->routeIs('frontoffice.dashboard') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->routeIs('frontoffice.dashboard') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->routeIs('frontoffice.dashboard') ? '#C7AB6B' : 'transparent' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    <span>Dashboard Antrian</span>
                </a>

                <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px 2px; margin-top: 4px;">Fitur Staf</div>

                <a href="/frontoffice/guests" class="menu-item {{ request()->routeIs('frontoffice.guest') || request()->is('frontoffice/guests*') ? 'active' : '' }}" title="Daftar Tamu" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ (request()->routeIs('frontoffice.guest') || request()->is('frontoffice/guests*')) ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ (request()->routeIs('frontoffice.guest') || request()->is('frontoffice/guests*')) ? '700' : '600' }}; border-radius: 10px; background: {{ (request()->routeIs('frontoffice.guest') || request()->is('frontoffice/guests*')) ? '#C7AB6B' : 'transparent' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span>Daftar Tamu (Guests)</span>
                </a>

                <a href="/frontoffice/history" class="menu-item {{ request()->routeIs('frontoffice.history') ? 'active' : '' }}" title="Riwayat Kunjungan" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->routeIs('frontoffice.history') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->routeIs('frontoffice.history') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->routeIs('frontoffice.history') ? '#C7AB6B' : 'transparent' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                    <span>Riwayat Kunjungan</span>
                </a>

                <a href="/frontoffice/appointment" class="menu-item {{ request()->routeIs('frontoffice.appointment') ? 'active' : '' }}" title="Janji Temu" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->routeIs('frontoffice.appointment') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->routeIs('frontoffice.appointment') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->routeIs('frontoffice.appointment') ? '#C7AB6B' : 'transparent' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span>Janji Temu (Appointment)</span>
                </a>

                <a href="/pengguna" class="menu-item {{ request()->routeIs('user.*') ? 'active' : '' }}" title="Manajemen Pengguna" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->routeIs('user.*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->routeIs('user.*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->routeIs('user.*') ? '#C7AB6B' : 'transparent' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                    <span>Manajemen Pengguna</span>
                </a>

                <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px 2px; margin-top: 4px;">Pengaturan</div>
            
                <a href="{{ route('branches.index') }}" class="menu-item {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '#C7AB6B' : 'transparent' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Master Data</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer" style="padding: 16px; border-top: 1px solid #04472d; background: #013220; flex-shrink: 0;">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" title="Keluar" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 10px; background: #fef2f2; border: none; cursor: pointer;">
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

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper" id="mainWrapper">

        {{-- NAVBAR ATAS --}}
        <header class="navbar-top">
            <div style="display: flex; align-items: center; gap: 14px;">
                <button type="button" id="sidebarToggle" style="background: #f8fafc; border: 1px solid #e8edf5; width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; cursor: pointer; color: #172033; font-size: 18px;">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <h1 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Dashboard Front Office</h1>
                    <p style="font-size: 11px; font-weight: 600; color: #778195; margin: 0;">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                @auth
                @php
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
                    <button type="button" class="btn-bell">
                        🔔
                        @if($unreadCount > 0)
                        <span class="badge">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <div class="dropdown-box">
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

                        <div style="max-height: 280px; overflow-y: auto; display: flex; flex-direction: column;">
                            @forelse($myNotifications as $notif)
                            <div class="notif-item">
                                <div class="notif-item-content">
                                    <strong>
                                        <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block; flex-shrink: 0;"></span>
                                        {{ $notif->title }}
                                    </strong>
                                    <p>{{ $notif->body }}</p>
                                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <form action="{{ route('frontoffice.notifications.read', $notif->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="notif-item-mark-btn" title="Tandai dibaca">
                                        ✓
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div style="padding: 20px; text-align: center; font-size: 12px; color: #94a3b8;">
                                Tidak ada notifikasi baru.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endauth

                <div style="width: 1px; height: 24px; background: #e8edf5; margin: 0 4px;"></div>
                <div style="display: flex; align-items: center;">
                    <img src="{{ asset('images/foto-perusahaan.jpg') }}" alt="Logo Perusahaan" style="width: 110px; height: 34px; object-fit: contain; display: block;">
                </div>
            </div>
        </header>

        {{-- KONTEN UTAMA --}}
        <main class="content-area">
            @yield('content')
        </main>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                // Jika dibuka di Layar HP / Tablet (lebar <= 992px)
                if (window.innerWidth <= 992) {
                    sidebar.classList.toggle('mobile-show');
                    mainWrapper.classList.toggle('mobile-shifted');
                } else {
                    // Jika di Laptop / Komputer, sidebar menyusut menjadi ikon saja (Gaya Manager)
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');
                }
            });
        }
    });
</script>
</body>

</html>