@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 16px;">

    <!-- Bagian Header & Filter -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 14px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div>
                <h2 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 2px;">Riwayat Kunjungan Tamu 📋</h2>
            </div>
        </div>

        {{-- Semua elemen filter di sini pakai tinggi (38px), padding, font-size, dan border-radius yang sama --}}
        <form action="{{ route('pic.riwayat') }}" method="GET" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 180px;">
        <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 5px; text-transform: uppercase;">Cari Nama / Instansi / PIC</label>
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Contoh: Budi atau Siska..." style="width: 100%; height: 38px; padding: 8px 12px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; box-sizing: border-box;">
    </div>

    <div style="width: 145px;">
        <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 5px; text-transform: uppercase;">Dari Tanggal</label>
        <input type="text" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Pilih tanggal..." readonly>
    </div>

    <div style="width: 145px;">
        <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 5px; text-transform: uppercase;">Sampai Tanggal</label>
        <input type="text" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="Pilih tanggal..." readonly>
    </div>

    <div style="width: 160px;">
        <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 5px; text-transform: uppercase;">Status</label>
        <div style="position: relative; width: 100%;">
            <select name="vip_status" style="width: 100%; height: 38px; padding: 8px 30px 8px 12px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; font-weight: 700; color: #172033; background-color: #fff; outline: none; cursor: pointer; box-sizing: border-box; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                @php
                    $vipOptions = ['all' => 'Semua Status', 'vip' => '⭐ VIP', 'reguler' => 'Reguler'];
                    $activeVipFilter = $vipFilter ?? 'all';
                @endphp
                @foreach($vipOptions as $key => $label)
                    <option value="{{ $key }}" {{ $activeVipFilter === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #5c6678; display: flex; align-items: center;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 8px;">
        <button type="submit" style="height: 38px; background: #013220; color: #fff; border: none; padding: 0 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
            Filter
        </button>
        @if(request()->hasAny(['keyword', 'start_date', 'end_date']) || request('vip_status', 'all') !== 'all')
            <a href="{{ route('pic.riwayat') }}" style="height: 38px; background: #f1f5f9; color: #475569; text-decoration: none; padding: 0 14px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center;">
                Reset
            </a>
        @endif
    </div>
</form>
    </div>

    <!-- Tabel Arsip Riwayat Kunjungan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 14px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 14px; font-weight: 800; color: #172033; margin-bottom: 12px;">Daftar Arsip Kunjungan</h3>

@if(request()->hasAny(['keyword', 'start_date', 'end_date']) || request('vip_status', 'all') !== 'all')
<div style="background-color: #d4edda; color: #155724; padding: 8px 10px; border-radius: 8px; font-size: 11px; margin-bottom: 12px; border: 1px solid #c3e6cb;">
    Menampilkan hasil filter
    @if(request('keyword')) untuk "<strong>{{ request('keyword') }}</strong>" @endif
    @if(request('start_date')) dari <strong>{{ tgl(request('start_date')) }}</strong> @endif
    @if(request('end_date')) sampai <strong>{{ tgl(request('end_date')) }}</strong> @endif
    @if(request('vip_status', 'all') !== 'all') status <strong>{{ request('vip_status') === 'vip' ? 'VIP' : 'Reguler' }}</strong> @endif
    — {{ $visits->total() }} data ditemukan.
</div>
@endif

        @php
            $leadBadges = [
                'new'         => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Baru'],
                'contacted'   => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Dihubungi'],
                'negotiation' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'Negosiasi 🔥'],
                'deal'        => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Deal 🎉'],
                'lost'        => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Lost'],
            ];
        @endphp

        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 12px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 8px 10px;">No</th>
                        <th style="padding: 8px 10px;">Token</th>
                        <th style="padding: 8px 10px;">Tamu & Jabatan</th>
                        <th style="padding: 8px 10px;">Tanggal & Waktu</th>
                        <th style="padding: 8px 10px;">Keperluan</th>
                        <th style="padding: 8px 10px; text-align: center;">Catatan</th>
                        <th style="padding: 8px 10px; text-align: center;">Status Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $index => $v)
                    @php
                        $statusLower = strtolower(trim($v->status ?? ''));
                        // "cancelled" / "ditolak" / "dibatalkan" semuanya dianggap kunjungan yang DIBATALKAN.
                        $isCancelled = in_array($statusLower, ['cancelled', 'ditolak', 'dibatalkan']);
                        // Selain dibatalkan, dan statusnya termasuk status final -> dianggap SELESAI (boleh ada catatan pertemuan).
                        $isCompleted = !$isCancelled && in_array($statusLower, ['completed', 'selesai', 'meeting selesai']);
                        $leadStatus = optional($v->lead)->status; // null kalau visit ini cold/non_lead (tidak dikonversi)
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 8px 10px; font-weight: 700;">{{ $visits->firstItem() + $index }}</td>

                        {{-- TOKEN: diambil dari data kunjungan ($v) itu sendiri, bukan $lead/$visit dari halaman lain --}}
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
                            {{-- Ditambah perusahaan, meniru frontoffice/dashboard: "Instansi (Jabatan)" --}}
                            <span style="font-size: 10px; color: #778195;">
                                {{ $v->guest->company_name ?? '-' }} ({{ $v->guest->position ?? '-' }})
                            </span>
                        </td>

                        <td style="padding: 8px 10px; color: #778195; font-weight: 600;">
                            @php
                                // Kunjungan yang dibatalkan seringkali tidak sempat check-in,
                                // jadi tanggal fallback ke scheduled_at supaya tetap tampil (bukan '-').
                                $displayDate = $v->check_in_at ?? $v->scheduled_at;
                            @endphp
                            {{ $displayDate ? \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') : '-' }}<br>
                            <span style="font-size: 10px;">{{ $displayDate ? \Carbon\Carbon::parse($displayDate)->format('H:i') . ' WIB' : '' }}</span>
                        </td>

                        <td style="padding: 8px 10px; color: #475569;">
                            {{ $v->purpose->name ?? $v->purpose }}
                        </td>

                        <!-- Kolom Tombol Modal Catatan -->
                        <td style="padding: 8px 10px; text-align: center;">
                            @if($isCompleted)
                                <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $v->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 4px 10px; border-radius: 7px; font-size: 10px; font-weight: 700; cursor: pointer;">
                                    📝 Lihat Catatan
                                </button>
                            @else
                                <span style="font-style: italic; color: #94a3b8; font-size: 11px;">Dibatalkan / Belum Selesai</span>
                            @endif
                        </td>

                        <!-- Kolom Status Akhir -->
                        <td style="padding: 8px 10px; text-align: center;">
                            @if($isCancelled)
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">Dibatalkan</span>
                            @elseif($isCompleted && $leadStatus)
                                @php $b = $leadBadges[$leadStatus] ?? $leadBadges['new']; @endphp
                                <span style="background: {{ $b['bg'] }}; color: {{ $b['color'] }}; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">{{ $b['label'] }}</span>
                            @elseif($isCompleted)
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">(Non-Lead)</span>
                            @else
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">{{ $v->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8; font-size: 12px;">
                            Belum ada riwayat kunjungan yang ditangani.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 14px;">
@include('partials.pagination', ['paginator' => $visits])
        </div>
    </div>

</div>

<!-- ============================================== -->
<!-- KUMPULAN MODAL CATATAN DI LUAR TABEL           -->
<!-- ============================================== -->
@foreach($visits as $v)
    @php
        $statusLowerModal = strtolower(trim($v->status ?? ''));
        $isCancelledModal = in_array($statusLowerModal, ['cancelled', 'ditolak', 'dibatalkan']);
        $isCompletedModal = !$isCancelledModal && in_array($statusLowerModal, ['completed', 'selesai', 'meeting selesai', 'sedang bertemu']);

        $leadModal = $v->lead; // bisa null kalau visit tidak dikonversi jadi lead
        $scheduleTextMap = [
            'deal' => 'Sudah Deal 🎉',
            'lost' => 'Lead Hilang / Lost',
        ];
        $scheduleText = $leadModal
            ? ($scheduleTextMap[$leadModal->status] ?? ($leadModal->follow_up_at ? \Carbon\Carbon::parse($leadModal->follow_up_at)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan'))
            : 'Kunjungan biasa, tidak dikonversi jadi lead';
    @endphp

    @if($isCompletedModal)
        <div class="modal fade" id="noteModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                        <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033;">
                            Riwayat & Hasil Pertemuan - {{ $v->guest->name ?? 'Tamu' }}
                            @if(isset($v->guest) && $v->guest->is_vip)⭐@endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">

                        <!-- Status & Jadwal Terkini -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; gap: 20px;">
                            <div>
                                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Tahap Pipeline Terakhir:</div>
                                <div style="font-weight: 800; color: #172033; text-transform: capitalize;">
                                    {{ $leadModal ? ($leadBadges[$leadModal->status]['label'] ?? $leadModal->status) : 'Bukan Lead' }}
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal / Keterangan Status:</div>
                                <div style="font-weight: 700; color: #006B3F;">
                                    {{ $scheduleText }}
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Awal Pertemuan -->
                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">
                                📌 Catatan Pertemuan Awal:
                            </label>
                            <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                                {{ $v->meeting_result ?? 'Tidak ada catatan awal yang ditinggalkan.' }}
                            </div>
                        </div>

                        <!-- Riwayat Update Pipeline (hanya ada kalau visit ini jadi lead) -->
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 8px;">
                                🔄 Riwayat Update Pipeline:
                            </label>

                            @forelse(optional($leadModal)->followUps ?? [] as $fu)
                                <div style="background: #fdfdfd; border: 1px solid #e2e8f0; border-left: 4px solid #006B3F; border-radius: 8px; padding: 12px; margin-bottom: 10px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; color: #64748b;">
                                        <span>📅 Tanggal Update: <strong>{{ \Carbon\Carbon::parse($fu->created_at)->translatedFormat('d F Y, H:i') }}</strong></span>
                                        <span>Tahap: <strong style="text-transform: uppercase; color: #006B3F;">{{ $leadBadges[$fu->status]['label'] ?? $fu->status }}</strong></span>
                                    </div>
                                    <div style="color: #334155; font-size: 13px; white-space: pre-line;">
                                        {{ $fu->result ?? 'Tidak ada detail catatan pada pembaruan ini.' }}
                                    </div>
                                    @if($fu->due_at)
                                        <div style="font-size: 11px; color: #475569; margin-top: 6px;">
                                            Target Due Date: {{ \Carbon\Carbon::parse($fu->due_at)->translatedFormat('d F Y') }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="font-style: italic; color: #94a3b8; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 12px; border-radius: 8px; text-align: center; font-size: 12px;">
                                    Tidak ada riwayat update pipeline untuk kunjungan ini.
                                </div>
                            @endforelse
                        </div>

                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 12px 24px;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- CDN CSS Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* 1. Mengecilkan Ukuran Container Pop-up Kalender */
    .flatpickr-calendar {
        width: 230px !important;            /* Default-nya ~307px, kita kecilkan ke 230px */
        padding: 6px !important;
        border-radius: 10px !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
        border: 1px solid #e8edf5 !important;
        font-family: inherit !important;
    }

    /* 2. Mengecilkan Header Bulan & Tahun */
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

    /* 3. Mengecilkan Baris Nama Hari (Sen, Sel, Rab...) */
    span.flatpickr-weekday {
        color: #778195 !important;
        font-weight: 600 !important;
        font-size: 10px !important;
    }

    /* 4. Mengecilkan Kotak Angka Tanggal */
    .flatpickr-day {
        max-width: 28px !important;
        height: 28px !important;
        line-height: 28px !important;
        font-size: 11px !important;
        border-radius: 6px !important;
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
    }

    .flatpickr-day.inRange {
        background: #e6f4ed !important;
        border-color: #e6f4ed !important;
        box-shadow: -4px 0 0 #e6f4ed, 4px 0 0 #e6f4ed !important;
    }

    /* Lock Input Text Flatpickr agar tidak melar */
    .flatpickr-input[readonly] {
        height: 32px !important;
        padding: 4px 8px !important;
        font-size: 11px !important;
    }

    /* Mengecilkan & mengunci kotak input tanggal Flatpickr */
.flatpickr-input,
input.flatpickr-input,
.flatpickr-input.form-control {
    height: 32px !important;            /* Atur tinggi kotak */
    padding: 4px 10px !important;        /* Atur jarak teks ke tepi */
    font-size: 11px !important;         /* Ukuran teks */
    border-radius: 6px !important;      /* Kelengkungan sudut */
    line-height: normal !important;
    box-sizing: border-radius-box;
}

/* Mengatur ukuran kotak container-nya */
div:has(> #start_date),
div:has(> #end_date) {
    width: 130px !important;            /* Lebar kotak input tanggal */
}

.flatpickr-custom-input[readonly] {
    background-color: #ffffff !important;
    cursor: pointer !important;
    height: 38px !important;
    padding: 8px 12px !important;
    border: 1px solid #e8edf5 !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #172033 !important;
    outline: none !important;
    width: 100% !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
}

.flatpickr-custom-input:focus,
.flatpickr-custom-input.active {
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
    const startPicker = flatpickr("#start_date", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        altInputClass: "flatpickr-custom-input",
        disableMobile: "true",
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                endPicker.set('minDate', selectedDates[0]);
            }
        }
    });

    const endPicker = flatpickr("#end_date", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        altInputClass: "flatpickr-custom-input",
        disableMobile: "true",
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                startPicker.set('maxDate', selectedDates[0]);
            }
        }
    });

    const startVal = document.getElementById('start_date').value;
    const endVal = document.getElementById('end_date').value;
    if (startVal) endPicker.set('minDate', startVal);
    if (endVal) startPicker.set('maxDate', endVal);
});
</script>
@endsection