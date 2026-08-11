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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            overflow-x: hidden;
        }

        /* --- NAVBAR & TOMBOL GARIS 3 (POSISI AMAN DI ATAS) --- */
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
            z-index: 1000 !important;
            box-sizing: border-box;
        }

        #sidebarToggle {
            position: relative;
            z-index: 99999 !important;
            pointer-events: auto !important;
            cursor: pointer;
            background: #f8fafc;
            border: 1px solid #e8edf5;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: #172033;
            font-size: 18px;
        }

        /* --- WRAPPER UTAMA --- */
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

        .content-area {
            padding: 32px;
            flex-grow: 1;
            background-color: #f4f7fc;
        }

        /* --- NOTIFICATION DROPDOWN STYLE --- */
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

        /* --- SIDEBAR COLLAPSE & RESPONSIVE STYLE --- */
        .sidebar {
            width: 260px;
            background: #013220;
            border-right: 1px solid #04472d;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100vh;
            z-index: 900;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Menghilangkan garis scroll putih di sidebar */
        .sidebar::-webkit-scrollbar,
        .sidebar-menu::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
        }
        .sidebar, .sidebar-menu {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        /* Saat Sidebar Dikecilkan (Collapsed) di Laptop */
        .sidebar.collapsed {
            width: 80px !important;
        }
        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .menu-category,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed form button span {
            display: none !important;
            opacity: 0 !important;
        }
        .sidebar.collapsed .sidebar-brand {
            justify-content: center !important;
            padding: 20px 0 !important;
        }
        .sidebar.collapsed .sidebar-brand-icon {
            margin: 0 auto;
        }
        .sidebar.collapsed .sidebar-menu {
            padding: 16px 10px !important;
            align-items: center !important;
        }
        .sidebar.collapsed .menu-item {
            justify-content: center !important;
            padding: 12px !important;
            width: 60px !important;
            height: 48px !important;
            margin: 0 auto 4px auto !important;
        }
        .sidebar.collapsed .sidebar-footer {
            padding: 16px 10px !important;
        }

        /* --- MEDIA QUERY HP / TABLET --- */
        @media(max-width: 992px) {
            .sidebar {
                width: 260px !important;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            .navbar {
                padding: 0 16px !important;
            }
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-wrapper" id="mainWrapper">
        
        @include('partials.navbar')

        <main class="content-area">
            @yield('content')
        </main>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');

        // Tombol garis 3 / toggle sidebar handler (Event Delegation)
        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('#sidebarToggle');
            if (!toggleBtn) return; 

            e.preventDefault();
            e.stopPropagation();

            if (window.innerWidth <= 992) {
                // Mode HP: Geser sidebar masuk/keluar layar
                if (sidebar) sidebar.classList.toggle('mobile-show');
                if (mainWrapper) mainWrapper.classList.toggle('mobile-shifted');
            } else {
                // Mode Laptop: Toggle membesar dan mengecil secara dinamis
                if (sidebar) sidebar.classList.toggle('collapsed');
                if (mainWrapper) mainWrapper.classList.expanded ? mainWrapper.classList.remove('expanded') : mainWrapper.classList.add('expanded');
            }
        });
    });
</script>
</body>

</html>