@extends('layouts.frontoffice')

@section('content')

<!-- CDN CSS Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
        margin: 16px 0;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* ==========================================================
       STYLING FLATPICKR - COMPACT, SOFT BORDER & MINIMALIS
       ========================================================== */

    /* Input Field Datepicker */
    .flatpickr-custom-input {
        font-size: 12px !important;
        padding: 8px 12px !important;
        color: #172033 !important;
        font-weight: 600 !important;
        border: 1px solid #e8edf5 !important;
        border-radius: 10px !important;
    }

    .flatpickr-custom-input[readonly] {
        background-color: #ffffff !important;
        cursor: pointer !important;
    }

    .flatpickr-custom-input:focus,
    .flatpickr-custom-input.active {
        border-color: #006B3F !important;
        box-shadow: 0 0 0 2px rgba(0, 107, 63, 0.08) !important;
    }

    /* Popup Kalender Ringkas & Border Soft */
    .flatpickr-calendar {
        z-index: 99999 !important;
        width: 215px !important;
        border-radius: 12px !important;
        border: 1px solid #f1f5f9 !important;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06) !important;
        font-family: inherit !important;
        padding: 6px !important;
        background: #ffffff !important;
    }

    .flatpickr-innerContainer {
        justify-content: center !important;
    }

    .flatpickr-days,
    .dayContainer {
        width: 196px !important;
        min-width: 196px !important;
        max-width: 196px !important;
    }

    /* Header Bulan & Navigasi */
    .flatpickr-months .flatpickr-month {
        height: 26px !important;
    }

    .flatpickr-current-month {
        font-weight: 700 !important;
        font-size: 11px !important;
        padding-top: 0 !important;
    }

    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        padding: 2px !important;
        height: 22px !important;
        width: 22px !important;
    }

    /* Header Nama Hari */
    span.flatpickr-weekday {
        color: #006B3F !important;
        font-weight: 700 !important;
        font-size: 9.5px !important;
    }

    /* Grid Angka Tanggal */
    .flatpickr-day {
        border-radius: 6px !important;
        color: #172033 !important;
        font-weight: 500 !important;
        font-size: 10px !important;
        max-width: 28px !important;
        height: 24px !important;
        line-height: 24px !important;
        margin-top: 1px !important;
        border: none !important;
    }

    .flatpickr-day:hover,
    .flatpickr-day:focus {
        background: #f0fdf4 !important;
        color: #006B3F !important;
        border: none !important;
    }

    /* Indikator Hari Ini (Today) */
    .flatpickr-day.today {
        border: 1px solid #86efac !important;
        color: #006B3F !important;
        font-weight: 700 !important;
    }

    /* Tanggal Terpilih */
    .flatpickr-day.selected,
    .flatpickr-day.selected:hover,
    .flatpickr-day.selected:focus {
        background: #006B3F !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }

    /* Pembatas Time Picker (Jam & Menit) */
    .flatpickr-time {
        border-top: 1px solid #f8fafc !important;
        margin-top: 4px !important;
        padding-top: 2px !important;
        height: 28px !important;
        line-height: 28px !important;
    }

    .flatpickr-time input {
        font-weight: 700 !important;
        color: #172033 !important;
        font-size: 11px !important;
    }

    /* Style Tombol Filter Tab */
    .btn-filter-tab {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
        color: #475569;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-filter-tab.active {
        background: #013220;
        color: #ffffff;
        border-color: #006B3F;
    }

    /* Validasi Error */
    .field-error {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.08) !important;
    }

    .error-message {
        display: none;
        color: #dc2626;
        font-size: 11px;
        font-weight: 600;
        margin-top: 4px;
    }

    .error-message.show {
        display: block;
    }
</style>

{{-- Flash Messages --}}
@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 20px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 12px 20px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('error') }}
</div>
@endif

{{-- Banner Halaman Kelola Janji Temu --}}
<div id="welcomeBanner" class="card border-0 rounded-4 p-4 mb-4 shadow-sm position-relative" style="background-color: #013220; color: white;">
    <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" aria-label="Close"></button>

    <div class="d-flex justify-content-between align-items-center pe-4">
        <div>
            <h4 class="fw-bold mb-1 text-white">Selamat datang, {{ Auth::user()->name ?? 'Pimpinan / Owner' }} </h4>
            <p class="mb-0 text-white-50 fs-6">Berikut adalah ringkasan aktivitas buku tamu dan kunjungan kantor hari ini.</p>
        </div>
    </div>
