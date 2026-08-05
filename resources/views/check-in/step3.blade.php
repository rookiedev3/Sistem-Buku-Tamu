@extends('layouts.guest')

@section('content')
<div style="width: 100vw; min-height: 100vh; background: #f4f7fc; display: flex; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; margin: -24px;">
    
    <div style="width: 100%; max-width: 1150px; background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(31,53,97,0.1); border: 1px solid #e8edf5; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">
        
        <!-- Sisi Kiri: Stepper (Tahap 3 Aktif) -->
        <div style="background: linear-gradient(135deg, #006B3F, #1b8a5c); padding: 60px 40px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px;">
                    Guest Check-In
                </span>
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 24px 0 12px 0;">
                    Konfirmasi Data 🔍
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 40px 0;">
                    Periksa kembali seluruh data Anda sebelum melakukan submit akhir dan mendapatkan token antrian.
                </p>

                <!-- Indikator Tahapan -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.3); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">✓</div>
                        <span style="font-size: 14px; font-weight: 500;">Mengisi Identitas</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.3); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">✓</div>
                        <span style="font-size: 14px; font-weight: 500;">Keperluan Kunjungan</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #1463ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">3</div>
                        <span style="font-size: 14px; font-weight: 700;">Konfirmasi Data (Aktif)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.3); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">4</div>
                        <span style="font-size: 14px; font-weight: 500;">Selesai & Token Antrian</span>
                    </div>
                </div>
            </div>

            <div style="font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 40px;">
                &copy; {{ date('Y') }} Sistem Buku Tamu Digital.
            </div>
        </div>

        <!-- Sisi Kanan: Ringkasan Data & Persetujuan -->
        <div style="padding: 30px 60px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; background: #ffffff;">
            
            <div style="margin-bottom: 12px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Tahap 3: Konfirmasi Data</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Pastikan seluruh data di bawah ini sudah benar.</p>
            </div>

            <!-- Kartu Ringkasan Data -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px 20px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; max-height: 260px; overflow-y: auto;">
                
                <div style="font-size: 11px; font-weight: 700; color: #1463ff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">I. Informasi Identitas</div>
                
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Nama Lengkap:</span>
                    <span style="color: #172033; font-weight: 700;">Budi Santoso</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Asal Instansi:</span>
                    <span style="color: #172033; font-weight: 700;">PT Sinar Jaya</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Jabatan:</span>
                    <span style="color: #172033; font-weight: 700;">Manager IT</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">No WhatsApp:</span>
                    <span style="color: #172033; font-weight: 700;">081234567890</span>
                </div>

                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 2px 0;">

                <div style="font-size: 11px; font-weight: 700; color: #1463ff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">II. Detail Kunjungan</div>

                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Tujuan PIC:</span>
                    <span style="color: #172033; font-weight: 700;">Budi Santoso (Sales Manager)</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Jenis Kunjungan:</span>
                    <span style="color: #172033; font-weight: 700;">Pertemuan Bisnis / Meeting</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Produk Diminati:</span>
                    <span style="color: #172033; font-weight: 700;">Custom Software / Aplikasi</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Sumber Info:</span>
                    <span style="color: #172033; font-weight: 700;">Pencarian Google</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Keperluan:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 200px;">Diskusi kontrak kerja sama proyek baru.</span>
                </div>
            </div>

            <!-- Form Submit Akhir -->
            <form action="/check-in/step-4" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                @csrf

                <!-- Kotak Centang Persetujuan (Wajib Dicentang) -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 12px;">
                    <input type="checkbox" name="privacy_consent" id="privacy_consent" required 
                        style="width: 18px; height: 18px; accent-color: #1463ff; cursor: pointer; margin-top: 2px;">
                    <label for="privacy_consent" style="font-size: 12px; color: #475569; line-height: 1.5; cursor: pointer;">
                        Saya menyetujui penggunaan data ini untuk keperluan pencatatan kunjungan dan tindak lanjut layanan IT Solution.
                    </label>
                </div>

                <!-- Tombol Navigasi (Kembali & Konfirmasi) -->
                <div style="display: flex; gap: 12px;">
                    <a href="/check-in/step-2" style="flex: 1; background: #f1f5f9; color: #475569; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box;">
                        ⬅️ Kembali
                    </a>
                    <button type="submit" 
                        style="flex: 2; background:  #1463ff; color: #fff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(22,163,74,0.25);">
                        Konfirmasi & Check-In 🚀
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection