@extends('layouts.security')

@section('content')

@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 20px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div id="welcomeBanner" class="card border-0 rounded-4 p-4 mb-4 shadow-sm position-relative" style="background-color: #013220; color: white;">
    <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" aria-label="Close"></button>

    <div class="d-flex justify-content-between align-items-center pe-4">
        <div>
            <h4 class="fw-bold mb-1 text-white">Selamat datang, {{ Auth::user()->name ?? 'Petugas Security' }} 👋</h4>
            <p class="mb-0 text-white-50 fs-6">Berikut adalah daftar tamu yang masuk dan keluar berdasarkan tanggal penjagaan.</p>
        </div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">
            Daftar Tamu 📋
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Pantau seluruh log aktivitas check-in dan check-out tamu secara real-time.
        </p>
    </div>

    <form action="{{ route('security.dashboard') }}" method="GET" style="display: flex; gap: 8px; align-items: center; background: #ffffff; padding: 8px 12px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
        <input type="text" id="selected_date" name="date" value="{{ $selectedDate }}" placeholder="Pilih tanggal..." readonly style="border: 1px solid #e8edf5; padding: 6px 10px; border-radius: 8px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; width: 170px;">
    </form>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">

    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Log Aktivitas Tamu</h3>
    </div>

    <div style="overflow-x: auto;">
        <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; margin: 0;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Nama Tamu & Instansi</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan (PIC)</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Waktu Check-in</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Waktu Check-out</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status Kehadiran</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $index => $v)
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px; font-weight: 700;">{{ $visits->firstItem() + $index }}</td>
                    <td style="padding: 16px 20px;">
                        <strong style="display: block; color: #172033; font-weight: 800;">
                            {{ $v->guest->name ?? '-' }}
                            @if(isset($v->guest) && $v->guest->is_vip)
                                <span title="VIP" style="color: #d97706;">⭐</span>
                            @endif
                        </strong>
                        <span style="font-size: 11px; color: #778195;">{{ $v->guest->company_name ?? '-' }}</span>
                    </td>
                    <td style="padding: 16px 20px; color: #475569; font-weight: 600;">{{ $v->assignedUser->name ?? '-' }}</td>
                    
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700; color: #172033;">
                            {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->format('H:i') . ' WIB' : '-' }}
                        </div>
                        <div style="font-size: 11px; color: #778195; margin-top: 2px;">Check-in</div>
                    </td>

                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700; color: #172033;">
                            {{ $v->check_out_at ? \Carbon\Carbon::parse($v->check_out_at)->format('H:i') . ' WIB' : '-' }}
                        </div>
                        <div style="font-size: 11px; color: #778195; margin-top: 2px;">Check-out</div>
                    </td>

                    <td style="padding: 16px 20px;">
                        <div>
                            @if($v->check_out_at)
                                <span style="background: #f1f5f9; color: #64748b; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-block;">Selesai / Keluar</span>
                            @elseif($v->check_in_at)
                                <span style="background: #e6f4ed; color: #006B3F; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-block;">Sedang Meeting</span>
                            @else
                                <span style="background: #fef3c7; color: #b45309; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-block;">Belum Masuk</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b;">Tidak ada data tamu pada tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe;">
        @include('partials.pagination', ['paginator' => $visits])
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
            maxDate: "today",
            defaultDate: "{{ $selectedDate }}",
            onChange: function(selectedDates, dateStr, instance) {
                instance.element.form.submit();
            }
        });
    });
</script>

@endsection