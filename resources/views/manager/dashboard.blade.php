@extends('layouts.manager')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            MANAGER MONITORING
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Dashboard Monitoring Manager 📊
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Pantau seluruh aktivitas kunjungan tamu secara real-time, kinerja PIC, dan progres konversi lead tim.
        </p>
    </div>

    <!-- Statistik Dinamis dari Database -->
    <div style="display: flex; gap: 12px;">
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Total Tamu Hari Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #1e3a8a;">{{ $totalToday ?? 0 }} Orang</span>
        </div>
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Lead Deal Bulan Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #006B3F;">{{ $leadDealsCount ?? 0 }} Klien</span>
        </div>
        <!-- Form Filter Tanggal dengan Auto-Submit -->
        <form action="{{ route('manager.dashboard') }}" method="GET" style="display: flex; gap: 8px; align-items: center; background: #ffffff; padding: 8px 12px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <input type="text" id="selected_date" name="date" value="{{ $selectedDate }}" placeholder="Pilih tanggal..." readonly style="border: 1px solid #e8edf5; padding: 6px 10px; border-radius: 8px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; width: 170px;">
        </form>
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Monitoring Kunjungan & Status PIC</h3>
        
    </div>

    <div style="overflow-x: auto;">
        <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; margin: 0;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Nama Tamu & Instansi</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Kategori</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan (PIC)</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Waktu Masuk</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $index => $v)
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px;">
                        <strong style="display: block; color: #172033; font-weight: 800;">{{ $v->guest->name ?? '-' }}</strong>
                        <span style="font-size: 11px; color: #778195;">{{ $v->guest->company_name ?? '-' }}</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="padding: 16px 20px; color: #475569; font-weight: 600;">
                             {{ $v->guest->category->name ?? 'Reguler' }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px; color: #475569; font-weight: 600;">{{ $v->assignedUser->name ?? '-' }} (PIC)</td>
                    <td style="padding: 16px 20px; color: #778195; font-weight: 600;">
                        {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->format('H:i') . ' WIB' : '-' }}
                    </td>
<td style="padding: 16px 20px; text-align: center;">
    @if(in_array($v->status, ['Terjadwal', 'scheduled']))
        <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
            Terjadwal
        </span>
    @elseif(in_array($v->status, ['Menunggu', 'waiting']))
        <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
            Menunggu
        </span>
    @elseif(in_array($v->status, ['Sedang Bertemu', 'confirmed']))
        <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
            Sedang Bertemu
        </span>
    @elseif(in_array($v->status, ['Selesai', 'completed']))
        <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
            Selesai
        </span>
    @else
        <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
            {{ $v->status }}
        </span>
    @endif
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b;">
                        Belum ada data kunjungan tamu untuk hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan data monitoring real-time</span>
        <span>Total Data: {{ $visits->count() }}</span>
    </div>

</div>

<!-- CDN CSS Flatpickr -->
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

<!-- CDN JS Flatpickr & Bahasa Indonesia -->
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