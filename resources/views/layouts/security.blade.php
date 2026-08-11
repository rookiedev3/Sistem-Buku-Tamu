<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Security - Buku Tamu Digital</title>
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

        /* --- MENYEMBUNYIKAN GARIS SCROLLBAR DI SIDEBAR --- */
        .sidebar::-webkit-scrollbar,
        .sidebar-menu::-webkit-scrollbar {
            display: none !important; /* Untuk Chrome, Safari, dan Edge */
            width: 0 !important;
        }

        .sidebar,
        .sidebar-menu {
            -ms-overflow-style: none !important; /* Untuk Internet Explorer dan Edge lama */
            scrollbar-width: none !important; /* Untuk Firefox */
        }

        /* --- SIDEBAR STYLE (ANTI JEDAG-JEDUG & MULUS) --- */
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
            will-change: width;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- SIDEBAR BRAND & LOGO --- */
        .sidebar-brand {
            padding: 20px;
            height: 80px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #04472d;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #04472d;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.1s ease, visibility 0.1s ease;
        }

        /* Kondisi saat Sidebar Dikecilkan di Laptop */
        .sidebar.collapsed {
            width: 80px !important;
        }
        .sidebar.collapsed .sidebar-brand {
            justify-content: center !important;
            padding: 20px 0 !important;
        }
        .sidebar.collapsed .sidebar-brand-icon {
            margin: 0 auto;
        }
        .sidebar.collapsed .sidebar-brand-text {
            opacity: 0 !important;
            visibility: hidden !important;
            width: 0 !important;
            display: none !important;
        }
        .sidebar.collapsed .menu-category,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed form button span {
            display: none !important;
            opacity: 0 !important;
        }
        .sidebar.collapsed .sidebar-menu {
            padding: 16px 10px !important;
            align-items: center !important;
        }
        .sidebar.collapsed .menu-item {
            justify-content: center !important;
            padding: 12px !important;
            width: 60px;
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
            will-change: margin-left, width;
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

        /* --- MEDIA QUERY KHUSUS HP / TABLET (Layar di bawah 992px) --- */
        @media(max-width: 992px) {
            .sidebar {
                width: 260px !important;
                transform: translateX(-100%); /* Tertutup rapi di luar layar sebelah kiri */
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            /* Saat sidebar dibuka di HP, masukkan ke layar */
            .sidebar.mobile-show {
                transform: translateX(0) !important;
            }
            /* Default di HP: wrapper memenuhi lebar layar penuh */
            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            /* Saat sidebar dibuka di HP: wrapper ikut bergeser */
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

    {{-- SKRIP INLINE UTAMA: Mencegah efek jedag-jedug/pantulan saat berpindah menu --}}
    <script>
        if (window.innerWidth > 992 && localStorage.getItem('sidebar_collapsed') === 'true') {
            document.write('<style>.sidebar{width:80px !important;}.sidebar .sidebar-brand{justify-content:center !important;padding:20px 0 !important;}.sidebar .sidebar-brand-icon{margin:0 auto;}.sidebar .sidebar-brand-text{opacity:0 !important;visibility:hidden !important;width:0 !important;display:none !important;}.sidebar .menu-category,.sidebar .menu-item span,.sidebar form button span{display:none !important;opacity:0 !important;}.sidebar .sidebar-menu{padding:16px 10px !important;align-items:center !important;}.sidebar .menu-item{justify-content:center !important;padding:12px !important;width:60px;}.sidebar .sidebar-footer{padding:16px 10px !important;}.main-wrapper{margin-left:80px !important;width:calc(100% - 80px) !important;}</style>');
        }
    </script>
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
            
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('images/logo-perusahaan.jpg') }}" alt="Logo Perusahaan" style="width: 100%; height: 100%; object-fit: contain;">
                </div> 

                <div class="sidebar-brand-text">
                    <span style="font-size: 14px; font-weight: 800; color: #ffffff; text-transform: capitalize; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ Auth::user()->name ?? 'Portal Security' }}
                    </span>
                    <span style="font-size: 11px; font-weight: 600; color: #C7AB6B; text-transform: uppercase; letter-spacing: 0.5px;">
                        Security / Penjaga
                    </span>
                </div>
            </div>

            <div class="sidebar-menu" style="padding: 16px; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; flex-grow: 1;">
                <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 6px 12px 2px;">Utama</div>
                
                <a href="#" class="menu-item {{ request()->is('security/dashboard*') ? 'active' : '' }}" title="Daftar Tamu Hari Ini" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('security/dashboard*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('security/dashboard*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('security/dashboard*') ? '#C7AB6B' : 'transparent' }}; pointer-events: none; cursor: not-allowed;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span>Daftar Tamu Hari Ini</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer" style="padding: 16px; border-top: 1px solid #04472d; background: #013220; flex-shrink: 0;">
            <form action="{{ route('logout') }}" method="post" style="margin: 0;">
                @csrf
                <button type="submit" title="Keluar" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 10px; background: #fef2f2; border: none; cursor: pointer;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
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
                    <h1 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Portal Security</h1>
                    <p style="font-size: 11px; font-weight: 600; color: #778195; margin: 0;">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
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
                if (window.innerWidth <= 992) {
                    // Mode Mobile (HP)
                    sidebar.classList.toggle('mobile-show');
                    mainWrapper.classList.toggle('mobile-shifted');
                } else {
                    // Mode Laptop / Desktop (Toggle Collapse)
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');

                    // Simpan status terbaru ke localStorage
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebar_collapsed', isCollapsed);
                }
            });
        }
    });
</script>
</body>

</html>