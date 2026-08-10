@extends('layouts.manager')

@section('content')

<div style="display: flex; flex-direction: column; gap: 16px;">

    <div id="welcomeBanner" class="border-0 rounded-3 position-relative" style="background-color: #013220; color: white; padding: 16px 20px;">
        <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" aria-label="Close" style="transform: scale(0.75);"></button>

        <div class="d-flex justify-content-between align-items-center pe-4">
            <div>
                <h4 class="fw-bold mb-1 text-white" style="font-size: 16px;">Selamat datang, {{ Auth::user()->name ?? 'Pimpinan / Owner' }} 👋</h4>
                <p class="mb-0 text-white-50" style="font-size: 12px;">Pantau seluruh aktivitas kunjungan tamu secara real-time, kinerja PIC, dan progres konversi lead tim.</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 10px; color: #778195; font-weight: 700; text-transform: uppercase;">Total Tamu Hari Ini</span>
            <strong style="font-size: 19px; font-weight: 900; color: #1e3a8a; margin-top: 2px;">{{ $totalToday ?? 0 }} Orang</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 10px; color: #778195; font-weight: 700; text-transform: uppercase;">Lead Deal Bulan Ini</span>
            <strong style="font-size: 19px; font-weight: 900; color: #013220; margin-top: 2px;">{{ $leadDealsCount ?? 0 }} Klien</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center;">
    <span style="font-size: 10px; color: #778195; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Filter Data</span>
    <form action="{{ route('manager.dashboard') }}" method="GET" style="margin: 0; display: flex; gap: 6px;">
        <select name="vip_status" onchange="this.form.submit()" style="height: 32px; border: 1px solid #e8edf5; padding: 0 8px; border-radius: 6px; font-size: 11px; font-weight: 700; color: #172033; background: #fff; outline: none; cursor: pointer; flex: 0 0 40%; box-sizing: border-box;">
            @php
                $vipOptions = ['all' => 'Semua Status', 'vip' => '⭐ VIP', 'reguler' => 'Reguler'];
                $activeVipFilter = $vipFilter ?? 'all';
            @endphp
            @foreach($vipOptions as $key => $label)
                <option value="{{ $key }}" {{ $activeVipFilter === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="text" id="selected_date" name="date" value="{{ $selectedDate }}" placeholder="Pilih tanggal..." readonly style="flex: 0 0 60%;">
    </form>
</div>

    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden;">

        <div style="padding: 14px 16px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
            <h3 style="font-size: 14px; font-weight: 800; color: #172033; margin: 0;">Monitoring Kunjungan & Status PIC</h3>
        </div>

        <div class="table-responsive">
            <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 12px; margin: 0; min-width: 700px;">
                <thead>
                    <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                        <th style="padding: 8px 10px; font-weight: 700;">No</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Token</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Tamu & Jabatan</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Waktu</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Jenis Kunjungan</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Keperluan</th>
                        <th style="padding: 8px 10px; font-weight: 700;">PIC / Sales</th>
                        <th style="padding: 8px 10px; font-weight: 700; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody style="color: #172033;">
                    @forelse($visits as $index => $v)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 8px 10px; font-weight: 700;">{{ $index + 1 }}</td>

                        <td style="padding: 8px 10px;">
                            <strong style="color: #006B3F; font-weight: 800;">
                                {{ $v->visit_code ?? ('VST-' . str_pad($v->id, 4, '0', STR_PAD_LEFT)) }}
                            </strong>
                        </td>

                        <td style="padding: 8px 10px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">
                                {{ $v->guest->name ?? '-' }}
                                @if(isset($v->guest) && $v->guest->is_vip)
                                    <span title="VIP" style="color: #d97706;">⭐</span>
                                @endif
                            </strong>
                            <span style="font-size: 10px; color: #778195;">
                                {{ $v->guest->company_name ?? '-' }} ({{ $v->guest->position ?? '-' }})
                            </span>
                        </td>

                        <td style="padding: 8px 10px; color: #778195; font-weight: 600;">
                            {{ $v->scheduled_at ? \Carbon\Carbon::parse($v->scheduled_at)->format('H:i') . ' WIB' : '-' }}
                        </td>

                        <td style="padding: 8px 10px;">
                            @php
                                $catName  = $v->guest->category->name ?? 'Reguler';
                                $catColor = $v->guest->category->color ?? '#006B3F';
                            @endphp
                            <span style="background: {{ $catColor }}22; color: {{ $catColor }}; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 800;">
                                {{ $catName }}
                            </span>
                        </td>

                        <td style="padding: 8px 10px; color: #475569;">
                            {{ $v->purpose->name ?? '-' }}
                        </td>

                        <td style="padding: 8px 10px; color: #475569; font-weight: 600;">
                            {{ $v->assignedUser->name ?? '-' }}
                        </td>

                        <td style="padding: 8px 10px; text-align: center;">
                            @php $statusLower = strtolower(trim($v->status ?? '')); @endphp
                            @if(in_array($statusLower, ['terjadwal']))
                                <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Terjadwal</span>
                            @elseif(in_array($statusLower, ['menunggu', 'waiting', 'pending']))
                                <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Menunggu</span>
                            @elseif(in_array($statusLower, ['dikonfirmasi', 'confirmed']))
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Dikonfirmasi</span>
                            @elseif(in_array($statusLower, ['sedang bertemu', 'meeting']))
                                <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Sedang Bertemu</span>
                            @elseif(in_array($statusLower, ['selesai', 'completed', 'meeting selesai']))
                                <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Selesai</span>
                            @elseif(in_array($statusLower, ['dibatalkan', 'cancelled', 'ditolak']))
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Dibatalkan</span>
                            @else
                                <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">{{ $v->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 20px; text-align: center; color: #94a3b8; font-size: 12px;">
                            Belum ada data kunjungan tamu untuk tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 10px 16px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 11px; flex-wrap: wrap; gap: 8px;">
            <span>Menampilkan data monitoring real-time</span>
            <span>Total Data: {{ $visits->count() }}</span>
        </div>

    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* Kalender diperkecil - disamakan dengan pic/riwayat & manager/kunjungan supaya rapi & tidak kebesaran */
    .flatpickr-calendar {
        width: 230px !important;
        padding: 6px !important;
        border-radius: 10px !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
        border: 1px solid #e8edf5 !important;
        font-family: inherit !important;
    }
    .flatpickr-months {
        height: 28px !important;
    }
    .flatpickr-current-month {
        font-size: 12px !important;
        padding-top: 2px !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 700 !important;
        padding: 0 2px !important;
    }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        height: 28px !important;
        padding: 4px !important;
    }
    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg {
        width: 10px !important;
        height: 10px !important;
    }
    span.flatpickr-weekday {
        color: #778195 !important;
        font-weight: 600 !important;
        font-size: 10px !important;
    }
    .flatpickr-day {
        max-width: 28px !important;
        height: 28px !important;
        line-height: 28px !important;
        font-size: 11px !important;
        border-radius: 6px !important;
    }
    .flatpickr-day.selected,
    .flatpickr-day.selected:focus,
    .flatpickr-day.selected:hover {
        background: #006B3F !important;
        border-color: #006B3F !important;
        font-weight: 700;
    }
.flatpickr-custom-input[readonly] {
    height: 32px !important;
    width: 100% !important;
    padding: 4px 10px !important;
    border: 1px solid #e8edf5 !important;
    border-radius: 6px !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #172033 !important;
    background-color: #fbfcfe !important;
    outline: none !important;
    cursor: pointer !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
}

.flatpickr-custom-input:focus,
.flatpickr-custom-input.active {
    border-color: #006B3F !important;
    background-color: #ffffff !important;
    box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important;
}
    #selected_date:focus {
        border-color: #006B3F !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#selected_date", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        altInputClass: "flatpickr-custom-input",
        disableMobile: "true",
        defaultDate: "{{ $selectedDate }}",
        onChange: function(selectedDates, dateStr, instance) {
            instance.element.form.submit();
        }
    });

    // pastikan wrapper alt-input ikut flex-basis 60% seperti select di sebelahnya
    const altInput = document.querySelector('#selected_date').nextElementSibling;
    if (altInput && altInput.classList.contains('flatpickr-custom-input')) {
        altInput.style.flex = '0 0 60%';
    }
});
</script>

@endsection