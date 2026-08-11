@extends('layouts.frontoffice')

@section('content')

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

    /* Styling Input Visual Flatpickr */
    .flatpickr-custom-input[readonly] {
        background-color: #fbfcfe !important;
        cursor: pointer !important;
        padding: 4px 8px !important;
        border: 1px solid #e8edf5 !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        color: #172033 !important;
        outline: none !important;
        width: 135px !important;
        max-width: 100% !important;
        transition: all 0.2s ease !important;
    }

    .flatpickr-custom-input:focus,
    .flatpickr-custom-input.active {
        border-color: #006B3F !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(0, 107, 63, 0.1) !important;
    }

    /* Styling Popup Kalender Flatpickr */
    .flatpickr-calendar {
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(31, 53, 97, 0.12) !important;
        border: 1px solid #e8edf5 !important;
        font-family: inherit !important;
        padding: 6px !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.selected:hover {
        background: #006B3F !important;
        border-color: #006B3F !important;
        font-weight: 700;
        border-radius: 8px !important;
    }

    .flatpickr-day:hover {
        border-radius: 8px !important;
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
        font-weight: 700 !important;
    }
</style>

{{-- Header Halaman --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Riwayat Kunjungan Tamu
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Arsip lengkap data tamu yang telah selesai melakukan kunjungan maupun dibatalkan.
        </p>
    </div>

    {{-- Filter Tanggal --}}
    <form action="{{ route('frontoffice.history') }}" method="GET" id="dateFilterForm" style="display: flex; gap: 6px; align-items: center; background: #ffffff; padding: 5px 10px; border-radius: 10px; border: 1px solid #e8edf5; box-shadow: 0 2px 8px rgba(31,53,97,0.03); flex-wrap: nowrap;">
        <span style="font-size: 11px; font-weight: 600; color: #64748b; white-space: nowrap;">Filter Tanggal:</span>

        <input type="text" id="filter_date" name="date" value="{{ $filterDate }}" placeholder="Pilih tanggal..." readonly>

        @if(!empty($filterDate))
        <a href="{{ route('frontoffice.history') }}" style="font-size: 10px; color: #dc2626; text-decoration: none; font-weight: 700; margin-left: 2px;">Clear</a>
        @endif
    </form>
</div>

{{-- Container Utama Tabel --}}
<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">

    {{-- Header Tabel & Search --}}
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Arsip Data Kunjungan</h3>

        <div style="width: 100%; max-width: 280px;">
            <input type="text" id="searchHistory" placeholder="Cari nama tamu / instansi / PIC..." onkeyup="filterHistoryTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 12px; font-weight: 600; color: #172033; outline: none; background: #ffffff; width: 100%; transition: all 0.2s ease; box-sizing: border-box;"
                onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
        </div>
    </div>

    {{-- Table Responsive Wrapper --}}
    <div class="table-responsive">
        <table id="historyTable" class="table align-middle" style="font-size: 13px; color: #172033; margin: 0; width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                    <th style="padding: 14px;">Token & Waktu</th>
                    <th style="padding: 14px;">Tamu & Jabatan</th>
                    <th style="padding: 14px;">Jenis Kunjungan</th>
                    <th style="padding: 14px;">Tujuan PIC</th>
                    <th style="padding: 14px;">Status Akhir</th>
                    <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi Detail</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $index => $visit)
                <tr class="history-row" style="border-bottom: 1px solid #f1f5f9;">
                    <td class="row-number" style="padding: 14px; font-weight: 600;">
                        {{ method_exists($visits, 'firstItem') && $visits->firstItem() ? $visits->firstItem() + $index : $index + 1 }}
                    </td>

                    {{-- Kode Token & Tanggal --}}
                    <td style="padding: 14px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">{{ $visit->visit_code ?? ('ANT-' . sprintf('%03d', $visit->queue_number)) }}</span>
                        <span style="font-size: 10px; color: #778195; font-weight: 600; display: inline-block; margin-top: 2px;">
                            {{ $visit->check_out_at ? \Carbon\Carbon::parse($visit->check_out_at)->translatedFormat('d M Y, H:i') : ($visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->translatedFormat('d M Y, H:i') : '-') }}
                        </span>
                    </td>

                    {{-- Identitas Tamu --}}
                    <td style="padding: 14px;" class="col-guest">
                        <strong style="display: block; color: #172033; font-weight: 800;">
                            {{ $visit->guest->name ?? '-' }}
                            @if(isset($visit->guest) && $visit->guest->is_vip)
                            <span title="VIP" style="color: #d97706;">⭐</span>
                            @endif
                        </strong>
                        <span style="font-size: 11px; color: #778195;">{{ $visit->guest->company_name ?? '-' }} ({{ $visit->guest->position ?? '-' }})</span>
                    </td>

                    {{-- Keperluan Kunjungan --}}
                    <td style="padding: 14px; color: #475569;">{{ $visit->purpose->name ?? '-' }}</td>

                    {{-- PIC Tujuan --}}
                    <td style="padding: 14px;" class="col-pic">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">
                            {{ $visit->assignedUser->name ?? '-' }}
                        </span>
                    </td>

                    {{-- STATUS AKHIR DINAMIS (Dibatalkan / Selesai) --}}
                    <td style="padding: 14px;">
                        @if(in_array(strtolower($visit->status), ['dibatalkan', 'cancelled']))
                        <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid #fecaca;">
                            Dibatalkan
                        </span>
                        @else
                        <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">
                            Selesai
                        </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td style="padding: 14px; text-align: center;">
                        <button type="button" onclick="openDetailModal(
                            '{{ $visit->visit_code ?? ('ANT-' . sprintf('%03d', $visit->queue_number)) }}', 
                            '{{ addslashes($visit->guest->name ?? '-') }}', 
                            '{{ addslashes($visit->guest->company_name ?? '-') }}', 
                            '{{ addslashes($visit->guest->position ?? '-') }}', 
                            '{{ $visit->guest->phone ?? '-' }}', 
                            '{{ addslashes($visit->purpose->name ?? '-') }}', 
                            '{{ addslashes($visit->assignedUser->name ?? '-') }}', 
                            '{{ $visit->check_in_at ? \Carbon\Carbon::parse($visit->check_in_at)->translatedFormat('d M Y, H:i') : '-' }}', 
                            '{{ $visit->check_out_at ? \Carbon\Carbon::parse($visit->check_out_at)->translatedFormat('d M Y, H:i') : '-' }}',
                            '{{ $visit->guest->photo_path ? asset('storage/' . $visit->guest->photo_path) : '' }}',
                            '{{ $visit->status }}'
                        )"
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" style="padding: 30px; text-align: center; color: #778195; font-weight: 600;">
                        Tidak ada data riwayat kunjungan.
                    </td>
                </tr>
                @endforelse

                <tr id="noSearchMatchRow" style="display: none;">
                    <td colspan="7" style="padding: 30px; text-align: center; color: #778195; font-weight: 600;">
                        🔍 Tidak ditemukan data kunjungan yang cocok dengan kata kunci pencarian.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination Component --}}
    <div style="margin-top: 20px;">
        @include('partials.pagination', ['paginator' => $visits])
    </div>
</div>

{{-- Modal Detail --}}
<div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 520px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; display: flex; flex-direction: column; box-sizing: border-box;">

        <div style="padding: 20px 24px; background: #fbfcfe; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Detail Kunjungan Tamu</h3>
            <button onclick="closeDetailModal()" style="background: none; border: none; font-size: 22px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <div class="custom-scroll" style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 14px; max-height: 70vh; overflow-y: auto;">
            <div style="display: flex; align-items: center; gap: 16px; background: #f8fafc; padding: 14px; border-radius: 14px; border: 1px solid #e8edf5;">
                <div id="photoContainer" style="display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #006B3F; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px;">
                        G
                    </div>
                </div>
                <div>
                    <span style="font-size: 11px; color: #778195; font-weight: 600; text-transform: uppercase; display: block;">Dokumentasi Wajah</span>
                    <span id="photoStatus" style="font-size: 13px; font-weight: 700; color: #172033;">Foto Tamu</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Nomor Token:</span>
                <span id="modalToken" style="font-weight: 800; color: #006B3F;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Status Kunjungan:</span>
                <span id="modalStatus" style="font-weight: 800;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Nama Lengkap:</span>
                <span id="modalName" style="font-weight: 700; text-align: right; max-width: 60%;"> -</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Asal Instansi & Jabatan:</span>
                <span id="modalInstansi" style="font-weight: 600; text-align: right; max-width: 60%;"> -</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Nomor WhatsApp:</span>
                <span id="modalPhone" style="font-weight: 600;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Jenis Kunjungan:</span>
                <span id="modalKeperluan" style="font-weight: 600; text-align: right; max-width: 60%;"> -</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Tujuan PIC Pegawai:</span>
                <span id="modalPic" style="font-weight: 600; color: #0369a1; text-align: right; max-width: 60%;"> -</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Waktu Check-in:</span>
                <span id="modalCheckin" style="font-weight: 600; font-size: 12px;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #778195;">Waktu Check-out:</span>
                <span id="modalCheckout" style="font-weight: 600; color: #006B3F; font-size: 12px;">-</span>
            </div>
        </div>

        <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; justify-content: flex-end;">
            <button onclick="closeDetailModal()" style="background: #172033; color: #fff; border: none; padding: 10px 20px; border-radius: 12px; font-size: 12px; font-weight: 700; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#filter_date", {
            locale: "id",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            altInputClass: "flatpickr-custom-input",
            disableMobile: "true",
            onChange: function(selectedDates, dateStr) {
                if (dateStr) {
                    document.getElementById('dateFilterForm').submit();
                }
            }
        });
    });

    function filterHistoryTable() {
        const input = document.getElementById('searchHistory');
        const filter = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.history-row');
        const noMatchRow = document.getElementById('noSearchMatchRow');
        let visibleIndex = 1;

        rows.forEach(row => {
            const guestText = row.querySelector('.col-guest')?.textContent || '';
            const picText = row.querySelector('.col-pic')?.textContent || '';
            const tdNum = row.querySelector('.row-number');

            if (guestText.toLowerCase().includes(filter) || picText.toLowerCase().includes(filter)) {
                row.style.display = "";
                if (tdNum) {
                    tdNum.textContent = visibleIndex;
                    visibleIndex++;
                }
            } else {
                row.style.display = "none";
            }
        });

        if (noMatchRow) {
            noMatchRow.style.display = (visibleIndex === 1 && rows.length > 0) ? "" : "none";
        }
    }

    function openDetailModal(token, name, instansi, jabatan, phone, keperluan, pic, checkin, checkout, photoUrl, status) {
        document.getElementById('modalToken').innerText = token;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalInstansi').innerText = instansi + ' (' + jabatan + ')';
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalKeperluan').innerText = keperluan;
        document.getElementById('modalPic').innerText = pic;
        document.getElementById('modalCheckin').innerText = checkin;
        document.getElementById('modalCheckout').innerText = checkout;

        const statusEl = document.getElementById('modalStatus');
        if (status && (status.toLowerCase() === 'dibatalkan' || status.toLowerCase() === 'cancelled')) {
            statusEl.innerText = 'Dibatalkan';
            statusEl.style.color = '#dc2626';
        } else {
            statusEl.innerText = 'Selesai (Check-out)';
            statusEl.style.color = '#006B3F';
        }

        const photoContainer = document.getElementById('photoContainer');
        const photoStatus = document.getElementById('photoStatus');

        if (photoUrl && photoUrl !== '') {
            photoContainer.innerHTML = `<img src="${photoUrl}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #006B3F;" alt="Foto Tamu">`;
            photoStatus.innerText = "Foto Terlampir";
        } else {
            const initial = name ? name.charAt(0).toUpperCase() : 'G';
            photoContainer.innerHTML = `<div style="width: 60px; height: 60px; border-radius: 50%; background: #006B3F; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px;">${initial}</div>`;
            photoStatus.innerText = "Foto Tidak Tersedia";
        }

        document.getElementById('detailModal').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
</script>

@endsection