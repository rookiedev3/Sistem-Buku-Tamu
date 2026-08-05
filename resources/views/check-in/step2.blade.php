@extends('layouts.guest')

@section('content')
<div style="width: 100vw; min-height: 100vh; background: #f4f7fc; display: flex; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; margin: -24px;">
    
    <div style="width: 100%; max-width: 1150px; background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(31,53,97,0.1); border: 1px solid #e8edf5; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">
        
        <div style="background: linear-gradient(135deg,  #006B3F, #1b8a5c); padding: 60px 40px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px;">
                    Guest Check-In
                </span>
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 24px 0 12px 0;">
                    Keperluan Kunjungan 📋
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 40px 0;">
                    Tentukan tujuan kunjungan, produk yang diminati, serta sampaikan detail keperluan Anda.
                </p>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.3); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">✓</div>
                        <span style="font-size: 14px; font-weight: 500;">Mengisi Identitas</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #1463ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">2</div>
                        <span style="font-size: 14px; font-weight: 700;">Keperluan Kunjungan (Aktif)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.3); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">3</div>
                        <span style="font-size: 14px; font-weight: 500;">Konfirmasi Data</span>
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

        <div style="padding: 40px 60px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; background: #ffffff;">
            
            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 6px 0;">Tahap 2: Keperluan Kunjungan</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Kolom bertanda <span style="color: #e5484d; font-weight: bold;">*</span> wajib diisi.</p>
            </div>

            <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Tujuan Bertemu (Staff / PIC) <span style="color: #e5484d;">*</span></label>
                    <select name="met_with" required 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Staff / PIC Tujuan --</option>
                        <option value="Budi Santoso">Budi Santoso (Sales Manager)</option>
                        <option value="Siti Aminah">Siti Aminah (IT Support)</option>
                        <option value="Rian Pratama">Rian Pratama (Customer Service)</option>
                        <option value="Dewi Lestari">Dewi Lestari (HRD / Personalia)</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Jenis Kunjungan <span style="color: #e5484d;">*</span></label>
                    <select name="visit_type" required 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Jenis Kunjungan --</option>
                        <option value="Pertemuan Bisnis">Pertemuan Bisnis / Meeting</option>
                        <option value="Konsultasi">Konsultasi Layanan</option>
                        <option value="Vendor / Kurir">Vendor / Kurir / Pengiriman</option>
                        <option value="Wawancara">Wawancara Kerja</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Produk / Layanan yang Diminati</label>
                    <select name="product_interest" 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Produk / Layanan --</option>
                        <option value="Website Development">Website Development</option>
                        <option value="Custom Software">Custom Software / Aplikasi</option>
                        <option value="Sistem POS">Sistem POS (Kasir)</option>
                        <option value="IT Consulting">IT Consulting & Support</option>
                        <option value="Tidak Ada">Tidak Ada / Umum</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Sumber Mengetahui IT Solution</label>
                    <select name="source_info" 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Sumber Informasi --</option>
                        <option value="Google">Pencarian Google</option>
                        <option value="Instagram / Media Sosial">Instagram / Media Sosial</option>
                        <option value="Rekan / Teman">Rekan / Teman / Keluarga</option>
                        <option value="Pameran / Event">Pameran / Event Perusahaan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Detail Keperluan Kunjungan <span style="color: #e5484d;">*</span></label>
                    <textarea name="purpose" rows="2" placeholder="Tuliskan ringkasan keperluan Anda berkunjung..." required 
                        style="width: 100%; padding: 10px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; resize: vertical; box-sizing: border-box;"></textarea>
                </div>

                <form action="/check-in/step-3" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
    @csrf
                <div style="display: flex; gap: 12px; margin-top: 6px;">
                    <a href="/check-in/step-1" style="flex: 1; background: #f1f5f9; color: #475569; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box;">
                        ⬅️ Kembali
                    </a>
                    <button type="submit" 
                        style="flex: 2; background: #1463ff; color: #fff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(20,99,255,0.25);">
                        Selanjutnya: Konfirmasi Data ➡️
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection