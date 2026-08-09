<aside class="sidebar" style="width: 260px; background: #013220; border-right: 1px solid #04472d; display: flex; flex-direction: column; justify-content: space-between; position: fixed; height: 100vh; z-index: 100;">
    
    <div style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
        
        <div class="sidebar-brand" style="padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #04472d; flex-shrink: 0;">
            
            <div class="sidebar-brand-icon" style="width: 40px; height: 40px; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #ffffff; border: 1px solid #04472d; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex-shrink: 0;">
                <img src="{{ asset('images/logo-perusahaan.jpg') }}" alt="Logo IT Solution" style="width: 100%; height: 100%; object-fit: contain;">
            </div> 

            <div class="sidebar-brand-text" style="display: flex; flex-direction: column; overflow: hidden;">
                <span style="font-size: 14px; font-weight: 800; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->name ?? 'Nama Pimpinan' }}
                </span>
                <span style="font-size: 11px; font-weight: 600; color: #C7AB6B; text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ Auth::user()->role ?? 'Manager / Owner' }}
                </span>
            </div>

        </div>

        <div class="sidebar-menu" style="padding: 16px; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; flex-grow: 1;">
            
            <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 6px 12px 2px;">Utama</div>
            
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('/') || request()->is('dashboard') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('/') || request()->is('dashboard') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('/') || request()->is('dashboard') ? '#C7AB6B' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Dashboard Utama</span>
            </a>

            <a href="{{ route('owner.kunjungan') }}" class="menu-item {{ request()->is('kunjungan*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('kunjungan*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('kunjungan*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('kunjungan*') ? '#C7AB6B' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>Daftar Kunjungan</span>
            </a>

            <a href="{{ url('/database-tamu') }}" class="menu-item {{ request()->is('database-tamu*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('database-tamu*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('database-tamu*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('database-tamu*') ? '#C7AB6B' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                <span>Database Tamu</span>
            </a>

            <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px 2px; margin-top: 4px;">Penjualan</div>

            <a href="{{ url('/leads') }}" class="menu-item {{ request()->is('leads*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('leads*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('leads*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('leads*') ? '#C7AB6B' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span>Lead & Follow Up</span>
            </a>

            <a href="{{ url('/laporan.index') }}" class="menu-item {{ request()->is('laporan.index*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('laporan.index*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('laporan.index*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('laporan.index*') ? '#C7AB6B' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span>Laporan</span>
            </a>

            <div class="menu-category" style="font-size: 10px; font-weight: 700; color: #8fa394; text-transform: uppercase; letter-spacing: 1px; padding: 8px 12px 2px; margin-top: 4px;">Pengaturan</div>
            
            <a href="{{ route('branches.index') }}" class="menu-item {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '#013220' : '#d1d5db' }}; text-decoration: none; font-size: 13px; font-weight: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '700' : '600' }}; border-radius: 10px; background: {{ request()->is('branches*', 'products*', 'lead-sources*', 'visit-purposes*', 'guest-categories*') ? '#C7AB6B' : 'transparent' }};">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Master Data</span>
            </a>

        </div>
    </div>

    <div style="padding: 16px; border-top: 1px solid #04472d; background: #013220; flex-shrink: 0;">
        <form action="{{ route('logout') }}" method="post" style="margin: 0;">
            @csrf
            <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 10px; background: #fef2f2; border: none; cursor: pointer;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Keluar (Logout)</span>
            </button>
        </form>
    </div>
</aside>