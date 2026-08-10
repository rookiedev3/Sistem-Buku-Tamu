@extends('layouts.guest')

@section('content')
<style>
    /* Responsive Styling untuk Tampilan Mobile */
    @media (max-width: 991px) {
        .checkin-container {
            grid-template-columns: 1fr !important;
        }
        .checkin-sidebar {
            padding: 40px 30px !important;
        }
        .checkin-form-area {
            padding: 40px 30px !important;
        }
    }
    @media (max-width: 480px) {
        .checkin-sidebar {
            padding: 30px 20px !important;
        }
        .checkin-form-area {
            padding: 30px 20px !important;
        }
    }
</style>

<div style="width: 100vw; min-height: 100vh; display: flex; box-sizing: border-box; margin: -24px; background-color: #f7faf8; position: relative; overflow-x: hidden;">

    <div class="checkin-container" style="width: 100%; max-width: 100%; background: #ffffff; border-radius: 0; box-shadow: none; border: none; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">

        <!-- Sidebar Kiri -->
        <div class="checkin-sidebar" style="background: linear-gradient(135deg, #013220, #159A5C); padding: 60px 50px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px;">
                    Guest Check-In
                </span>
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 24px 0 12px 0;">
                    Selamat Datang! 
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 40px 0;">
                    Silakan isi data identitas Anda untuk memulai proses check-in kunjungan agar tim kami dapat menyambut dan melayani Anda dengan lebih cepat
                </p>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #006B3F; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">1</div>
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

            <div style="font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 40px; display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span>Kantor Sleman, Yogyakarta</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.85); font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span>Senin-Sabtu, 08.00-16.00 WIB</span>
                </div>
            </div>
        </div> 

        <!-- Area Form Kanan -->
        <div class="checkin-form-area" style="padding: 60px 80px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; background: #ffffff;">

            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 6px 0;">Tahap 1: Mengisi Identitas</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Kolom bertanda <span style="color: #e5484d; font-weight: bold;">*</span> wajib diisi.</p>
            </div>

            <form action="{{ route('check-in.store-step1') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Nama Lengkap <span style="color: #e5484d;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $step1Data['name'] ?? '') }}" placeholder="Masukkan nama lengkap Anda" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Asal Instansi / Perusahaan <span style="color: #e5484d;">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $step1Data['company_name'] ?? '') }}" placeholder="Contoh: PT / Universitas / Pribadi" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Alamat Instansi / Perusahaan</label>
                    <input type="text" name="address" value="{{ old('address', $step1Data['address'] ?? '') }}" placeholder="Contoh: Jl. Sudirman No. 123, Jakarta Selatan"
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Jabatan di Perusahaan <span style="color: #e5484d;">*</span></label>
                    <input type="text" name="position" value="{{ old('position', $step1Data['position'] ?? '') }}" placeholder="Contoh: Staff, Manager, Direktur" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Nomor WhatsApp (Aktif) <span style="color: #e5484d;">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', $step1Data['phone'] ?? '') }}" pattern="^(\+62|62|0)8[1-9][0-9]{7,11}$" placeholder="Contoh: 081234567890" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Email <span style="color: #e5484d;">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $step1Data['email'] ?? '') }}" placeholder="Contoh: nama@email.com" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Kategori Pengunjung</label>
                    <select name="guest_category_id"
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" {{ old('guest_category_id', $step1Data['guest_category_id'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Kategori --</option>
                        @if($guestCategories->isEmpty())
                        <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                        @foreach($guestCategories as $categories)
                        <option value="{{ $categories->id }}" {{ old('guest_category_id', $step1Data['guest_category_id'] ?? '') == $categories->id ? 'selected' : '' }}>
                            {{ $categories->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Foto Tamu <span style="font-weight: 400; color: #778195;">(Opsional)</span></label>
                    @if(!empty($step1Data['photo']))
                    <div style="margin-bottom: 12px;">
                        <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Foto Terunggah:</p>
                        <img src="{{ asset('storage/' . $step1Data['photo']) }}" alt="Preview" style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #e8edf5;">
                    </div>
                    @endif
                    
                    <input type="file" id="photoInput" name="photo_path" accept="image/*" onchange="validateFileSize(this)"
                        style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #fbfcfe; color: #172033; box-sizing: border-box; cursor: pointer;">
                    
                    <span style="font-size: 11px; color: #778195; display: block; margin-top: 4px;">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                    <small id="fileError" style="color: #dc2626; display: none; margin-top: 4px; font-size: 12px;"></small>
                    
                    @error('photo_path')
                        <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Navigasi Tombol (Kembali & Selanjutnya) -->
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <a href="{{ route('halamaanUtama') }}" style="flex: 1; background: #1463ff; color: #ffffff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span style="font-size: 15px; line-height: 1; color: #ffffff;">&#8592;</span> Kembali
                    </a>
                    <button type="submit"
                        style="flex: 2; background: #C7AB6B; color: #013220; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(20,99,255,0.25);">
                        Selanjutnya: Keperluan Kunjungan 
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

<script>
    function validateFileSize(input) {
        const file = input.files[0];
        const errorElement = document.getElementById('fileError');
        const maxSizeBytes = 2 * 1024 * 1024; // 2 MB dalam Bytes

        if (file) {
            if (file.size > maxSizeBytes) {
                errorElement.textContent = 'Ukuran file terlalu besar! Maksimal 2 MB.';
                errorElement.style.display = 'block';
                input.value = ''; // Reset file input
            } else {
                errorElement.style.display = 'none';
            }
        }
    }
</script>
@endsection