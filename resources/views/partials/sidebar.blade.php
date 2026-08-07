<aside class="sidebar" style="width: 260px; background: #ffffff; border-right: 1px solid #e8edf5; display: flex; flex-direction: column; justify-content: space-between; position: fixed; height: 100vh; z-index: 100;">
    
    <!-- Bagian Atas: Brand & Menu yang bisa di-scroll -->
    <div style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
        
        <div class="sidebar-brand" style="padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #e8edf5; flex-shrink: 0;">
            <div class="sidebar-brand-icon" style="width: 36px; height: 36px; background: #006B3F; color: #ffffff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px;">OW</div>
            <div>
                <div style="font-size: 15px; font-weight: 800; color: #172033;">Pimpinan / Owner</div>
                <div style="font-size: 11px; color: #778195;">Executive Dashboard</div>
            </div>
        </div>

        <!-- Area Menu dengan kemampuan Scroll (overflow-y: auto) -->
        <div class="sidebar-menu" style="padding: 16px; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; flex-grow: 1;">
            
            <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding: 6px 12px 2px;">Utama</div>
            
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('/') || request()->is('dashboard') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('/') || request()->is('dashboard') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('/') || request()->is('dashboard') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Dashboard Utama</span>
            </a>

            <a href="{{ url('/check-in') }}" class="menu-item {{ request()->is('check-in*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('check-in*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('check-in*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('check-in*') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                <span>Check-in Tamu</span>
            </a>

            <a href="{{ url('/kunjungan') }}" class="menu-item {{ request()->is('kunjungan*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('kunjungan*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('kunjungan*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('kunjungan*') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>Daftar Kunjungan</span>
            </a>

            <a href="{{ url('/database-tamu') }}" class="menu-item {{ request()->is('database-tamu*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('database-tamu*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('database-tamu*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('database-tamu*') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                <span>Database Tamu</span>
            </a>

            <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px 2px; margin-top: 4px;">Penjualan</div>

            <a href="{{ url('/leads') }}" class="menu-item {{ request()->is('leads*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('leads*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('leads*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('leads*') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span>Lead & Follow Up</span>
            </a>

            <a href="{{ url('/laporan') }}" class="menu-item {{ request()->is('laporan*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('laporan*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('laporan*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('laporan*') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span>Laporan</span>
            </a>

            {{-- <a href="{{ route('branches.index') }}" class="menu-item {{ request()->routeIs('branches.index') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->routeIs('branches.index') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->routeIs('branches.index') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->routeIs('branches.index') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Menu Branches</span>
            </a> --}}

            <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px 2px; margin-top: 4px;">Pengaturan</div>

            {{-- <a href="{{ url('/master-data') }}" class="menu-item {{ request()->is('master-data*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('master-data*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('master-data*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('master-data*') ? '#e6f0eb' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Master Data</span>
            </a> --}}
            
            <a href="{{ route('branches.index') }}" class="menu-item {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '#006B3F' : '#64748b' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '#e6f0eb' : 'transparent' }};">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    <span>Master Data</span>
</a>

        </div>
    </div>

    <!-- Bagian Bawah: Tombol Keluar (Tetap Terkunci di Bawah) -->
    <div style="padding: 16px; border-top: 1px solid #e8edf5; background: #ffffff; flex-shrink: 0;">
        <form action="{{ route('logout') }}" method="post" style="margin: 0;">
            @csrf
            <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 10px; background: #fef2f2; border: none; cursor: pointer;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Keluar (Logout)</span>
            </button>
        </form>
    </div>
</aside>