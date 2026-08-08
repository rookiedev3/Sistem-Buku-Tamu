<div class="dashboard-split-wrapper mb-4">
    
    <div class="stats-left-grid">
        <div class="stat-box">
            <div class="stat-icon-wrap blue">👥</div>
            <div class="stat-content">
                <span class="stat-label-custom">Total Tamu Hari Ini</span>
                <h3 class="stat-number-custom">24</h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon-wrap yellow">⏳</div>
            <div class="stat-content">
                <span class="stat-label-custom">Sedang Menunggu</span>
                <h3 class="stat-number-custom">5</h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon-wrap green">🤝</div>
            <div class="stat-content">
                <span class="stat-label-custom">Sedang Bertemu</span>
                <h3 class="stat-number-custom">7</h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon-wrap purple">💼</div>
            <div class="stat-content">
                <span class="stat-label-custom">Menjadi Lead</span>
                <h3 class="stat-number-custom">+4</h3>
            </div>
        </div>
    </div>

    <div class="stats-right-stack">
        <div class="stat-box wide-box">
            <div class="stat-icon-wrap teal">🔥</div>
            <div class="stat-content w-100">
                <span class="stat-label-custom">Produk Paling Sering Diminati</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">Cloud Server Enterprise</h5>
                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">12 Permintaan</span>
                </div>
            </div>
        </div>

        <div class="stat-box wide-box">
            <div class="stat-icon-wrap orange">📊</div>
            <div class="stat-content w-100">
                <span class="stat-label-custom">Dominasi Kategori Tamu</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">Klien Korporat / Instansi</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">65% total</span>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
/* Wrapper Utama Layout Split (Kiri & Kanan) */
.dashboard-split-wrapper {
    display: grid;
    grid-template-columns: 1.2fr 1fr; /* Sisi kiri sedikit lebih lebar untuk 2x2 grid */
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
    font-size: 20px;
    flex-shrink: 0;
}

/* Variasi Warna Ikon & Background */
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
        grid-template-columns: 1fr; /* Berubah menjadi 1 kolom ke bawah di layar kecil */
    }
}
</style>