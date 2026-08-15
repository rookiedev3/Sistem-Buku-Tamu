<div class="dashboard-split-wrapper mb-4">
    
    <div class="stats-left-grid">
        <a href="{{ route('owner.dashboard') }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
            <div class="stat-icon-wrap blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label-custom">Total Tamu Hari Ini</span>
                <h3 class="stat-number-custom">{{ $totalTamuHariIni ?? 0 }}</h3>
            </div>
        </a>

        <a href="{{ route('owner.dashboard', ['status' => 'Terjadwal']) }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
            <div class="stat-icon-wrap yellow">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label-custom">Terjadwal</span>
                <h3 class="stat-number-custom">{{ $terjadwalHariIni ?? 0 }}</h3>
            </div>
        </a>

        <a href="{{ route('owner.dashboard', ['status' => 'Selesai']) }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
            <div class="stat-icon-wrap green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label-custom">Pertemuan Selesai</span>
                <h3 class="stat-number-custom">{{ $pertemuanSelesai ?? 0 }}</h3>
            </div>
        </a>

        <a href="{{ route('owner.dashboard', ['lead_only' => 1]) }}#kunjungan-hari-ini" class="stat-box" style="text-decoration:none; color:inherit;">
            <div class="stat-icon-wrap purple">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            </div>
            <div class="stat-content">
                <span class="stat-label-custom">Menjadi Lead</span>
                <h3 class="stat-number-custom">+{{ $menjadiLeadHariIni ?? 0 }}</h3>
            </div>
        </a>
    </div>

    <div class="stats-right-stack">
        <a href="{{ route('products.laporan') }}" class="stat-box wide-box" style="text-decoration:none; color:inherit;">
            <div class="stat-icon-wrap teal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>
            </div>
            <div class="stat-content w-100">
                <span class="stat-label-custom">Produk Paling Sering Diminati</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">{{ $topProduct->name ?? '-' }}</h5>
                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">{{ $topProduct->total ?? 0 }} Permintaan</span>
                </div>
            </div>
        </a>

        <a href="{{ route('guest-categories.laporan') }}" class="stat-box wide-box" style="text-decoration:none; color:inherit;">
            <div class="stat-icon-wrap orange">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8.11 2.82"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            </div>
            <div class="stat-content w-100">
                <span class="stat-label-custom">Dominasi Kategori Tamu</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">{{ $topCategory->name ?? '-' }}</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">{{ $topCategoryPercentage ?? 0 }}% total</span>
                </div>
            </div>
        </a>
    </div>

</div>

<style>
/* Wrapper Utama Layout Split (Kiri & Kanan) */
.dashboard-split-wrapper {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
}

/* Sisi Kiri: Format Grid 2 Kolom x 2 Baris */
.stats-left-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

/* Sisi Kanan: Format Tumpuk Vertikal Ke Bawah */
.stats-right-stack {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.stat-box {
    background: #ffffff;
    border: 1px solid #e8edf5;
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 10px 30px rgba(31, 53, 97, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.stat-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(31,53,97,0.10);
}

.wide-box {
    flex-direction: row;
    align-items: center;
    gap: 16px;
}

.stat-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

/* Variasi Warna Kotak Ikon yang Lembut & Clean */
.stat-icon-wrap.blue { background: #edf4ff; color: #1463ff; }
.stat-icon-wrap.yellow { background: #fefce8; color: #ca8a04; }
.stat-icon-wrap.green { background: #e8f8f1; color: #21a86b; }
.stat-icon-wrap.purple { background: #f5f3ff; color: #7c3aed; }
.stat-icon-wrap.teal { background: #e0f2fe; color: #0284c7; }
.stat-icon-wrap.orange { background: #fff7ed; color: #c2410c; }

.stat-label-custom {
    font-size: 11px;
    font-weight: 700;
    color: #778195;
    display: block;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number-custom {
    font-size: 26px;
    font-weight: 900;
    color: #172033;
    margin: 0;
}

/* Responsif untuk Layar Tablet / HP */
@media(max-width: 992px) {
    .dashboard-split-wrapper {
        grid-template-columns: 1fr;
    }
}
</style>