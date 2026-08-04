<div class="card" style="background:#fff; border:1px solid #e8edf5; border-radius:24px; box-shadow:0 18px 50px rgba(31,53,97,.12); padding:24px; height:100%;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
        <div>
            <h3 style="font-size:18px; font-weight:800; margin:0 0 4px; color:#172033;">Mockup Form Check-in Tamu</h3>
            <p style="color:#778195; font-size:13px; margin:0;">Form sederhana untuk tablet front office atau QR publik.</p>
        </div>
        <a href="#" style="font-size:12px; font-weight:700; color:#1463ff; text-decoration:none;">Mode publik</a>
    </div>

    <form action="#" method="POST" style="display:flex; flex-direction:column; gap:14px;">
        <!-- Baris 1: Nama Lengkap & Nomor WhatsApp -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">Nama lengkap</label>
                <input type="text" placeholder="Masukkan nama tamu" style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">Nomor WhatsApp</label>
                <input type="text" placeholder="08xxxxxxxxxx" style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; box-sizing:border-box;">
            </div>
        </div>

        <!-- Baris 2: Instansi / Perusahaan & Keperluan -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">Instansi / Perusahaan</label>
                <input type="text" placeholder="Nama perusahaan" style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">Keperluan</label>
                <select style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; cursor:pointer; box-sizing:border-box;">
                    <option value="">Pilih keperluan ▾</option>
                    <option value="konsultasi">Konsultasi bisnis</option>
                    <option value="meeting">Meeting</option>
                </select>
            </div>
        </div>

        <!-- Produk yang diminati (Chips) -->
        <div>
            <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:8px;">Produk yang diminati</label>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                <span style="padding:8px 14px; border-radius:10px; border:1px solid #cfe0ff; background:#eef4ff; color:#1463ff; font-size:12px; font-weight:800; cursor:pointer;">Website</span>
                <span style="padding:8px 14px; border-radius:10px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer;">Sistem POS</span>
                <span style="padding:8px 14px; border-radius:10px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer;">SEO</span>
                <span style="padding:8px 14px; border-radius:10px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer;">Digital Marketing</span>
                <span style="padding:8px 14px; border-radius:10px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer;">Custom System</span>
            </div>
        </div>

        <!-- Baris 3: PIC yang ingin ditemui & Sumber informasi (Sejajar) -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">PIC yang ingin ditemui</label>
                <select style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; cursor:pointer; box-sizing:border-box;">
                    <option value="">Pilih PIC ▾</option>
                    <option value="budi">Budi (IT)</option>
                    <option value="rina">Rina (Sales)</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">Sumber informasi</label>
                <select style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; cursor:pointer; box-sizing:border-box;">
                    <option value="">Google / Instagram / Referral ▾</option>
                    <option value="google">Google Search</option>
                    <option value="instagram">Instagram</option>
                </select>
            </div>
        </div>

        <!-- Baris 4: Catatan kebutuhan (Full Width) -->
        <div>
            <label style="display:block; font-size:12px; font-weight:700; color:#172033; margin-bottom:6px;">Catatan kebutuhan</label>
            <input type="text" placeholder="Tuliskan kebutuhan singkat..." style="width:100%; border:1px solid #e8edf5; border-radius:10px; padding:11px 14px; font-size:12px; background:#fbfcfe; color:#8a94a4; outline:none; box-sizing:border-box;">
        </div>

        <!-- Tombol Aksi (Bersihkan & Check-in Sekarang) -->
        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:8px;">
            <button type="reset" style="background:#f0f3f8; color:#5e697c; border:none; border-radius:12px; padding:11px 20px; font-size:13px; font-weight:800; cursor:pointer;">
                Bersihkan
            </button>
            <button type="submit" style="background:#1463ff; color:#fff; border:none; border-radius:12px; padding:11px 20px; font-size:13px; font-weight:800; cursor:pointer; box-shadow:0 10px 22px rgba(20,99,255,.2);">
                Check-in Sekarang
            </button>
        </div>
    </form>
</div>