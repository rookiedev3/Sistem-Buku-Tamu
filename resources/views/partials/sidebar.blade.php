<aside class="sidebar" style="width: 280px; background: #ffffff; border-right: 1px solid #e8edf5; display: flex; flex-direction: column; justify-content: space-between; min-height: 100vh; padding: 24px; box-sizing: border-box; flex: none;">
    
    <!-- Bagian Atas: Logo & Navigasi -->
    <div>
        <!-- Logo Brand -->
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #1463ff; color: #fff; display: grid; place-items: center; font-weight: 900; font-size: 18px; box-shadow: 0 8px 20px rgba(20,99,255,.25); flex: none;">IT</div>
            <div>
                <h4 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">IT Solution</h4>
            </div>
        </div>

        <!-- Box Workspace -->
        <div style="background: #f8fafc; border: 1px solid #e8edf5; border-radius: 16px; padding: 12px 14px; margin-bottom: 24px;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; display: block; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Workspace</span>
            <strong style="font-size: 13px; font-weight: 800; color: #172033;">Guest Management</strong>
        </div>

        <!-- Menu Navigasi -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- Kategori: UTAMA -->
            <div>
                <span style="font-size: 11px; font-weight: 800; color: #9aa3b2; display: block; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px; padding-left: 4px;">Utama</span>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                     <li>
                        <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('/') || request()->is('dashboard') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('/') || request()->is('dashboard') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('/') || request()->is('dashboard') ? '800' : '700' }}; text-decoration: none;">
                            <span style="font-size: 15px;">🏠</span> Dashboard
                        </a>
                    </li>
                   <li>
                        <a href="{{ url('/check-in') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('check-in*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('check-in*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('check-in*') ? '800' : '700' }}; text-decoration: none;">
                            <span style="font-size: 15px;">➕</span> Check-in Tamu
                        </a>
                    </li>

                   <li>
    <a href="{{ url('/kunjungan') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('kunjungan*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('kunjungan*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('kunjungan*') ? '800' : '700' }}; text-decoration: none;">
        <span style="font-size: 15px;">📋</span> Daftar Kunjungan
    </a>
</li>
                   <li>
                        <a href="{{ url('/database-tamu') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('database-tamu*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('database-tamu*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('database-tamu*') ? '800' : '700' }}; text-decoration: none;">
                            <span style="font-size: 15px;">🗂️</span> Database Tamu
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kategori: PENJUALAN -->
            <div>
                <span style="font-size: 11px; font-weight: 800; color: #9aa3b2; display: block; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px; padding-left: 4px;">Penjualan</span>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                    <li>
                        <a href="{{ url('/leads') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('leads*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('leads*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('leads*') ? '800' : '700' }}; text-decoration: none;">
                            <span style="font-size: 15px;">📈</span> Lead & Follow Up
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/laporan') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('laporan*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('laporan*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('laporan*') ? '800' : '700' }}; text-decoration: none;">
                            <span style="font-size: 15px;">📊</span> Laporan
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('branches.index') }}">MENU BRANCHESS</a>
            <!-- Kategori: PENGATURAN -->
            <div>
                <span style="font-size: 11px; font-weight: 800; color: #9aa3b2; display: block; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px; padding-left: 4px;">Pengaturan</span>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                    <li>
                        <a href="{{ url('/master-data') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('master-data*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('master-data*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('master-data*') ? '800' : '700' }}; text-decoration: none;">
                            <span style="font-size: 15px;">⚙️</span> Master Data
                        </a>
                    </li>
                 <li>
                <a href="{{ url('/pengguna') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; background: {{ request()->is('pengguna*') ? '#eef4ff' : 'transparent' }}; color: {{ request()->is('pengguna*') ? '#1463ff' : '#5c6678' }}; font-size: 13px; font-weight: {{ request()->is('pengguna*') ? '800' : '700' }}; text-decoration: none;">
                    <span style="font-size: 15px;">👤</span> Pengguna
                </a>
            </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Bagian Bawah: Profil Pengguna & Tombol Keluar Laravel -->
    <div style="border-top: 1px solid #e8edf5; padding-top: 20px; margin-top: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #eef4ff; color: #1463ff; display: grid; place-items: center; font-weight: 800; font-size: 12px; flex: none;">MN</div>
                <div>
                    <strong style="font-size: 13px; color: #172033; display: block; font-weight: 800;">Mana</strong>
                    <span style="font-size: 11px; color: #778195;">Owner</span>
                </div>
            </div>
        </div>

        <!-- Tombol Keluar (Logout) Sesuai Route Laravel -->
        <form action="{{ route('logout') }}" method="get" style="margin: 0;">
            @csrf
            <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border-radius: 10px; background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; font-size: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                🚪 <span>Keluar Sistem</span>
            </button>
        </form>
    </div>

</aside>