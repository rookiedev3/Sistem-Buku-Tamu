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

    /* Lingkaran / Bola-Bola Besar Transparan di Sidebar Kiri */
    .checkin-sidebar {
        background: linear-gradient(145deg, #01281b 0%, #013220 40%, #006B3F 100%);
        position: relative;
        overflow: hidden;
    }

    .checkin-sidebar::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 350px;
        height: 350px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
        pointer-events: none;
    }

    .checkin-sidebar::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Ukuran Gambar Logo Diperkecil & Transparan Putih */
    .logo-box-img {
        max-height: 15px;
        width: auto;
        margin-bottom: 16px;
        display: block;
        filter: brightness(0) invert(1);
    }
</style>

<div style="width: 100vw; min-height: 100vh; display: flex; box-sizing: border-box; margin: -24px; background-color: #f7faf8; position: relative; overflow-x: hidden;">

    <div class="checkin-container" style="width: 100%; max-width: 100%; background: #ffffff; border-radius: 0; box-shadow: none; border: none; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">

        <!-- Sidebar Kiri -->
        <div class="checkin-sidebar" style="padding: 60px 50px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="position: relative; z-index: 2;">
                <!-- Logo Perusahaan Berukuran Kecil -->
                <img src="{{ asset('images/foto-perusahaan.jpg') }}" alt="Logo Perusahaan" class="logo-box-img">

              

                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 20px 0 12px 0;">
                    Konfirmasi Data
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 35px 0;">
                    Periksa kembali seluruh data Anda sebelum melakukan submit akhir dan mendapatkan token antrian.
                </p>

                <!-- Step Navigasi -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">✓</div>
                        <span style="font-size: 14px; font-weight: 500;">Mengisi Identitas</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">✓</div>
                        <span style="font-size: 14px; font-weight: 500;">Keperluan Kunjungan</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #006B3F; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">3</div>
                        <span style="font-size: 14px; font-weight: 700;">Konfirmasi Data (Aktif)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">4</div>
                        <span style="font-size: 14px; font-weight: 500;">Selesai & Token Antrian</span>
                    </div>
                </div>
            </div>

            <!-- Informasi Alamat & Jam Kerja -->
            <div style="font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 40px; display: flex; flex-direction: column; gap: 8px; position: relative; z-index: 2;">
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
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 6px 0;">Tahap 3: Konfirmasi Data</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Pastikan seluruh data di bawah ini sudah benar.</p>
            </div>

            <div style="background: #fbfcfe; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; max-height: 360px; overflow-y: auto;">

                @php
                // Mengecek field 'photo_path' atau 'photo' dari data session step 1
                $photoPath = $step1Data['photo_path'] ?? $step1Data['photo'] ?? null;
                @endphp

                @if(!empty($photoPath))
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px; padding: 10px 14px; background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px;">
                    <img src="{{ asset('storage/' . $photoPath) }}"
                        alt="Foto Tamu"
                        style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #e8edf5;"
                        onerror="this.onerror=null; this.src='https://via.placeholder.com/55?text=Foto';" />

                    <div>
                        <span style="font-size: 13px; font-weight: 700; color: #172033; display: block;">Foto Identitas</span>
                        <span style="font-size: 11px; color: #10b981; font-weight: 600;">✓ Berhasil Terlampir</span>
                    </div>
                </div>
                @endif

                <div style="font-size: 11px; font-weight: 700; color: #006B3F; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">I. Informasi Identitas</div>

                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Nama Lengkap:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['name'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Email:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['email'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Kategori Tamu:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $guestCategory?->name ?? $step1Data['category'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Asal Instansi:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['company_name'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Jabatan:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['position'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">No WhatsApp:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['phone'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Alamat:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 220px;">{{ $step1Data['address'] ?? '-' }}</span>
                </div>

                <hr style="border: none; border-top: 1px solid #e8edf5; margin: 6px 0;">

                <div style="font-size: 11px; font-weight: 700; color: #006B3F; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">II. Detail Kunjungan</div>

                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Tujuan PIC:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $pic?->name ?? $pic?->role ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Cabang:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $branch?->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Jenis Kunjungan:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $purposeType?->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Produk Diminati:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 220px;">{{ $productNames }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Sumber Info:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $source?->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Waktu Check-In:</span>
                    <span style="color: #172033; font-weight: 700;">
                        @if(!empty($step2Data['check_in_at']))
                        {{ \Carbon\Carbon::parse($step2Data['check_in_at'])->translatedFormat('d M Y, H:i') }} WIB
                        @else
                        {{ now()->translatedFormat('d M Y, H:i') }} WIB
                        @endif
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #778195; font-weight: 600;">Keperluan:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 220px;">{{ $step2Data['notes'] ?? '-' }}</span>
                </div>
            </div>

            <form action="{{ route('check-in.step3') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf

                <div style="background: #fbfcfe; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 12px;">
                    <input type="checkbox" name="privacy_consent" id="privacy_consent" value="1" required
                        style="width: 18px; height: 18px; accent-color: #006B3F; cursor: pointer; margin-top: 2px;">
                    <label for="privacy_consent" style="font-size: 12px; color: #778195; line-height: 1.5; cursor: pointer;">
                        Saya menyetujui penggunaan data ini untuk keperluan pencatatan kunjungan dan tindak lanjut layanan IT Solution.
                    </label>
                </div>

                <!-- Navigasi Tombol (Kembali & Konfirmasi) -->
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <a href="{{ route('check-in.step2') }}" style="flex: 1; background: #006B3F; color: #ffffff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        Kembali
                    </a>
                    <button type="submit"
                        style="flex: 2; background: #C7AB6B; color: #013220; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0,107,63,0.15);">
                        Konfirmasi & Check-In
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection