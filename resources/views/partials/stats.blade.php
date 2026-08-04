<div class="stats-grid-custom mb-4">
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

<style>
/* Styling khusus agar persis dengan desain CSS perusahaan */
.stats-grid-custom {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
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

.stat-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 20px;
    margin-bottom: 16px;
}

/* Variasi Warna Ikon Background ala Perusahaan */
.stat-icon-wrap.blue { background: #edf4ff; color: #1463ff; }
.stat-icon-wrap.yellow { background: #fefce8; color: #ca8a04; }
.stat-icon-wrap.green { background: #e8f8f1; color: #21a86b; }
.stat-icon-wrap.purple { background: #f5f3ff; color: #7c3aed; }

.stat-label-custom {
    font-size: 12px;
    font-weight: 700;
    color: #778195;
    display: block;
    margin-bottom: 6px;
}

.stat-number-custom {
    font-size: 26px;
    font-weight: 900;
    color: #172033;
    margin: 0;
}

/* Responsif untuk layar kecil/tablet */
@media(max-width: 992px) {
    .stats-grid-custom {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media(max-width: 576px) {
    .stats-grid-custom {
        grid-template-columns: 1fr;
    }
}
</style>