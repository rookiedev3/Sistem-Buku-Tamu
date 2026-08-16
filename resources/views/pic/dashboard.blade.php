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
                <h4 class="fw-bold mb-1 text-white" style="font-size: 16px;">Selamat datang, {{ Auth::user()->name ?? 'PIC / Sales' }} </h4>
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

   input.rupiah-input:focus { border-color: #006B3F !important; box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important; }
    input.rupiah-input.is-invalid { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important; }
</style>

<script>
(function () {
    const panel = document.getElementById('dashboardPanel');
    const dashboardUrl = new URL('{{ route('pic.dashboard') }}', window.location.origin);

function toggleFollowUpRequirement(selectEl) {
    const id = selectEl.id.replace('potential_level-', '');
    const dateInput = document.getElementById('follow_up_at-' + id);
    const estGroup = document.getElementById('estimatedValueGroup-' + id);
    const estInput = document.getElementById('estimatedValueDisplay' + id); // <-- ambil input-nya juga

    const isDeal = selectEl.value === 'deal';
    const isDateOptional = ['cold', 'non_lead', 'deal'].includes(selectEl.value);
    const showEstValue = ['hot', 'warm', 'deal'].includes(selectEl.value);

    const form = selectEl.closest('form');
    const mark = form ? form.querySelector('.js-followup-required-mark') : null;
    const errorEl = form ? form.querySelector('.js-followup-date-error') : null;
    const dealMark = form ? form.querySelector('.js-deal-required-mark') : null;

    if (dateInput) {
        dateInput.disabled = isDateOptional;
        dateInput.placeholder = isDateOptional
            ? 'Tidak memerlukan follow up'
            : 'Pilih tanggal follow-up...';
        dateInput.style.background = isDateOptional ? '#f1f5f9' : '#fbfcfe';
        dateInput.style.cursor = isDateOptional ? 'not-allowed' : 'pointer';

        if (isDateOptional) {
            if (dateInput._flatpickr) {
                dateInput._flatpickr.clear();
            } else {
                dateInput.value = '';
            }
        }
    }

    if (mark) mark.style.display = isDateOptional ? 'none' : 'inline';

    if (isDateOptional && errorEl) {
        errorEl.style.display = 'none';
        if (dateInput) dateInput.style.borderColor = '#e8edf5';
    }

    if (estGroup) estGroup.style.display = showEstValue ? 'block' : 'none';
    if (dealMark) dealMark.style.display = isDeal ? 'inline' : 'none';

    // Field ini cuma bener-bener wajib pas Deal — dan yang penting,
    // JANGAN biarin "required" nempel pas field-nya disembunyikan (hot/warm/cold/non_lead),
    // soalnya required + hidden bikin form gagal submit tanpa pesan error yang keliatan.
    if (estInput) {
        estInput.required = isDeal;
        if (!showEstValue) {
            estInput.value = '';
            estInput.classList.remove('is-invalid');
            const hidden = document.getElementById(estInput.dataset.hiddenTarget);
            if (hidden) hidden.value = '';
        }
    }
}

// Formatter Rupiah, sama logic-nya kayak di pic.leads, tapi bisa dipanggil ulang
// tiap kali panel di-swap via AJAX (pakai flag data-rupiah-init biar gak double-bind)
function initRupiahInputs(scope) {
    scope.querySelectorAll('.rupiah-input:not([data-rupiah-init])').forEach(function (input) {
        input.dataset.rupiahInit = '1';
        const hidden = document.getElementById(input.dataset.hiddenTarget);

        input.addEventListener('input', function () {
            const raw = this.value.replace(/\D/g, '');
            this.value = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
            if (hidden) hidden.value = raw;
            this.classList.remove('is-invalid');
        });
    });
}

// Nyalain ulang flatpickr utk input follow_up_at di baris-baris yang baru di-load
function initRowWidgets() {
    panel.querySelectorAll('[id^="follow_up_at-"]').forEach(function (inputEl) {
        if (inputEl._flatpickr) return;
        flatpickr(inputEl, {
            locale: "id",
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: "true"
        });
    });

    // Nyalain formatter Rupiah utk input Estimasi Nilai di baris-baris yang baru di-load
    initRupiahInputs(panel);

    // Set kondisi awal tanda (*) & visibilitas Estimasi Nilai tiap kali panel
    // di-render/di-swap ulang, biar sinkron sama nilai potential_level yang
    // udah ke-set (misal dari old())
    panel.querySelectorAll('select[id^="potential_level-"]').forEach(function (select) {
        toggleFollowUpRequirement(select);
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

// Satu listener aja buat semua perubahan di dalam panel
panel.addEventListener('change', function (e) {
    if (e.target.matches('select[id^="potential_level-"]')) {
        toggleFollowUpRequirement(e.target);
        return;
    }

    if (!e.target.matches('select[data-role="vip-status"]')) return;

    const url = new URL(e.target.value, window.location.origin);
    loadDashboard(new URLSearchParams(url.search));
});

initRowWidgets();
})();
</script>
@endsection