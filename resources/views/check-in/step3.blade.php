@extends('layouts.guest')

@section('content')
<style>
    /* Responsive Styling untuk Tampilan Mobile & Tablet */
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

<div style="width: 100vw; min-height: 100vh; display: flex; box-sizing: border-box; margin: -24px; background-color: #ffffff; position: relative; overflow-x: hidden;">

    <div class="checkin-container" style="width: 100%; max-width: 100%; background: #ffffff; border-radius: 0; box-shadow: none; border: none; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">

        <!-- Sidebar Kiri -->
        <div class="checkin-sidebar" style="background: linear-gradient(135deg, #013220, #159A5C); padding: 60px 50px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px;">
                    Guest Check-In
                </span>
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 24px 0 12px 0;">
                    Konfirmasi Data
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 40px 0;">
                    Periksa kembali seluruh data Anda sebelum melakukan submit akhir dan mendapatkan token antrian.
                </p>

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
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #006B3F; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">3</div>
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

        <!-- Area Form Kanan -->
        <div class="checkin-form-area" style="padding: 40px 60px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; background: #ffffff;">

            <div style="margin-bottom: 12px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Tahap 3: Konfirmasi Data</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Pastikan seluruh data di bawah ini sudah benar.</p>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px 20px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; max-height: 340px; overflow-y: auto;">

                @php
                // Mengecek field 'photo_path' atau 'photo' dari data session step 1
                $photoPath = $step1Data['photo_path'] ?? $step1Data['photo'] ?? null;
                @endphp

                @if(!empty($photoPath))
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; padding: 10px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <img src="{{ asset('storage/' . $photoPath) }}"
                        alt="Foto Tamu"
                        style="width: 55px; height: 55px; object-fit: cover; border-radius: 10px; border: 1px solid #cbd5e1;"
                        onerror="this.onerror=null; this.src='https://via.placeholder.com/55?text=Foto';" />

                    <div>
                        <span style="font-size: 13px; font-weight: 700; color: #172033; display: block;">Foto Identitas</span>
                        <span style="font-size: 11px; color: #10b981; font-weight: 600;">✓ Berhasil Terlampir</span>
                    </div>
                </div>
                @endif

                <div style="font-size: 11px; font-weight: 700; color: #006B3F; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">I. Informasi Identitas</div>

                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Nama Lengkap:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['name'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Email:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['email'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Kategori Tamu:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $guestCategory?->name ?? $step1Data['category'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Asal Instansi:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['company_name'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Jabatan:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['position'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">No WhatsApp:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $step1Data['phone'] ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Alamat:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 220px;">{{ $step1Data['address'] ?? '-' }}</span>
                </div>

                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 6px 0;">

                <div style="font-size: 11px; font-weight: 700; color: #006B3F; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">II. Detail Kunjungan</div>

                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Tujuan PIC:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $pic?->name ?? $pic?->role ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Cabang:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $branch?->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Jenis Kunjungan:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $purposeType?->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Produk Diminati:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 220px;">{{ $productNames }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Sumber Info:</span>
                    <span style="color: #172033; font-weight: 700;">{{ $source?->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Waktu Check-In:</span>
                    <span style="color: #172033; font-weight: 700;">
                        @if(!empty($step2Data['check_in_at']))
                        {{ \Carbon\Carbon::parse($step2Data['check_in_at'])->translatedFormat('d M Y, H:i') }} WIB
                        @else
                        {{ now()->translatedFormat('d M Y, H:i') }} WIB
                        @endif
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span style="color: #64748b; font-weight: 600;">Keperluan:</span>
                    <span style="color: #172033; font-weight: 700; text-align: right; max-width: 220px;">{{ $step2Data['notes'] ?? '-' }}</span>
                </div>
            </div>

            <form action="{{ route('check-in.step3') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                @csrf

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 12px;">
                    <input type="checkbox" name="privacy_consent" id="privacy_consent" value="1" required
                        style="width: 18px; height: 18px; accent-color: #006B3F; cursor: pointer; margin-top: 2px;">
                    <label for="privacy_consent" style="font-size: 12px; color: #475569; line-height: 1.5; cursor: pointer;">
                        Saya menyetujui penggunaan data ini untuk keperluan pencatatan kunjungan dan tindak lanjut layanan IT Solution.
                    </label>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('check-in.step2') }}" style="flex: 1; background: #1463ff; color: #ffffff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box;">
                        <span style="font-size: 15px; line-height: 1; color: #ffffff;">&#8592;</span> Kembali
                    </a>
                    <button type="submit"
                        style="flex: 2; background: #C7AB6B; color: #013220; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0,107,63,0.25);">
                        Konfirmasi & Check-In
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection