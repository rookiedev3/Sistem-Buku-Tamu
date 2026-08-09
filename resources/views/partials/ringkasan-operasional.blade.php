@extends('layouts.app') {{-- Sesuaikan dengan nama file layout owner Anda --}}

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <div id="welcomeBanner" class="card border-0 rounded-4 p-4 shadow-sm position-relative" style="background-color: #013220; color: white;">
        <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" aria-label="Close"></button>
        <div class="d-flex justify-content-between align-items-center pe-4">
            <div>
                <h4 class="fw-bold mb-1 text-white">Selamat datang, {{ Auth::user()->name ?? 'Pimpinan / Owner' }} 👋</h4>
                <p class="mb-0 text-white-50 fs-6">Berikut adalah ringkasan aktivitas buku tamu dan kunjungan kantor hari ini.</p>
            </div>
        </div>
    </div>

    <div>
        <h5 class="fw-bold mb-3" style="color: #172033; font-size: 16px;">Ringkasan Operasional & Wawasan</h5>
        
        <div class="dashboard-split-wrapper">
            
            <div class="stats-left-grid">
                <div class="stat-box">
                    <div class="stat-icon-wrap blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label-custom">Total Tamu Hari Ini</span>
                        <h3 class="stat-number-custom">24</h3>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon-wrap yellow">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label-custom">Sedang Menunggu</span>
                        <h3 class="stat-number-custom">5</h3>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon-wrap green">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label-custom">Sedang Bertemu</span>
                        <h3 class="stat-number-custom">7</h3>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon-wrap purple">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label-custom">Menjadi Lead</span>
                        <h3 class="stat-number-custom">+4</h3>
                    </div>
                </div>
            </div>

            <div class="stats-right-stack">
                <div class="stat-box wide-box">
                    <div class="stat-icon-wrap teal">
                        <i class="bi bi-fire"></i>
                    </div>
                    <div class="stat-content w-100">
                        <span class="stat-label-custom">Produk Paling Sering Diminati</span>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h5 class="fw-bold text-dark m-0" style="font-size: 14px;">Cloud Server Enterprise</h5>
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">12 Permintaan</span>
                        </div>
                    </div>
                </div>

                <div class="stat-box wide-box">
                    <div class="stat-icon-wrap orange">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div class="stat-content w-100">
                        <span class="stat-label-custom">Dominasi Kategori Tamu</span>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h5 class="fw-bold text-dark m-0" style="font-size: 14px;">Klien Korporat / Instansi</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill" style="font-size: 11px; font-weight: 700;">65% total</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row g-4 align-items-stretch">
        
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm h-100" style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 10px 30px rgba(31,53,97,0.05); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; margin: 0 0 4px; color: #172033;">Analisis Pelayanan</h3>
                    <p style="color: #778195; font-size: 12px; margin: 0 0 20px;">Kondisi performa pelayanan tamu hari ini.</p>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: #edf4ff; color: #1463ff; display: grid; place-items: center; font-weight: 900; font-size: 15px; flex: none;">10</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: #172033; margin: 0 0 2px;">Rata-rata waktu tunggu</h4>
                                <p style="font-size: 12px; color: #778195; margin: 0; line-height: 1.4;">10 menit sebelum bertemu PIC.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: #e8f8f1; color: #21a86b; display: grid; place-items: center; font-weight: 900; font-size: 15px; flex: none;">82</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: #172033; margin: 0 0 2px;">Tingkat pelayanan</h4>
                                <p style="font-size: 12px; color: #778195; margin: 0; line-height: 1.4;">82% tamu dilayani di bawah SLA.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: #f5f3ff; color: #7c3aed; display: grid; place-items: center; font-weight: 900; font-size: 15px; flex: none;">37</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: #172033; margin: 0 0 2px;">Conversion rate</h4>
                                <p style="font-size: 12px; color: #778195; margin: 0; line-height: 1.4;">37% kunjungan potensial menjadi lead.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm h-100" style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 10px 30px rgba(31,53,97,0.05); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold m-0" style="color: #172033; font-size: 16px;">Aktivitas Terbaru ⚡</h3>
                        <a href="#" style="font-size: 12px; color: #013220; font-weight: 700; text-decoration: none;">Lihat Semua</a>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div class="d-flex align-items-center gap-3 p-2.5 rounded-3" style="background: #f8fafc;">
                            <div style="width: 38px; height: 38px; background: #e8f8f1; color: #21a86b; border-radius: 10px; display: grid; place-items: center; font-weight: bold; flex-shrink: 0; font-size: 12px;">BP</div>
                            <div class="flex-grow-1" style="overflow: hidden;">
                                <h6 class="m-0 text-dark fw-bold" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Budi Prasetyo (PT Telkom)</h6>
                                <span class="text-muted" style="font-size: 11px;">Check-in untuk bertemu Bapak Direktur</span>
                            </div>
                            <span class="text-muted" style="font-size: 10px; white-space: nowrap;">10:42</span>
                        </div>

                        <div class="d-flex align-items-center gap-3 p-2.5 rounded-3" style="background: #f8fafc;">
                            <div style="width: 38px; height: 38px; background: #edf4ff; color: #1463ff; border-radius: 10px; display: grid; place-items: center; font-weight: bold; flex-shrink: 0; font-size: 12px;">SN</div>
                            <div class="flex-grow-1" style="overflow: hidden;">
                                <h6 class="m-0 text-dark fw-bold" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Siti Nurhaliza (Universitas GМM)</h6>
                                <span class="text-muted" style="font-size: 11px;">Status kunjungan diubah: Sedang Bertemu</span>
                            </div>
                            <span class="text-muted" style="font-size: 10px; white-space: nowrap;">10:15</span>
                        </div>

                        <div class="d-flex align-items-center gap-3 p-2.5 rounded-3" style="background: #f8fafc;">
                            <div style="width: 38px; height: 38px; background: #f5f3ff; color: #7c3aed; border-radius: 10px; display: grid; place-items: center; font-weight: bold; flex-shrink: 0; font-size: 12px;">RA</div>
                            <div class="flex-grow-1" style="overflow: hidden;">
                                <h6 class="m-0 text-dark fw-bold" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Rian Aditiya (Mandiri Corp)</h6>
                                <span class="text-muted" style="font-size: 11px;">Berhasil dikonversi menjadi Lead Penjualan</span>
                            </div>
                            <span class="text-muted" style="font-size: 10px; white-space: nowrap;">09:50</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card mb-4 border-0 rounded-4 p-4 shadow-sm" style="background:#fff; border:1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.05);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <div>
                <h3 style="font-size:18px; font-weight:800; margin:0 0 4px; color:#172033;">Kunjungan Hari Ini 📋</h3>
                <p style="color:#778195; font-size:13px; margin:0;">Daftar tamu yang terdaftar dan status pertemuan hari ini.</p>
            </div>
            
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <select style="border:1px solid #e8edf5; border-radius:10px; padding:10px 14px; font-size:12px; background:#fbfcfe; color:#4a5568; outline:none; cursor:pointer;">
                    <option value="">Semua Status</option>
                    <option value="bertemu">Sedang Bertemu</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="selesai">Selesai</option>
                </select>

                <select style="border:1px solid #e8edf5; border-radius:10px; padding:10px 14px; font-size:12px; background:#fbfcfe; color:#4a5568; outline:none; cursor:pointer;">
                    <option value="">Semua PIC</option>
                    <option value="budi">Budi (IT)</option>
                    <option value="rina">Rina (Sales)</option>
                </select>

                <input type="text" placeholder="Cari..." style="border:1px solid #e8edf5; border-radius:10px; padding:10px 14px; font-size:12px; width:160px; background:#fbfcfe; outline:none;">
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left; font-size:13px;">
                <thead>
                    <tr style="border-bottom:1px solid #e8edf5; color:#778195; font-weight:700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding:12px 10px;">Nama Tamu</th>
                        <th style="padding:12px 10px;">Instansi</th>
                        <th style="padding:12px 10px;">Keperluan</th>
                        <th style="padding:12px 10px;">PIC</th>
                        <th style="padding:12px 10px;">Jam</th>
                        <th style="padding:12px 10px;">Status</th>
                    </tr>
                </thead>
                <tbody style="color:#172033; font-weight:600;">
                    <tr style="border-bottom:1px solid #f7faff;">
                        <td style="padding:14px 10px;">Ahmad Fauzan</td>
                        <td style="padding:14px 10px;">Aqiqah Berkah</td>
                        <td style="padding:14px 10px;">Konsultasi Bisnis</td>
                        <td style="padding:14px 10px;">Budi (IT)</td>
                        <td style="padding:14px 10px; color:#778195; font-weight:normal; font-size:12px;">09:30 - 10:15</td>
                        <td style="padding:14px 10px;"><span style="background:#e8f8f1; color:#21a86b; padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700;">Sedang Bertemu</span></td>
                    </tr>
                    <tr style="border-bottom:1px solid #f7faff;">
                        <td style="padding:14px 10px;">Siti Aminah</td>
                        <td style="padding:14px 10px;">CV Maju Jaya</td>
                        <td style="padding:14px 10px;">Demo Sistem POS</td>
                        <td style="padding:14px 10px;">Rina (Sales)</td>
                        <td style="padding:14px 10px; color:#778195; font-weight:normal; font-size:12px;">10:00 - Selesai</td>
                        <td style="padding:14px 10px;"><span style="background:#fefce8; color:#ca8a04; padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700;">Menunggu</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
/* CSS Styling khusus untuk Split Layout Dashboard */
.dashboard-split-wrapper {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
}

.stats-left-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.stats-right-stack {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.stat-box {
    background: #ffffff;
    border: 1px solid #e8edf5;
    border-radius: 20px;
    padding: 20px;
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
    padding: 18px 20px;
}

.stat-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 18px;
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
    font-size: 24px;
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
@endsection