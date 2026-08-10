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

    /* Styling Input Visual Flatpickr (altInput) */
    .flatpickr-custom-input[readonly] {
        background-color: #fbfcfe !important;
        cursor: pointer !important;
        padding: 7px 12px !important;
        border: 1px solid #e8edf5 !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        color: #172033 !important;
        outline: none !important;
        width: 170px !important;
        max-width: 100% !important;
        transition: all 0.2s ease !important;
    }

    .flatpickr-custom-input:focus,
    .flatpickr-custom-input.active {
        border-color: #006B3F !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important;
    }

    /* Styling Popup Kalender Flatpickr */
    .flatpickr-calendar { 
        border-radius: 16px !important; 
        box-shadow: 0 12px 32px rgba(31, 53, 97, 0.15) !important; 
        border: 1px solid #e8edf5 !important; 
        font-family: inherit !important; 
        padding: 10px !important; 
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
            Arsip lengkap data tamu yang telah selesai melakukan kunjungan dan check-out dari sistem.
        </p>
    </div>

    {{-- Filter Tanggal Menggunakan Flatpickr --}}
    <form action="{{ route('frontoffice.history') }}" method="GET" id="dateFilterForm" style="display: flex; gap: 10px; align-items: center; background: #ffffff; padding: 8px 14px; border-radius: 12px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03); flex-wrap: wrap;">
        <span style="font-size: 12px; font-weight: 600; color: #64748b;">Filter Tanggal:</span>
        
        <input type="text" id="filter_date" name="date" value="{{ $filterDate }}" placeholder="Pilih tanggal..." readonly>

        @if(!empty($filterDate))
        <a href="{{ route('frontoffice.history') }}" style="font-size: 11px; color: #dc2626; text-decoration: none; font-weight: 600; margin-left: 4px;">Clear</a>
        @endif
    </form>
</div>

{{-- Container Utama Tabel --}}
<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Arsip Data Kunjungan Selesai</h3>
        
        <div style="width: 100%; sm:width: auto;">
            <input type="text" id="searchHistory" placeholder="Cari nama tamu / instansi / PIC..." onkeyup="filterHistoryTable()"
                style="padding: 9px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #ffffff; width: 100%; sm:width: 260px; transition: all 0.2s ease; box-sizing: border-box;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="historyTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 900px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Token & Waktu Check-out</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Jenis Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status Akhir</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi Detail</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $index => $visit)
                <tr class="history-row" style="border-bottom: 1px solid #e8edf5;">
                    {{-- Penomoran Dinamis --}}
                    <td style="padding: 16px 20px; font-weight: 600; color: #64748b;">
                        {{ method_exists($visits, 'firstItem') && $visits->firstItem() ? $visits->firstItem() + $index : $index + 1 }}
                    </td>

                    {{-- Kode Token & Tanggal Check-out --}}
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">{{ $visit->visit_code ?? ('ANT-' . sprintf('%03d', $visit->queue_number)) }}</span>
                        <span style="font-size: 11px; color: #778195;">
                            {{ $visit->check_out_at ? \Carbon\Carbon::parse($visit->check_out_at)->translatedFormat('d M Y, H:i') : ($visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->translatedFormat('d M Y, H:i') : '-') }}
                        </span>
                    </td>

                    {{-- Identitas Tamu --}}
                    <td style="padding: 16px 20px;" class="col-guest">
                        <div style="font-weight: 700; color: #172033;">{{ $visit->guest->name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $visit->guest->company_name ?? '-' }} ({{ $visit->guest->position ?? '-' }})</div>
                    </td>

                    {{-- Jenis / Keperluan Kunjungan --}}
                    <td style="padding: 16px 20px;">{{ $visit->purpose->name ?? '-' }}</td>

                    {{-- PIC Tujuan --}}
                    <td style="padding: 16px 20px;" class="col-pic">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            {{ $visit->assignedUser->name ?? '-' }}
                        </span>
                    </td>

                    {{-- Status Akhir --}}
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Selesai (Check-out)
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td style="padding: 16px 20px; text-align: center;">
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
                            '{{ $visit->guest->photo_path ? asset('storage/' . $visit->guest->photo_path) : '' }}'
                        )" 
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" style="padding: 40px 20px; text-align: center; color: #64748b;">
                        <div style="font-size: 24px; margin-bottom: 8px;"></div>
                        <div style="font-weight: 600;">Tidak ada data riwayat kunjungan selesai.</div>
                    </td>
                </tr>
                @endforelse

                <tr id="noSearchMatchRow" style="display: none;">
                    <td colspan="7" style="padding: 40px 20px; text-align: center; color: #64748b;">
                        <div style="font-size: 24px; margin-bottom: 8px;">🔍</div>
                        <div style="font-weight: 600;">Tidak ditemukan data kunjungan yang cocok dengan kata kunci pencarian.</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Ringkasan Jumlah Data --}}
    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px; flex-wrap: wrap; gap: 8px;">
        <span>Menampilkan arsip kunjungan selesai</span>
        <span>Total: <strong>{{ method_exists($visits, 'total') ? $visits->total() : $visits->count() }}</strong> Kunjungan</span>
    </div>

</div>

{{-- 🟢 PAGINATION LINK --}}
@if(method_exists($visits, 'hasPages') && $visits->hasPages())
    <div style="margin-top: 20px;">
        @include('partials.pagination', ['paginator' => $visits])
    </div>
@endif

<div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 520px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; display: flex; flex-direction: column; box-sizing: border-box;">
        
        <div style="padding: 20px 24px; background: #fbfcfe; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Detail Kunjungan Tamu </h3>
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
                <span id="modalCheckin" style="font-weight: 600;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #778195;">Waktu Check-out:</span>
                <span id="modalCheckout" style="font-weight: 600; color: #006B3F;">-</span>
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
    // Inisialisasi Flatpickr Filter Tanggal
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

    // Pencarian Dinamis Real-Time
    function filterHistoryTable() {
        const input = document.getElementById('searchHistory');
        const filter = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.history-row');
        const noMatchRow = document.getElementById('noSearchMatchRow');
        let visibleCount = 0;

        rows.forEach(row => {
            const guestText = row.querySelector('.col-guest')?.textContent || '';
            const picText = row.querySelector('.col-pic')?.textContent || '';
            
            if (guestText.toLowerCase().includes(filter) || picText.toLowerCase().includes(filter)) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? "" : "none";
        }
    }

    // Modal Detail Functions
    function openDetailModal(token, name, instansi, jabatan, phone, keperluan, pic, checkin, checkout, photoUrl) {
        document.getElementById('modalToken').innerText = token;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalInstansi').innerText = instansi + ' (' + jabatan + ')';
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalKeperluan').innerText = keperluan;
        document.getElementById('modalPic').innerText = pic;
        document.getElementById('modalCheckin').innerText = checkin;
        document.getElementById('modalCheckout').innerText = checkout;

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