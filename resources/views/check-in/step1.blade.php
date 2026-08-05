@extends('layouts.guest')

@section('content')
<div style="width: 100vw; min-height: 100vh; background: #f4f7fc; display: flex; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; margin: -24px;">
    
    <div style="width: 100%; max-width: 1150px; background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(31,53,97,0.1); border: 1px solid #e8edf5; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">
        
        <div style="background: linear-gradient(135deg, #1463ff, #0a4cd9); padding: 60px 40px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px;">
                    Guest Check-In
                </span>
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 24px 0 12px 0;">
                    Selamat Datang! 👋
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 40px 0;">
                    Silakan isi data identitas Anda untuk memulai proses check-in kunjungan.
                </p>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #1463ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">1</div>
                        <span style="font-size: 14px; font-weight: 700;">Mengisi Identitas (Aktif)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.3); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">2</div>
                        <span style="font-size: 14px; font-weight: 500;">Keperluan Kunjungan</span>
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

        <div style="padding: 50px 60px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; background: #ffffff;">
            
            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 6px 0;">Tahap 1: Mengisi Identitas</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Kolom bertanda <span style="color: #e5484d; font-weight: bold;">*</span> wajib diisi.</p>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">                @csrf

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Nama Lengkap <span style="color: #e5484d;">*</span></label>
                    <input type="text" name="name" placeholder="Masukkan nama lengkap Anda" required 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Asal Instansi / Perusahaan <span style="color: #e5484d;">*</span></label>
                    <input type="text" name="company_name" placeholder="Contoh: PT / Universitas / Pribadi" required 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Alamat Instansi / Perusahaan</label>
                    <input type="text" name="address" placeholder="Contoh: Jl. Sudirman No. 123, Jakarta Selatan" 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Jabatan di Perusahaan <span style="color: #e5484d;">*</span></label>
                    <input type="text" name="position" placeholder="Contoh: Staff, Manager, Direktur" required 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Nomor WhatsApp (Aktif) <span style="color: #e5484d;">*</span></label>
                    <input type="tel" name="phone" placeholder="Contoh: 081234567890" required 
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

               <!-- Foto Tamu (Opsional) -->
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Foto Tamu <span style="font-weight: 400; color: #778195;">(Opsional)</span></label>
                <input type="file" name="photo" accept="image/*" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box; cursor: pointer;">
                <span style="font-size: 11px; color: #778195; display: block; margin-top: 4px;">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
            </div>

                <div style="margin-top: 10px;">
                    <form action="/check-in/step-2" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
    @csrf
                    <button type="submit" 
                        style="width: 100%; background: #1463ff; color: #fff; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(20,99,255,0.25);">
                        Selanjutnya: Keperluan Kunjungan ➡️
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection