@extends('layouts.pic')

@section('content')

@php
    $visits = $visits ?? collect();
    $vipCount = $vipCount ?? 0;
    $regularCount = $regularCount ?? 0;
@endphp

<div style="display: flex; flex-direction: column; gap: 16px;">

    @if(session('success'))
    <div class="alert alert-success" style="border-radius: 10px; font-weight: 600; font-size: 13px; padding: 10px 14px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600; font-size: 13px; padding: 10px 14px;">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger" style="border-radius: 10px; font-weight: 600; font-size: 13px; padding: 10px 14px;">
        <ul style="margin:0; padding-left: 18px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div id="welcomeBanner" class="border-0 rounded-3 position-relative" style="background-color: #013220; color: white; padding: 16px 20px;">
        <button type="button" onclick="document.getElementById('welcomeBanner').style.display='none';" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" aria-label="Close" style="transform: scale(0.75);"></button>

        <div class="d-flex justify-content-between align-items-center pe-4">
            <div>
                <h4 class="fw-bold mb-1 text-white" style="font-size: 16px;">Selamat datang, {{ Auth::user()->name ?? 'PIC / Sales' }} 👋</h4>
                <p class="mb-0 text-white-50" style="font-size: 12px;">Kelola daftar tamu berdasarkan kategori VIP & Reguler, konfirmasi kehadiran, catat hasil pertemuan, dan pantau konversi lead.</p>
            </div>
        </div>
    </div>

    {{-- Panel ini yang di-swap via AJAX saat filter/pagination --}}
    <div id="dashboardPanel">
        @include('pic.partials._dashboard_panel')
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<style>
    .flatpickr-calendar {
        border-radius: 14px !important;
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
        font-weight: 600 !important;
    }
</style>

<script>
(function () {
    const panel = document.getElementById('dashboardPanel');
    const dashboardUrl = new URL('{{ route('pic.dashboard') }}', window.location.origin);

    // Nyalain ulang flatpickr utk input follow_up_at di baris-baris yang baru di-load
    function initRowWidgets() {
        panel.querySelectorAll('[id^="follow_up_at-"]').forEach(function (inputEl) {
            if (inputEl._flatpickr) return; // sudah pernah di-init
            flatpickr(inputEl, {
                locale: "id",
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: "true"
            });
        });
    }

    function loadDashboard(params, pushState = true) {
        const url = dashboardUrl.pathname + '?' + params.toString();

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.text(); })
            .then(function (html) {
                panel.innerHTML = html;
                initRowWidgets();
                if (pushState) {
                    window.history.pushState({}, '', url);
                }
            });
    }

    // Klik tombol filter (Semua / Hari Ini / Terjadwal Mendatang) & tautan pagination -> AJAX, bukan reload
    panel.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const url = new URL(link.href, window.location.origin);
        if (url.pathname !== dashboardUrl.pathname) return; // biarkan tautan lain jalan normal

        e.preventDefault();
        loadDashboard(new URLSearchParams(url.search));
    });

    // Ganti dropdown Status VIP/Reguler -> AJAX, bukan reload
    panel.addEventListener('change', function (e) {
        if (!e.target.matches('select[data-role="vip-status"]')) return;

        const url = new URL(e.target.value, window.location.origin);
        loadDashboard(new URLSearchParams(url.search));
    });

    initRowWidgets();
})();
</script>
@endsection