</div>

{{-- Kartu Statistik Rangkuman --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div style="background: #ffffff; padding: 18px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span style="font-size: 11px; color: #778195; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px;">Total Terjadwal</span>
            <span style="font-size: 22px; font-weight: 800; color: #006B3F;">
                {{ method_exists($visits, 'total') ? $visits->total() : count($visits) }} 
                <span style="font-size: 13px; font-weight: 600; color: #778195;">Agenda</span>
            </span>
        </div>
        <div style="width: 42px; height: 42px; background: #e6f4ed; color: #006B3F; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
        </div>
    </div>

    <div style="background: #ffffff; padding: 18px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span style="font-size: 11px; color: #778195; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 4px;">Belum Selesai Hari Ini</span>
            <span style="font-size: 22px; font-weight: 800; color: #0284c7;">
                {{ $unfinishedTodayCount }} 
                <span style="font-size: 13px; font-weight: 600; color: #778195;">Tamu</span>
            </span>
        </div>
        <div style="width: 42px; height: 42px; background: #e0f2fe; color: #0284c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 22h14" />
                <path d="M5 2h14" />
                <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" />
                <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" />
            </svg>
        </div>
    </div>
</div>

{{-- Container Utama Tabel --}}
<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">

    {{-- Filter & Header Tabel --}}
    <form id="dashboardFilterForm" method="GET" action="{{ route('frontoffice.dashboard') }}" style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 14px; margin: 0;">
        <input type="hidden" name="date_filter" id="dateFilterInput" value="{{ request('date_filter', 'all') }}">
        
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
            <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Daftar Reservasi & Janji Temu</h3>

            {{-- TOMBOL FILTER SEMUA & HARI INI --}}
            <div style="display: flex; gap: 6px; align-items: center;">
                <button type="button" id="btnFilterAll" onclick="setDateFilter('all')" class="btn-filter-tab {{ request('date_filter', 'all') === 'all' ? 'active' : '' }}">
                    Semua
                </button>
                <button type="button" id="btnFilterToday" onclick="setDateFilter('today')" class="btn-filter-tab {{ request('date_filter') === 'today' ? 'active' : '' }}">
                    Hari Ini
                </button>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="text" id="searchApp" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama tamu / PIC..." oninput="handleSearchInput(this)"
                style="padding: 9px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #ffffff; width: 240px; transition: all 0.2s ease;"
                onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">

            <button type="button" onclick="openAppointmentModal()" style="background: #013220; color: #fff; border: none; padding: 9px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,107,63,0.15); white-space: nowrap;">
                + Buat Janji Temu
            </button>
        </div>
    </form>

    {{-- Table Responsive Wrapper --}}
    <div style="overflow-x: auto;">
        <table id="guestTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 900px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tanggal & Waktu</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Jenis Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $index => $visit)
                @php
                    $visitDate = $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->format('Y-m-d') : ($visit->check_in_at ? \Carbon\Carbon::parse($visit->check_in_at)->format('Y-m-d') : '');
                @endphp
                <tr class="visit-row-item" data-date="{{ $visitDate }}" style="border-bottom: 1px solid #e8edf5;">
                    <td class="row-number" style="padding: 16px 20px; font-weight: 600;">
                        {{ method_exists($visits, 'firstItem') && $visits->firstItem() ? $visits->firstItem() + $index : $index + 1 }}
                    </td>

                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">{{ $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->translatedFormat('d M Y') : ($visit->check_in_at ? \Carbon\Carbon::parse($visit->check_in_at)->translatedFormat('d M Y') : '-') }}</span>
                        <span style="font-size: 11px; color: #778195; font-weight: 600;">{{ $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->format('H:i') . ' WIB' : '-' }}</span>
                    </td>

                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700; color: #172033;">
                            {{ $visit->guest->name ?? '-' }}
                            @if(isset($visit->guest) && $visit->guest->is_vip)
    <span style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700;">VIP</span>
@endif
                        </div>
                        <div style="font-size: 11px; color: #778195;">{{ $visit->guest->company_name ?? '-' }} ({{ $visit->guest->position ?? '-' }})</div>
                    </td>

                    <td style="padding: 16px 20px;">{{ $visit->purpose->name ?? '-' }}</td>

                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            {{ $visit->assignedUser->name ?? '-' }}
                        </span>
                    </td>

                    <td style="padding: 16px 20px;">
                        @php $statusLower = strtolower($visit->status ?? ''); @endphp

                        @if(in_array($statusLower, ['terjadwal', 'scheduled']))
                        <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Terjadwal
                        </span>
                        @elseif(in_array($statusLower, ['menunggu', 'waiting']))
                        <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Menunggu
                        </span>
                        @elseif(in_array($statusLower, ['sedang bertemu', 'confirmed', 'dikonfirmasi']))
                        <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Sedang Bertemu
                        </span>
                        @elseif(in_array($statusLower, ['selesai', 'completed']))
                        <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Selesai
                        </span>
                        @elseif(in_array($statusLower, ['dibatalkan', 'cancelled']))
                        <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Dibatalkan
                        </span>
                        @else
                        <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            {{ $visit->status }}
                        </span>
                        @endif
                    </td>

                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: wrap;">
                            @if(in_array($statusLower, ['terjadwal', 'scheduled']))
                            <form action="{{ route('frontoffice.checkin', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Check-in
                                </button>
                            </form>

                            <form id="cancel-form-{{ $visit->id }}" action="{{ route('frontoffice.cancel', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="button" onclick="confirmCancel('{{ $visit->id }}', '{{ addslashes($visit->guest->name ?? 'Tamu') }}')" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Batalkan
                                </button>
                            </form>

                            @elseif(in_array($statusLower, ['menunggu', 'waiting']))
                            <span style="font-size: 11px; color: #d97706; font-weight: 600;">Menunggu</span>

                            @elseif(in_array($statusLower, ['sedang bertemu', 'confirmed', 'meeting selesai', 'meeting_selesai', 'dikonfirmasi']))
                            <form action="{{ route('frontoffice.checkout', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Check-out
                                </button>
                            </form>

                            @elseif(in_array($statusLower, ['dibatalkan', 'cancelled']))
                            <span style="font-size: 11px; color: #dc2626; font-weight: 600;">Dibatalkan</span>

                            @else
                            <span style="font-size: 11px; color: #64748b; font-weight: 600;">Selesai</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" style="padding: 30px; text-align: center; color: #64748b;">Belum ada antrian kunjungan hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Pagination --}}
    <div id="paginationContainer" style="padding: 16px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe;">
        @include('partials.pagination', ['paginator' => $visits])
    </div>
</div>

{{-- MODAL MULTI-STEP INPUT MANUAL JANJI TEMU --}}
<div id="manualModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 999; padding: 16px; box-sizing: border-box;">

    <div style="background: #ffffff; width: 100%; max-width: 520px; max-height: 90vh; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); box-sizing: border-box; overflow: hidden; display: flex; flex-direction: column;">

        <div style="padding: 24px 32px 16px 32px; border-bottom: 1px solid #e8edf5; background: #fbfcfe;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0;">Input Janji Temu Manual</h3>
                <span id="stepIndicatorText" style="font-size: 11px; font-weight: 700; color: #006B3F; background: #e6f4ed; padding: 4px 10px; border-radius: 20px;">Langkah 1 dari 3</span>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <div id="bar-step-1" style="flex: 1; height: 6px; background: #006B3F; border-radius: 10px; transition: all 0.3s ease;"></div>
                <div id="bar-step-2" style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 10px; transition: all 0.3s ease;"></div>
                <div id="bar-step-3" style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 10px; transition: all 0.3s ease;"></div>
            </div>
        </div>

        <div id="modalFormContainer" class="custom-scroll" style="padding: 28px 32px; overflow-y: auto; max-height: 70vh; box-sizing: border-box;">
            <form id="multiStepForm" action="{{ route('frontoffice.storeManual') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 14px;" novalidate>
                @csrf

                {{-- STEP 1: PROFIL TAMU --}}
                <div id="step-1-content">
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Nama Lengkap <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="input_name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="validateField(this)" oninput="validateField(this)" data-error-msg="Nama lengkap wajib diisi.">
                        <small class="error-message" id="err_input_name"></small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Asal Instansi / Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="input_company" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT / Universitas / Pribadi" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="validateField(this)" oninput="validateField(this)" data-error-msg="Asal instansi / perusahaan wajib diisi.">
                        <small class="error-message" id="err_input_company"></small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Alamat Instansi / Perusahaan
                        </label>
                        <input type="text" id="input_address" name="address" value="{{ old('address') }}" placeholder="Contoh: Jl. Sudirman No. 123, Jakarta"
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Jabatan di Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="input_position" name="position" value="{{ old('position') }}" placeholder="Contoh: Staff, Manager, Direktur" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="validateField(this)" oninput="validateField(this)" data-error-msg="Jabatan wajib diisi.">
                        <small class="error-message" id="err_input_position"></small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Nomor WhatsApp (Aktif) <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="tel" id="input_phone" name="phone" value="{{ old('phone') }}" pattern="^(\+62|62|0)8[1-9][0-9]{7,11}$" maxlength="16" placeholder="Contoh: 081234567890" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="validateField(this, true)" oninput="validateField(this, false)" data-error-msg="Nomor WhatsApp wajib diisi." data-error-pattern="Format nomor tidak valid. Contoh: 081234567890" data-error-toolong="Nomor WhatsApp melebihi batas maksimal (16 digit). Periksa kembali nomor Anda.">
                        <small class="error-message" id="err_input_phone"></small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Email <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="email" id="input_email" name="email" value="{{ old('email') }}" placeholder="Contoh: nama@email.com" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="validateField(this, true)" oninput="validateField(this, false)" data-error-msg="Email wajib diisi." data-error-pattern="Format email tidak valid.">
                        <small class="error-message" id="err_input_email"></small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Kategori Pengunjung <span style="color: #dc2626;">*</span>
                        </label>
                        <select id="select_category" name="guest_category_id" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; cursor: pointer; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="validateField(this)" onchange="validateField(this); updateSummary()" data-error-msg="Kategori pengunjung wajib dipilih.">
                            <option value="">-- Pilih Kategori --</option>
                            @if(isset($guestCategories))
                            @foreach($guestCategories as $categories)
                            <option value="{{ $categories->id }}">
                                {{ $categories->name }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                        <small class="error-message" id="err_select_category"></small>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Foto Tamu <span style="font-weight: 500; color: #778195;">(Opsional)</span>
                        </label>
                        <input type="file" id="photoInput" name="photo_path" accept="image/*" onchange="validateFileSize(this)"
                            style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; cursor: pointer; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">

                        <span style="font-size: 11px; color: #778195; display: block; margin-top: 6px;">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                        <small id="fileError" style="color: #dc2626; display: none; margin-top: 4px; font-size: 12px;"></small>
                    </div>
                </div>

                {{-- STEP 2: TUJUAN KUNJUNGAN --}}
                <div id="step-2-content" style="display: none;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 14px 0;">
                        2. Tujuan & Keperluan Kunjungan
                    </h4>

                    {{-- 1. PILIH CABANG TERLEBIH DAHULU --}}
                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih Cabang *</label>
                        <select name="branch_id" id="select_branch" required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="loadPicsForModal(this.value); validateField(this); updateSummary();" onblur="validateField(this)" data-error-msg="Cabang wajib dipilih.">
                            <option value="" disabled selected>-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <small class="error-message" id="err_select_branch"></small>
                    </div>

                    {{-- 2. PILIH PIC TUJUAN (DINAMIS DIBUAT BERDASARKAN CABANG) --}}
                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih PIC Tujuan *</label>
                        <select id="select_pic" name="assigned_to" required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="validateField(this); updateSummary()" onblur="validateField(this)" data-error-msg="PIC tujuan wajib dipilih.">
                            <option value="" disabled selected>-- Pilih Cabang Terlebih Dahulu --</option>
                        </select>
                        <small class="error-message" id="err_select_pic"></small>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih Keperluan Kunjungan *</label>
                        <select name="purpose_id" id="select_purpose" required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="validateField(this); updateSummary()" onblur="validateField(this)" data-error-msg="Keperluan kunjungan wajib dipilih.">
                            <option value="" disabled selected>-- Pilih Keperluan --</option>
                            @foreach($purposes as $purpose)
                            <option value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                            @endforeach
                        </select>
                        <small class="error-message" id="err_select_purpose"></small>
                    </div>

                    {{-- PILIH PRODUK / LAYANAN (dikirim sebagai product_id) --}}
                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih Produk / Layanan yang Diminati</label>
                        <select name="product_id" id="select_product" style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="">-- Pilih Produk / Layanan --</option>
                            @if(isset($products))
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Tanggal & Jam Kunjungan *</label>
                        <input type="text" id="input_scheduled_at" name="scheduled_at" class="flatpickr-custom-input" readonly required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; outline: none;" onchange="validateField(this); updateSummary()" data-error-msg="Tanggal & jam kunjungan wajib dipilih.">
                        <small class="error-message" id="err_input_scheduled_at"></small>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Sumber Mengetahui IT Solution</label>
                        <select name="source_id" id="select_source" style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="">-- Pilih Sumber Informasi --</option>
                            @if(isset($leadSources))
                            @foreach($leadSources as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Detail Keperluan Kunjungan *</label>
                        <textarea name="notes" id="input_notes" rows="2" placeholder="Tuliskan ringkasan keperluan..." required style="width: 100%; padding: 10px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none; resize: vertical;" onkeyup="updateSummary(); validateField(this)" onblur="validateField(this)" data-error-msg="Detail keperluan kunjungan wajib diisi."></textarea>
                        <small class="error-message" id="err_input_notes"></small>
                    </div>
                </div>

                {{-- STEP 3: RINGKASAN & KONFIRMASI --}}
                <div id="step-3-content" style="display: none;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 14px 0;">
                        3. Konfirmasi Data Check-In
                    </h4>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; margin-bottom: 12px; max-height: 280px; overflow-y: auto;">

                        <div id="sum_photo_container" style="display: none; justify-content: center; margin-bottom: 16px;">
                            <div style="text-align: center;">
                                <img id="sum_photo_preview" src="" alt="Pratinjau Foto Tamu" style="width: 80px; height: 80px; object-fit: cover; border-radius: 14px; border: 2px solid #006B3F; box-shadow: 0 4px 12px rgba(0,107,63,0.15);">
                                <span style="display: block; font-size: 11px; color: #778195; margin-top: 4px; font-weight: 700;">Foto Tamu</span>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Nama Tamu:</span>
                                <strong id="sum_name" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Instansi:</span>
                                <strong id="sum_company" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Alamat:</span>
                                <strong id="sum_address" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Jabatan:</span>
                                <strong id="sum_position" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">No. WhatsApp:</span>
                                <strong id="sum_phone" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Email:</span>
                                <strong id="sum_email" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Kategori Pengunjung:</span>
                                <strong id="sum_category" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Cabang:</span>
                                <strong id="sum_branch" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Tujuan PIC:</span>
                                <strong id="sum_pic" style="color: #006B3F; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Jenis Kunjungan:</span>
                                <strong id="sum_purpose" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Produk Minat:</span>
                                <strong id="sum_product" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Sumber Info:</span>
                                <strong id="sum_source" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Jadwal:</span>
                                <strong id="sum_schedule" style="color: #172033; text-align: right; max-width: 60%;"> -</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Keperluan:</span>
                                <strong id="sum_notes" style="color: #172033; text-align: right; max-width: 60%; word-break: break-all;">-</strong>
                            </div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                        <input type="checkbox" name="privacy_consent" id="manual_privacy_consent" value="1" required
                            style="width: 18px; height: 18px; accent-color: #006B3F; cursor: pointer; margin-top: 2px; flex-shrink: 0;">
                        <label for="manual_privacy_consent" style="font-size: 12px; color: #475569; line-height: 1.5; cursor: pointer;">
                            Saya menyetujui penggunaan data ini untuk keperluan pencatatan kunjungan dan tindak lanjut layanan IT Solution.
                        </label>
                    </div>

                    <p style="font-size: 12px; color: #94a3b8; margin: 0; text-align: center;">
                        Pastikan seluruh data di atas sudah benar sebelum menyimpan antrian.
                    </p>
                </div>
            </form>
        </div>

        <div style="padding: 16px 32px 24px 32px; border-top: 1px solid #e8edf5; background: #ffffff; display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" id="btnBatalModal" onclick="closeManualModal()" style="flex: 1; min-width: 90px; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>

            <button type="button" id="btnPrevStep" onclick="changeStep(-1)" style="flex: 1; min-width: 90px; display: none; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                ← Kembali
            </button>

            <button type="button" id="btnNextStep" onclick="changeStep(1)" style="flex: 2; min-width: 120px; background: #013220; color: #fff; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
                Lanjut →
            </button>

            <button type="button" id="btnSubmitForm" onclick="submitMultiStepForm()" style="flex: 2; min-width: 120px; display: none; background: #013220; color: #fff; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
                Simpan & Buat Antrian ✓
            </button>
        </div>

    </div>
</div>

{{-- MODAL KONFIRMASI PEMBATALAN --}}
<div id="cancelConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; padding: 28px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); text-align: center; box-sizing: border-box;">

        <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0 0 8px 0;">Batalkan Kunjungan?</h3>
        <p style="font-size: 13px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">
            Apakah Anda yakin ingin membatalkan jadwal kunjungan untuk <strong id="cancelGuestName" style="color: #172033;">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="closeCancelModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 11px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>
            <button type="button" id="confirmCancelSubmitBtn" style="flex: 1; background: #dc2626; color: #ffffff; border: none; padding: 11px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>

{{-- CDN JS FLATPICKR --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    let activeCancelVisitId = null;
    let currentStep = 1;
    let activeDateFilter = '{{ request('date_filter', 'all') }}';

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchApp');
        if (searchInput && searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        flatpickr("#input_scheduled_at", {
            locale: "id",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "j F Y, H:i",
            altInputClass: "flatpickr-custom-input",
            minDate: "today",
            minTime: "08:00",
            maxTime: "17:00",
            minuteIncrement: 15,
            disableMobile: "true",
            defaultDate: new Date(),
            static: true,
            disable: [
                function(date) {
                    return (date.getDay() === 0);
                }
            ],
            onChange: function() {
                validateField(document.getElementById('input_scheduled_at'));
                updateSummary();
            }
        });
    });

    // ================== VALIDASI REAL-TIME ==================
    // Menampilkan pesan error langsung ketika field kosong (required)
    // atau formatnya salah (pattern/type email & phone tidak sesuai).
    // Parameter isBlurEvent: true = validasi penuh (dipanggil saat blur/pindah field/submit),
    // false = dipanggil saat mengetik (oninput) -> pesan "format salah" DITUNDA sampai
    // user selesai mengetik (blur), supaya tidak muncul berulang kali saat nomor/email
    // belum selesai diketik. Error "kosong" & "kepanjangan" tetap muncul langsung.
    function validateField(field, isBlurEvent) {
        isBlurEvent = (isBlurEvent === undefined) ? true : isBlurEvent;
        const errorBox = document.getElementById('err_' + field.id);
        if (!errorBox) return true;

        const alreadyShowingError = field.classList.contains('field-error');

        let isValid = true;
        let message = '';

        // Cek kosong (required)
        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            message = field.dataset.errorMsg || 'Kolom ini wajib diisi.';
        }
        // Cek kepanjangan (melebihi maxlength) - dicek lebih dulu supaya pesannya spesifik
        else if (field.hasAttribute('maxlength') && field.value.trim().length > parseInt(field.getAttribute('maxlength'), 10)) {
            isValid = false;
            message = field.dataset.errorToolong || 'Input melebihi batas maksimal karakter.';
        }
        // Cek format (pattern / type email) jika sudah ada isinya
        else if (field.value.trim() && !field.checkValidity()) {
            // Hanya munculkan pesan "format salah" saat blur, atau jika error ini memang
            // sedang tampil (supaya bisa langsung hilang begitu user memperbaikinya).
            // Saat masih mengetik dan belum ada error tampil, jangan munculkan dulu.
            if (isBlurEvent || alreadyShowingError) {
                isValid = false;
                message = field.dataset.errorPattern || field.dataset.errorMsg || 'Format tidak valid.';
            }
        }

        if (!isValid) {
            field.classList.add('field-error');
            field.style.borderColor = '#dc2626';
            errorBox.textContent = message;
            errorBox.classList.add('show');
        } else {
            field.classList.remove('field-error');
            field.style.borderColor = '#e8edf5';
            errorBox.textContent = '';
            errorBox.classList.remove('show');
        }

        return isValid;
    }

    // 🟢 DYNAMIC FETCH PIC BERDASARKAN CABANG TERPILIH
    function loadPicsForModal(branchId, selectedPicId = null) {
        const picSelect = document.getElementById('select_pic');
        if (!branchId) return;

        picSelect.innerHTML = '<option value="" disabled selected>Memuat data PIC...</option>';

        fetch(`/get-pics-by-branch/${branchId}`)
            .then(response => response.json())
            .then(data => {
                picSelect.innerHTML = '';

                if (data.length === 0) {
                    picSelect.innerHTML = '<option value="" disabled selected>Tidak ada PIC di cabang ini</option>';
                } else {
                    picSelect.innerHTML = '<option value="" disabled selected>-- Pilih PIC --</option>';
                    data.forEach(pic => {
                        const option = document.createElement('option');
                        option.value = pic.id;
                        option.textContent = pic.name;

                        if (selectedPicId && selectedPicId == pic.id) {
                            option.selected = true;
                        }

                        picSelect.appendChild(option);
                    });
                }
                updateSummary();
            })
            .catch(error => {
                console.error('Error fetching PICs:', error);
                picSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data PIC</option>';
                updateSummary();
            });
    }

    function setDateFilter(filterType) {
        document.getElementById('dateFilterInput').value = filterType;
        document.getElementById('dashboardFilterForm').submit();
    }

    let searchTimeout;
    function handleSearchInput(inputElem) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            inputElem.form.submit();
        }, 600);
    }

    function validateFileSize(input) {
        const file = input.files[0];
        const errorElement = document.getElementById('fileError');
        const maxSizeBytes = 2 * 1024 * 1024; // 2 MB
        const previewImg = document.getElementById('sum_photo_preview');
        const previewContainer = document.getElementById('sum_photo_container');

        if (file) {
            if (file.size > maxSizeBytes) {
                errorElement.textContent = 'Ukuran file terlalu besar! Maksimal 2 MB.';
                errorElement.style.display = 'block';
                input.value = '';
                if (previewImg) previewImg.src = '';
                if (previewContainer) previewContainer.style.display = 'none';
            } else {
                errorElement.style.display = 'none';
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        } else {
            errorElement.style.display = 'none';
            if (previewImg) previewImg.src = '';
            if (previewContainer) previewContainer.style.display = 'none';
        }
    }

    function confirmCancel(visitId, guestName) {
        activeCancelVisitId = visitId;
        document.getElementById('cancelGuestName').innerText = guestName;
        document.getElementById('cancelConfirmModal').style.display = 'flex';
    }

    function closeCancelModal() {
        activeCancelVisitId = null;
        document.getElementById('cancelConfirmModal').style.display = 'none';
    }

    document.getElementById('confirmCancelSubmitBtn').addEventListener('click', function() {
        if (activeCancelVisitId) {
            document.getElementById('cancel-form-' + activeCancelVisitId).submit();
        }
    });

    function openAppointmentModal() {
        openManualModal();
    }

    function openManualModal() {
        currentStep = 1;
        updateStepUI();
        document.getElementById('manualModal').style.display = 'flex';
    }

    function closeManualModal() {
        document.getElementById('manualModal').style.display = 'none';
    }

    function changeStep(direction) {
        if (direction === 1 && !validateCurrentStep(currentStep)) {
            return;
        }

        currentStep += direction;

        if (currentStep < 1) currentStep = 1;
        if (currentStep > 3) currentStep = 3;

        updateStepUI();
    }

    // Validasi seluruh field wajib & format pada step aktif.
    // Semua error langsung ditampilkan (tidak berhenti di field pertama),
    // lalu fokus diarahkan ke field pertama yang tidak valid.
    function validateCurrentStep(step) {
        const currentContainer = document.getElementById(`step-${step}-content`);
        const fields = currentContainer.querySelectorAll('input, select, textarea');

        let firstInvalid = null;
        let allValid = true;

        fields.forEach(field => {
            const valid = validateField(field);
            if (!valid) {
                allValid = false;
                if (!firstInvalid) firstInvalid = field;
            }
        });

        if (firstInvalid) {
            firstInvalid.focus();
        }

        return allValid;
    }

    function updateStepUI() {
        const container = document.getElementById('modalFormContainer');
        if (container) {
            container.scrollTop = 0;
        }

        document.getElementById('step-1-content').style.display = 'none';
        document.getElementById('step-2-content').style.display = 'none';
        document.getElementById('step-3-content').style.display = 'none';

        document.getElementById(`step-${currentStep}-content`).style.display = 'block';

        document.getElementById('stepIndicatorText').innerText = `Langkah ${currentStep} dari 3`;

        document.getElementById('bar-step-1').style.background = currentStep >= 1 ? '#006B3F' : '#e2e8f0';
        document.getElementById('bar-step-2').style.background = currentStep >= 2 ? '#006B3F' : '#e2e8f0';
        document.getElementById('bar-step-3').style.background = currentStep >= 3 ? '#006B3F' : '#e2e8f0';

        document.getElementById('btnBatalModal').style.display = currentStep === 1 ? 'block' : 'none';
        document.getElementById('btnPrevStep').style.display = currentStep > 1 ? 'block' : 'none';
        document.getElementById('btnNextStep').style.display = currentStep < 3 ? 'block' : 'none';
        document.getElementById('btnSubmitForm').style.display = currentStep === 3 ? 'block' : 'none';

        if (currentStep === 3) {
            updateSummary();
        }
    }

    function updateSummary() {
        document.getElementById('sum_name').innerText = document.getElementById('input_name').value.trim() || '-';
        document.getElementById('sum_company').innerText = document.getElementById('input_company').value.trim() || '-';

        // 🆕 Field yang sebelumnya belum ditampilkan di ringkasan Step 3
        document.getElementById('sum_address').innerText = document.getElementById('input_address').value.trim() || '-';
        document.getElementById('sum_position').innerText = document.getElementById('input_position').value.trim() || '-';
        document.getElementById('sum_phone').innerText = document.getElementById('input_phone').value.trim() || '-';
        document.getElementById('sum_email').innerText = document.getElementById('input_email').value.trim() || '-';

        const categorySelect = document.getElementById('select_category');
        document.getElementById('sum_category').innerText = categorySelect.value ? categorySelect.options[categorySelect.selectedIndex].text : '-';

        const branchSelect = document.getElementById('select_branch');
        document.getElementById('sum_branch').innerText = branchSelect.value ? branchSelect.options[branchSelect.selectedIndex].text : '-';

        const picSelect = document.getElementById('select_pic');
        document.getElementById('sum_pic').innerText = picSelect.value ? picSelect.options[picSelect.selectedIndex].text : '-';

        const purposeSelect = document.getElementById('select_purpose');
        document.getElementById('sum_purpose').innerText = purposeSelect.value ? purposeSelect.options[purposeSelect.selectedIndex].text : '-';

        const productSelect = document.getElementById('select_product');
        document.getElementById('sum_product').innerText = productSelect.value ? productSelect.options[productSelect.selectedIndex].text : '-';

        const sourceSelect = document.getElementById('select_source');
        document.getElementById('sum_source').innerText = sourceSelect.value ? sourceSelect.options[sourceSelect.selectedIndex].text : '-';

        document.getElementById('sum_schedule').innerText = document.getElementById('input_scheduled_at').value || '-';
        document.getElementById('sum_notes').innerText = document.getElementById('input_notes').value.trim() || '-';
    }

    function submitMultiStepForm() {
        // Validasi ulang seluruh step sebelum submit, memastikan tidak ada
        // field wajib yang kosong atau formatnya salah walau tidak melewati step tsb secara manual.
        const step1Valid = validateCurrentStep(1);
        const step2Valid = validateCurrentStep(2);

        if (!step1Valid) {
            currentStep = 1;
            updateStepUI();
            validateCurrentStep(1);
            return;
        }

        if (!step2Valid) {
            currentStep = 2;
            updateStepUI();
            validateCurrentStep(2);
            return;
        }

        const consent = document.getElementById('manual_privacy_consent');
        if (!consent.checked) {
            consent.focus();
            alert('Anda harus menyetujui penggunaan data untuk melanjutkan.');
            return;
        }
        document.getElementById('multiStepForm').submit();
    }
</script>

@endsection