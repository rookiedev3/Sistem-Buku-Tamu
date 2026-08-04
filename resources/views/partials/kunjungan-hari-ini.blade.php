<div class="card mb-4" style="background:#fff; border:1px solid #e8edf5; border-radius:24px; box-shadow:0 18px 50px rgba(31,53,97,.12); padding:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <div>
            <h3 style="font-size:18px; font-weight:800; margin:0 0 4px; color:#172033;">Kunjungan Hari Ini</h3>
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
                <tr style="border-bottom:1px solid #e8edf5; color:#778195; font-weight:700;">
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