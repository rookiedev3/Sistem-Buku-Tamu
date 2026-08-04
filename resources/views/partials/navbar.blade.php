<nav class="navbar" style="background: #ffffff; border-bottom: 1px solid #e8edf5; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div>
        <h1 style="font-size: 18px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Dashboard</h1>
        <p style="font-size: 12px; font-weight: 600; color: #778195; margin: 0;">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <div style="display: flex; align-items: center; gap: 10px;">
        <button class="btn-icon" title="Notifikasi" style="width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e8edf5; background: #f8fafc; color: #5c6678; font-size: 15px; display: grid; place-items: center; cursor: pointer; transition: background 0.2s;">
            🔔
        </button>

        <button class="btn-primary-custom" style="background: #1463ff; color: #fff; border: none; border-radius: 10px; padding: 9px 16px; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 8px 20px rgba(20,99,255,.2); display: flex; align-items: center; gap: 6px; transition: background 0.2s;">
            <span>+ Tambah Tamu</span>
        </button>
    </div>

</nav>