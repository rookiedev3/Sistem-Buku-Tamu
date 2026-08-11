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
                    Keperluan Kunjungan
                </h1>
                <p style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6; margin: 0 0 35px 0;">
                    Tentukan tujuan kunjungan, produk yang diminati, serta sampaikan detail keperluan Anda.
                </p>

                <!-- Step Navigasi -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">✓</div>
                        <span style="font-size: 14px; font-weight: 500;">Mengisi Identitas</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #006B3F; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">2</div>
                        <span style="font-size: 14px; font-weight: 700;">Keperluan Kunjungan (Aktif)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; opacity: 0.7;">
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">3</div>
                        <span style="font-size: 14px; font-weight: 500;">Konfirmasi Data</span>
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
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 6px 0;">Tahap 2: Keperluan Kunjungan</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Kolom bertanda <span style="color: #e5484d; font-weight: bold;">*</span> wajib diisi.</p>
            </div>

            <form action="{{ route('check-in.store-step2') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf

                {{-- Cabang Tujuan (Diletakkan di Atas PIC agar alur pengisian logis) --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Cabang / Kantor Tujuan <span style="color: #e5484d;">*</span></label>
                    <select name="branch_id" id="branch_select" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled {{ old('branch_id', $step2Data['branch_id'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Cabang / Kantor Tujuan --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $step2Data['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tujuan Bertemu (Staff / PIC) --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Tujuan Bertemu (Staff / PIC) <span style="color: #e5484d;">*</span></label>
                    <select name="assigned_to" id="pic_select" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Cabang Terlebih Dahulu --</option>
                    </select>
                </div>

                {{-- Jenis Kunjungan --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Jenis Kunjungan <span style="color: #e5484d;">*</span></label>
                    <select name="purpose_id" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled {{ old('purpose_id', $step2Data['purpose_id'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Jenis Kunjungan --</option>
                        @if($visitPurposes->isEmpty())
                        <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                        @foreach($visitPurposes as $purposes)
                        <option value="{{ $purposes->id }}" {{ old('purpose_id', $step2Data['purpose_id'] ?? '') == $purposes->id ? 'selected' : '' }}>
                            {{ $purposes->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>

                {{-- Produk Minat --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Produk / Layanan yang Diminati</label>
                    <select name="product_interest"
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" {{ old('product_interest', $step2Data['product_interest'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Produk / Layanan --</option>
                        @if($products->isEmpty())
                        <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_interest', $step2Data['product_interest'] ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>

                {{-- Tanggal & Jam Kunjungan --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">
                        Tanggal & Jam Kunjungan <span style="color: #e5484d;">*</span> <span style="font-weight: 400; color: #778195; font-size: 11px;">(08:00 - 17:00)</span>
                    </label>

                    <div style="position: relative; display: flex; align-items: center; width: 100%;">
                        <div style="position: absolute; left: 14px; display: flex; align-items: center; justify-content: center; pointer-events: none; color: #006B3F;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <input type="text" id="scheduled_at" name="scheduled_at"
                            value="{{ old('scheduled_at', $step2Data['scheduled_at'] ?? date('Y-m-d 08:00')) }}"
                            placeholder="Pilih tanggal & jam kunjungan..." required readonly
                            style="width: 100%; padding: 11px 16px 11px 44px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box; font-family: inherit; transition: all 0.2s ease;">
                    </div>
                </div>

                {{-- Sumber Info --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Sumber Mengetahui IT Solution</label>
                    <select name="source_id"
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" {{ old('source_id', $step2Data['source_id'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Sumber Informasi --</option>
                        @if($leadSources->isEmpty())
                        <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                        @foreach($leadSources as $lead)
                        <option value="{{ $lead->id }}" {{ old('source_id', $step2Data['source_id'] ?? '') == $lead->id ? 'selected' : '' }}>
                            {{ $lead->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>

                {{-- Detail Keperluan --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 5px;">Detail Keperluan Kunjungan <span style="color: #e5484d;">*</span></label>
                    <textarea name="notes" rows="2" placeholder="Tuliskan ringkasan keperluan Anda berkunjung..." required
                        style="width: 100%; padding: 10px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; resize: vertical; box-sizing: border-box;">{{ old('notes', $step2Data['notes'] ?? '') }}</textarea>
                </div>

                <!-- Navigasi Tombol (Kembali & Selanjutnya) -->
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <a href="{{ route('check-in.step1') }}" style="flex: 1; background: #006B3F; color: #ffffff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span style="font-size: 15px; line-height: 1; color: #ffffff;">&#8592;</span> Kembali
                    </a>
                    <button type="submit"
                        style="flex: 2; background: #C7AB6B; color: #013220; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0,107,63,0.15);">
                        Selanjutnya: Konfirmasi Data
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .flatpickr-calendar {
        border-radius: 16px !important;
        box-shadow: 0 12px 32px rgba(31, 53, 97, 0.15) !important;
        border: 1px solid #e8edf5 !important;
        font-family: inherit !important;
        padding: 8px !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.selected:hover {
        background: #006B3F !important;
        border-color: #006B3F !important;
        font-weight: 600;
        border-radius: 10px !important;
    }

    .flatpickr-day:hover {
        border-radius: 10px !important;
    }

    .flatpickr-months .flatpickr-month {
        color: #172033 !important;
        fill: #172033 !important;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 700 !important;
    }

    span.flatpickr-weekday {
        color: #778195 !important;
        font-weight: 600 !important;
    }

    #scheduled_at:focus {
        border-color: #006B3F !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const liburNasional = [];

        flatpickr("#scheduled_at", {
            locale: "id",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            minTime: "08:00",
            maxTime: "17:00",
            minuteIncrement: 15,
            disableMobile: "true",
            disable: [
                function(date) {
                    return (date.getDay() === 0);
                },
                function(date) {
                    const formattedDate = flatpickr.formatDate(date, "Y-m-d");
                    return liburNasional.includes(formattedDate);
                }
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const branchSelect = document.getElementById('branch_select');
        const picSelect = document.getElementById('pic_select');

        // Simpan ID PIC lama jika ada (untuk penanganan validasi gagal / tombol kembali)
        const selectedPicId = "{{ old('assigned_to', $step2Data['assigned_to'] ?? '') }}";

        function loadPics(branchId, selectedPic = null) {
            if (!branchId) return;

            // Reset dropdown PIC ke status loading
            picSelect.innerHTML = '<option value="" disabled selected>Memuat data PIC...</option>';

            fetch(`/get-pics-by-branch/${branchId}`)
                .then(response => response.json())
                .then(data => {
                    picSelect.innerHTML = '';

                    if (data.length === 0) {
                        picSelect.innerHTML = '<option value="" disabled selected>Tidak ada PIC di cabang ini</option>';
                        return;
                    }

                    picSelect.innerHTML = '<option value="" disabled selected>-- Pilih Staff / PIC Tujuan --</option>';

                    data.forEach(pic => {
                        const option = document.createElement('option');
                        option.value = pic.id;
                        option.textContent = pic.name;

                        if (selectedPic && selectedPic == pic.id) {
                            option.selected = true;
                        }

                        picSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching PICs:', error);
                    picSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data PIC</option>';
                });
        }

        // Trigger otomatis saat cabang dipilih
        branchSelect.addEventListener('change', function() {
            loadPics(this.value);
        });

        // Otomatis load data PIC saat halaman dibuka pertama kali jika cabang sudah terpilih sebelumnya
        if (branchSelect.value) {
            loadPics(branchSelect.value, selectedPicId);
        }
    });
</script>
@endsection