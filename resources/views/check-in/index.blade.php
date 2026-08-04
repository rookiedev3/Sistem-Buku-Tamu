@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h1 style="font-size: 22px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Check-in Tamu Baru</h1>
    <p style="color: #778195; font-size: 13px; margin: 0;">Silakan isi formulir di bawah ini untuk mencatat kedatangan tamu secara digital.</p>
</div>

<div class="card" style="background:#fff; border:1px solid #e8edf5; border-radius:24px; box-shadow:0 18px 50px rgba(31,53,97,.12); padding:30px; max-width: 900px;">
    
    <form action="{{ route('visit.checkin') }}" method="POST" style="display:flex; flex-direction:column; gap:20px;">
        @csrf

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Nama lengkap <span style="color:#e5484d;">*</span></label>
                <input type="text" name="name" placeholder="Contoh: Ahmad Fauzan" required style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Nomor WhatsApp <span style="color:#e5484d;">*</span></label>
                <input type="text" name="phone" placeholder="Contoh: 081234567890" required style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; box-sizing:border-box;">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Instansi / Perusahaan</label>
                <input type="text" name="company_name" placeholder="Contoh: PT Maju Jaya" style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Jabatan</label>
                <input type="text" name="position" placeholder="Contoh: Owner / Manager" style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; box-sizing:border-box;">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Jenis kunjungan</label>
                <select name="guest_category_id" style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; cursor:pointer; box-sizing:border-box;">
                    <option value="1">Prospek</option>
                    <option value="2">Klien</option>
                    <option value="3">Vendor</option>
                    <option value="4">Pelamar</option>
                    <option value="5">Mitra</option>
                    <option value="6">Umum</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">PIC yang ingin ditemui</label>
                <select name="pic_id" style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; cursor:pointer; box-sizing:border-box;">
                    <option value="">-- Belum Menentukan --</option>
                    <option value="1">Budi (IT Support)</option>
                    <option value="2">Rina (Sales & Marketing)</option>
                    <option value="3">Andi (Project Manager)</option>
                </select>
            </div>
        </div>

       <div>
    <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Produk yang diminati</label>
    <div style="display:flex; flex-wrap:wrap; gap:10px;">
        
        <label class="product-chip" style="padding:10px 14px; border-radius:11px; border:1px solid #cfe0ff; background:#eef4ff; color:#1463ff; font-size:12px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all 0.2s;">
            <input type="checkbox" name="produk[]" value="Website" checked style="accent-color:#1463ff;" onchange="updateChipStyle(this)"> Website
        </label>

        <label class="product-chip" style="padding:10px 14px; border-radius:11px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all 0.2s;">
            <input type="checkbox" name="produk[]" value="Sistem POS" style="accent-color:#1463ff;" onchange="updateChipStyle(this)"> Sistem POS
        </label>

        <label class="product-chip" style="padding:10px 14px; border-radius:11px; border:1px solid #cfe0ff; background:#eef4ff; color:#1463ff; font-size:12px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all 0.2s;">
            <input type="checkbox" name="produk[]" value="SEO" checked style="accent-color:#1463ff;" onchange="updateChipStyle(this)"> SEO
        </label>

        <label class="product-chip" style="padding:10px 14px; border-radius:11px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all 0.2s;">
            <input type="checkbox" name="produk[]" value="Digital Marketing" style="accent-color:#1463ff;" onchange="updateChipStyle(this)"> Digital Marketing
        </label>

        <label class="product-chip" style="padding:10px 14px; border-radius:11px; border:1px solid #e8edf5; background:#fff; color:#5c6678; font-size:12px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:6px; transition:all 0.2s;">
            <input type="checkbox" name="produk[]" value="Custom System" style="accent-color:#1463ff;" onchange="updateChipStyle(this)"> Custom System
        </label>

    </div>
</div>

<script>
function updateChipStyle(checkbox) {
    const label = checkbox.closest('label');
    if (checkbox.checked) {
        label.style.background = '#eef4ff';
        label.style.borderColor = '#cfe0ff';
        label.style.color = '#1463ff';
    } else {
        label.style.background = '#fff';
        label.style.borderColor = '#e8edf5';
        label.style.color = '#5c6678';
    }
}
</script>
    
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Sumber mengetahui IT Solution</label>
                <select name="sumber_info" style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; cursor:pointer; box-sizing:border-box;">
                    <option value="google">Google Search</option>
                    <option value="instagram">Instagram</option>
                    <option value="referral">Rekomendasi / Teman</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:800; color:#172033; margin-bottom:8px;">Catatan kebutuhan</label>
                <input type="text" name="catatan" placeholder="Tuliskan kebutuhan singkat..." style="width:100%; border:1px solid #e8edf5; border-radius:12px; padding:13px 14px; font-size:13px; background:#fbfcfe; color:#172033; outline:none; box-sizing:border-box;">
            </div>
        </div>

        <div style="display:flex; gap:10px; align-items:flex-start; padding:14px; border-radius:12px; background:#f8fafc; font-size:12px; color:#697386;">
            <input type="checkbox" required style="margin-top:2px; accent-color:#1463ff;">
            <span>Saya menyetujui penggunaan data ini untuk keperluan pencatatan kunjungan dan tindak lanjut layanan IT Solution.</span>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; border-top:1px solid #e8edf5; padding-top:20px; margin-top:5px;">
            <button type="reset" style="background:#f0f3f8; color:#5e697c; border:none; border-radius:12px; padding:13px 20px; font-size:13px; font-weight:800; cursor:pointer;">
                Bersihkan Form
            </button>
            <button type="submit" style="background:#1463ff; color:#fff; border:none; border-radius:12px; padding:13px 24px; font-size:13px; font-weight:800; cursor:pointer; box-shadow:0 10px 22px rgba(20,99,255,.2);">
                Check-in Sekarang →
            </button>
        </div>

    </form>
</div>

@endsection