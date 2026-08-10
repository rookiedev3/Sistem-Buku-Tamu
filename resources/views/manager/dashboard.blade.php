@extends('layouts.manager')

@section('content')

<div style="display: flex; flex-direction: column; gap: 24px;">

    <div id="welcomeBanner" class="card border-0 rounded-4 p-4 shadow-sm position-relative" style="background-color: #013220; color: white;">
        <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" aria-label="Close"></button>

        <div class="d-flex justify-content-between align-items-center pe-4">
            <div>
                <h4 class="fw-bold mb-1 text-white">Selamat datang, {{ Auth::user()->name ?? 'Pimpinan / Owner' }} 👋</h4>
                <p class="mb-0 text-white-50 fs-6">Pantau seluruh aktivitas kunjungan tamu secara real-time, kinerja PIC, dan progres konversi lead tim.</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; align-items: stretch;">
        
        <div style="background: #ffffff; padding: 18px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; color: #778195; font-weight: 700; text-transform: uppercase;">Total Tamu Hari Ini</span>
            <strong style="font-size: 22px; font-weight: 900; color: #1e3a8a; margin-top: 4px;">{{ $totalToday ?? 0 }} Orang</strong>
        </div>

        <div style="background: #ffffff; padding: 18px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; color: #778195; font-weight: 700; text-transform: uppercase;">Lead Deal Bulan Ini</span>
            <strong style="font-size: 22px; font-weight: 900; color: #013220; margin-top: 4px;">{{ $leadDealsCount ?? 0 }} Klien</strong>
        </div>

        <div style="background: #ffffff; padding: 14px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; color: #778195; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Filter Tanggal</span>
            <form action="{{ route('manager.dashboard') }}" method="GET" style="margin: 0;">
                <input type="text" id="selected_date" name="date" value="{{ $selectedDate }}" placeholder="Pilih tanggal..." readonly style="border: 1px solid #e8edf5; padding: 8px 12px; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; width: 100%; box-sizing: border-box;">
            </form>
        </div>

    </div>

    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden;">
        
        <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Monitoring Kunjungan & Status PIC</h3>
        </div>

        <div class="table-responsive">
            <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; margin: 0; min-width: 700px;">
                <thead>
                    <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                        <th style="padding: 14px 20px; font-weight: 700;">No</th>
                        <th style="padding: 14px 20px; font-weight: 700;">Token</th>
                        <th style="padding: 14px 20px; font-weight: 700;">Waktu </th>
                        <th style="padding: 14px 20px; font-weight: 700;">Tamu & Jabatan</th>
                        <th style="padding: 14px 20px; font-weight: 700;">Jenis Kunjungan</th>
                        <th style="padding: 14px 20px; font-weight: 700;">Keperluan</th>
                        <th style="padding: 14px 20px; font-weight: 700;">PIC / Sales</th>
                        <th style="padding: 14px 20px; font-weight: 700;">Check In</th>
                        <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody style="color: #172033;">
                    @forelse($visits as $index => $v)
                    <tr style="border-bottom: 1px solid #e8edf5;">
                        <td style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                        <td style="padding: 16px 20px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">
                                {{ $v->guest->name ?? '-' }}
                                @if(isset($v->guest) && $v->guest->is_vip)
                                    <span title="VIP" style="color: #d97706;">⭐</span>
                                @endif
                            </strong>
                            <span style="font-size: 11px; color: #778195;">{{ $v->guest->company_name ?? '-' }}</span>
                        </td>
                        <td style="padding: 16px 20px;">
                            @php
                                $catName  = $v->guest->category->name ?? 'Reguler';
                                $catColor = $v->guest->category->color ?? '#006B3F';
                            @endphp
                            <span style="background: {{ $catColor }}22; color: {{ $catColor }}; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">
                                {{ $catName }}
                            </span>
                        </td>
                        <td style="padding: 16px 20px; color: #475569; font-weight: 600;">{{ $v->assignedUser->name ?? '-' }} (PIC)</td>
                        <td style="padding: 16px 20px; color: #778195; font-weight: 600;">
                            {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->format('H:i') . ' WIB' : '-' }}
                        </td>
                        <td style="padding: 16px 20px; text-align: center;">
                            @php $statusLower = strtolower($v->status); @endphp
                            @if(in_array($statusLower, ['terjadwal']))
                                <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">Terjadwal</span>
                            @elseif(in_array($statusLower, ['menunggu', 'waiting', 'pending']))
                                <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">Menunggu</span>
                            @elseif(in_array($statusLower, ['sedang bertemu', 'confirmed', 'meeting']))
                                <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">Sedang Bertemu</span>
                            @elseif(in_array($statusLower, ['selesai', 'completed']))
                                <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">Selesai</span>
                            @else
                                <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">{{ $v->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #64748b; font-weight: 600;">
                            Belum ada data kunjungan tamu untuk hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px; flex-wrap: wrap; gap: 8px;">
            <span>Menampilkan data monitoring real-time</span>
            <span>Total Data: {{ $visits->count() }}</span>
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
            disableMobile: "true",
            defaultDate: "{{ $selectedDate }}",
            onChange: function(selectedDates, dateStr, instance) {
                instance.element.form.submit();
            }
        });
    });
</script>

@endsection