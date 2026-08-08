@extends('layouts.guest')

@section('content')
<div style="width: 100vw; min-height: 100vh; background: #f4f7fc; display: flex; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; margin: -24px;">

    <div style="width: 100%; max-width: 1150px; background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(31,53,97,0.1); border: 1px solid #e8edf5; overflow: hidden; display: grid; grid-template-columns: 1fr 1.4fr; box-sizing: border-box;">

        <!-- Left Sidebar Info -->
        <div style="background: linear-gradient(135deg, #006B3F, #1b8a5c); padding: 60px 40px; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px;">
                    Guest Check-In
                </span>
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3; margin: 24px 0 12px 0;">
                    Keperluan Kunjungan 
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
                        <div style="width: 32px; height: 32px; background: #ffffff; color: #006B3F; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">2</div>
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

        <!-- Right Form Area -->
        <div style="padding: 40px 60px; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; background: #ffffff;">

            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 6px 0;">Tahap 2: Keperluan Kunjungan</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Kolom bertanda <span style="color: #e5484d; font-weight: bold;">*</span> wajib diisi.</p>
            </div>

            <form action="{{ route('check-in.store-step2') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf

                {{-- Tujuan Bertemu --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Tujuan Bertemu (Staff / PIC) <span style="color: #e5484d;">*</span></label>
                    <select name="assigned_to" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled {{ old('assigned_to', $step2Data['assigned_to'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Staff / PIC Tujuan --</option>
                        @if($pic->isEmpty())
                            <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                            @foreach($pic as $sales)
                                <option value="{{ $sales->id }}" {{ old('assigned_to', $step2Data['assigned_to'] ?? '') == $sales->id ? 'selected' : '' }}>
                                    {{ $sales->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Cabang Tujuan --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Cabang / Kantor Tujuan <span style="color: #e5484d;">*</span></label>
                    <select name="branch_id" required
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled {{ old('branch_id', $step2Data['branch_id'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Cabang / Kantor Tujuan --</option>
                        @if($branches->isEmpty())
                            <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $step2Data['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Jenis Kunjungan --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Jenis Kunjungan <span style="color: #e5484d;">*</span></label>
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
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Produk / Layanan yang Diminati</label>
                    <select name="product_interest"
                        style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="" {{ old('product_interest', $step2Data['product_interest'] ?? '') == '' ? 'selected' : '' }}>-- Pilih Produk / Layanan --</option>
                        @if($products->isEmpty())
                            <option value="" disabled>Data tidak ditemukan.</option>
                        @else
                            @foreach($products as $product)
                                <option value="{{ $product->code }}" {{ old('product_interest', $step2Data['product_interest'] ?? '') == $product->code ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Tanggal & Jam Kunjungan (Datepicker & Timepicker) --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">
                        Tanggal & Jam Kunjungan <span style="color: #e5484d;">*</span> <span style="font-weight: 400; color: #778195; font-size: 11px;">(08:00 - 17:00)</span>
                    </label>
                    
                    <div style="position: relative; display: flex; align-items: center; width: 100%;">
                        {{-- Icon Prefix --}}
                        <div style="position: absolute; left: 14px; display: flex; align-items: center; justify-content: center; pointer-events: none; color: #006B3F;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        
                        {{-- Input Text Flatpickr --}}
                        <input type="text" id="scheduled_at" name="scheduled_at"
                            value="{{ old('scheduled_at', $step2Data['scheduled_at'] ?? date('Y-m-d 08:00')) }}"
                            placeholder="Pilih tanggal & jam kunjungan..." required readonly
                            style="width: 100%; padding: 11px 16px 11px 44px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; cursor: pointer; box-sizing: border-box; font-family: inherit; transition: all 0.2s ease;">
                    </div>
                </div>

                {{-- Sumber Info --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Sumber Mengetahui IT Solution</label>
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
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 4px;">Detail Keperluan Kunjungan <span style="color: #e5484d;">*</span></label>
                    <textarea name="notes" rows="2" placeholder="Tuliskan ringkasan keperluan Anda berkunjung..." required
                        style="width: 100%; padding: 10px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 14px; outline: none; background: #fbfcfe; color: #172033; resize: vertical; box-sizing: border-box;">{{ old('purpose', $step2Data['purpose'] ?? '') }}</textarea>
                </div>

                {{-- Navigation Buttons --}}
                <div style="display: flex; gap: 12px; margin-top: 6px;">
                    <a href="{{ route('check-in.step1') }}" style="flex: 1; background: #f1f5f9; color: #475569; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; text-align: center; text-decoration: none; box-sizing: border-box;">
                        ⬅ Kembali
                    </a>
                    <button type="submit"
                        style="flex: 2; background: #1463ff; color: #fff; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(20,99,255,0.25);">
                        Selanjutnya: Konfirmasi Data 
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<!-- CDN CSS Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Custom Style UI Flatpickr (Style Pertama / Standard) -->
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

<!-- CDN JS Flatpickr & Bahasa Indonesia -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const liburNasional = [

        ];

        flatpickr("#scheduled_at", {
            locale: "id",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            minTime: "08:00",           // Membatasi jam mulai: 08:00 WIB
            maxTime: "17:00",           // Membatasi jam selesai: 17:00 WIB
            minuteIncrement: 15,
            disableMobile: "true",
            disable: [
                // 1. Blokir Hari Minggu
                function(date) {
                    return (date.getDay() === 0);
                },
                // 2. Blokir Tanggal Merah / Libur Nasional
                function(date) {
                    const formattedDate = flatpickr.formatDate(date, "Y-m-d");
                    return liburNasional.includes(formattedDate);
                }
            ]
        });
    });
</script>
@endsection