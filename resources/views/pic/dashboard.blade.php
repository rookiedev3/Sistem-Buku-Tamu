@extends('layouts.pic')

@section('content')

@php
// 1. Amankan $visits: Jika tidak dikirim Controller, buat Collection kosong
$visits = $visits ?? collect();

// 2. Amankan $vipCount & $regularCount
$vipCount = $vipCount ?? $visits->filter(function($v) {
return optional($v->guest)->is_vip == true;
})->count();

$regularCount = $regularCount ?? ($visits->count() - $vipCount);
@endphp


<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Alert Sukses -->
@if(session('success'))
<div class="alert alert-success" style="border-radius: 12px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger" style="border-radius: 12px; font-weight: 600;">
    <ul style="margin:0; padding-left: 18px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Bagian Sambutan & Statistik Ringkas -->
<div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Dashboard PIC / Sales 👋</h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar tamu berdasarkan kategori VIP & Reguler, konfirmasi kehadiran, catat hasil pertemuan, dan pantau konversi lead.</p>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
        <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu VIP Menunggu</span>
        <strong style="font-size: 24px; font-weight: 900; color: #d97706; margin-top: 4px;">{{ $vipCount }} Orang</strong>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
        <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu Reguler</span>
        <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">{{ $regularCount }} Orang</strong>
    </div>
</div>

    <!-- Tabel Daftar Tamu Ditugaskan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Tamu Masuk & Kategori Pelanggan</h3>
                    <span style="font-size: 12px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>

        <!-- Filter Cepat Dashboard -->
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">

    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @php
            $filterOptions = [
                'all'      => 'Semua',
                'today'    => 'Hari Ini' . ($countToday > 0 ? " ({$countToday})" : ''),
                'upcoming' => 'Terjadwal Mendatang' . ($countUpcoming > 0 ? " ({$countUpcoming})" : ''),
            ];
            $activeFilter = $filter ?? 'all';
        @endphp

        @foreach($filterOptions as $key => $label)
            @php
                $isActive = $activeFilter === $key;
                $bg = $isActive ? '#006B3F' : '#f1f5f9';
                $color = $isActive ? '#ffffff' : '#475569';
            @endphp
            <a href="{{ route('pic.dashboard', array_merge(request()->query(), ['filter' => $key])) }}"
               style="background: {{ $bg }}; color: {{ $color }}; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1px solid {{ $isActive ? '#006B3F' : '#e2e8f0' }};">
                {{ $label }}
            </a>
        @endforeach
    </div>

<!-- Filter Status VIP/Reguler (Dropdown) -->
<div style="display: flex; align-items: center; gap: 8px;">
    <label style="font-size: 12px; font-weight: 700; color: #5c6678;">Status:</label>
    <select onchange="window.location.href=this.value" style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 12px; font-weight: 700; color: #172033; background: #fff; outline: none; cursor: pointer;">
        @php
            $vipOptions = [
                'all'     => 'Semua Status',
                'vip'     => '⭐ VIP',
                'reguler' => 'Reguler',
            ];
            $activeVipFilter = $vipFilter ?? 'all';
        @endphp
        @foreach($vipOptions as $key => $label)
            <option
                value="{{ route('pic.dashboard', array_merge(request()->query(), ['vip_status' => $key])) }}"
                {{ $activeVipFilter === $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

</div>

        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Kategori</th>
                        <th style="padding: 14px;">Keperluan</th>
                        <th style="padding: 14px; text-align: center;">Catatan</th>
                        <th style="padding: 14px;">Waktu / Jadwal</th>
                        <th style="padding: 14px; text-align: center;">Konfirmasi Kehadiran</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $index => $visit)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px; font-weight: 600;">{{ $visits->firstItem() + $index }}</td>

                            <td style="padding: 14px;">
                                <strong style="display: block; color: #172033; font-weight: 800;">
                                    {{ $visit->guest->name ?? '-' }}
                                    @if(isset($visit->guest) && $visit->guest->is_vip)
                                        <span title="VIP" style="color: #d97706;">⭐</span>
                                    @endif
                                </strong>
                                <span style="font-size: 11px; color: #778195;">{{ $visit->guest->company_name ?? '-' }}</span>
                            </td>

                            <td style="padding: 14px;">
                                @php
                                    $catName  = $visit->guest->category->name  ?? '-';
                                    $catColor = $visit->guest->category->color ?? '#006B3F';
                                @endphp
                                <span style="background: {{ $catColor }}22; color: {{ $catColor }}; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">
                                    {{ $catName }}
                                </span>
                            </td>

                            <td style="padding: 14px; color: #475569;">{{ $visit->purpose->name ?? $visit->purpose }}</td>

                            <!-- Kolom Catatan (dari data awal pengirim, bukan hasil meeting) -->
                            <td style="padding: 14px; text-align: center;">
                                @if(!empty($visit->notes))
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalCatatanTamu-{{ $visit->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    📝 Lihat Catatan
                                </button>
                                @else
                                <span style="font-style: italic; color: #94a3b8; font-size: 12px;">-</span>
                                @endif
                            </td>

<td style="padding: 14px; color: #778195; font-weight: 600;">
    @if($visit->check_in_at)
        {{ \Carbon\Carbon::parse($visit->check_in_at)->format('H:i') }} WIB
    @elseif($visit->scheduled_at)
        @php $schedDate = \Carbon\Carbon::parse($visit->scheduled_at); @endphp
        {{ $schedDate->format('H:i') }} WIB
        @if($schedDate->isToday())
            <div style="font-size: 10px; color: #d97706; margin-top: 2px; font-weight: 700;">🔥 Hari Ini</div>
        @else
            <div style="font-size: 10px; color: #1d4ed8; margin-top: 2px; font-weight: 700;">📅 {{ tgl($schedDate) }}</div>
        @endif
    @else
        -
    @endif
</td>

<!-- Kolom Konfirmasi Kehadiran -->
<td style="padding: 14px; text-align: center;">
    @php $statusLower = strtolower($visit->status); @endphp

    @if(in_array($statusLower, ['pending', 'waiting', 'menunggu']))
        <div style="display: flex; justify-content: center; gap: 6px;">
            <!-- Tombol Centang: Konfirmasi Bertemu -->
            <form action="{{ route('pic.updateStatus', $visit->id) }}" method="POST" style="margin:0;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Dikonfirmasi">
                <button type="submit" title="Konfirmasi Benar Bertemu" style="background: #e6f4ed; color: #006B3F; border: 1px solid #bbf7d0; width: 34px; height: 34px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">✓</button>
            </form>

                            <!-- Tombol Silang: Batalkan -->
                            <form action="{{ route('pic.updateStatus', $visit->id) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Dibatalkan">
                                <button type="submit" title="Tolak / Salah Tujuan" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; width: 34px; height: 34px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
                            </form>
                        </div>
                        @elseif(in_array($statusLower, ['terjadwal']))
                        <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Terjadwal</span>
                        @elseif(in_array($statusLower, ['confirmed', 'dikonfirmasi']))
                        <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Dikonfirmasi ✓</span>
                        @elseif(in_array($statusLower, ['cancelled', 'dibatalkan']))
                        <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Dibatalkan ✕</span>
                        @elseif(in_array($statusLower, ['meeting', 'sedang bertemu']))
                        <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Sedang Bertemu</span>
                        @elseif(in_array($statusLower, ['meeting selesai']) || !empty($visit->meeting_result))
                        <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Meeting Selesai</span>
                        @elseif(in_array($statusLower, ['completed', 'selesai']))
                        <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Selesai</span>
                        @else
                        <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Dikonfirmasi ✓</span>
                        @endif
                    </td>

                    <!-- Kolom Aksi Mulai Pertemuan / Catat Hasil -->
                    <td style="padding: 14px; text-align: center;">
                        @php $statusLower = strtolower($visit->status); @endphp

                        @if(in_array($statusLower, ['terjadwal']))
                        <button type="button" disabled style="background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: not-allowed;">
                            Belum Check-In
                        </button>
                        @elseif(in_array($statusLower, ['confirmed', 'dikonfirmasi']))
                        <form action="{{ route('pic.startMeeting', $visit->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: #006B3F; color: white; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                Mulai Pertemuan
                            </button>
                        </form>
                        @elseif(in_array($statusLower, ['meeting', 'sedang bertemu', 'meeting selesai']))
                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalCatatPertemuan-{{ $visit->id }}" style="background: {{ $visit->meeting_result ? '#0d9488' : '#d97706' }}; color: white; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">
                            {{ $visit->meeting_result ? '📝 Edit Catatan Pertemuan' : '📝 Catat Hasil Pertemuan' }}
                        </button>
                        @elseif(in_array($statusLower, ['completed', 'selesai']))
                        <span style="color: #006B3F; font-size: 12px; font-weight: 700;">✔ Selesai (Checked Out)</span>
                        @else
                        <button type="button" disabled style="background: #cbd5e1; color: #64748b; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700;">
                            Mulai Pertemuan
                        </button>
                        @endif
                    </td>
                </tr>

                <!-- MODAL CATATAN TAMU (catatan data awal dari yang kirim/isi kunjungan) -->
                @if(!empty($visit->notes))
                <div class="modal fade" id="modalCatatanTamu-{{ $visit->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                                <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033;">
                                    📝 Catatan dari {{ $visit->guest->name ?? 'Tamu' }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6;">
                                <div style="white-space: pre-line; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                                    {{ $visit->notes }}
                                </div>
                            </div>
                            <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 12px 24px;">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- END MODAL CATATAN TAMU -->

                <!-- MODAL CATAT PERTEMUAN -->
                <div class="modal fade" id="modalCatatPertemuan-{{ $visit->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">

                            <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                                <h5 class="modal-title" style="font-size: 16px; font-weight: 800; color: #172033;">
                                    📝 Catat Hasil Pertemuan & Lead
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body" style="padding: 24px;">
                                <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                                    <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Tamu yang Ditemui:</span>
                                    <strong style="color: #172033; font-size: 14px;">{{ $visit->guest->name ?? '-' }} ({{ $visit->guest->company_name ?? '-' }})</strong>
                                </div>

                                <form action="{{ route('pic.completeMeeting', $visit->id) }}" method="POST">
                                    @csrf
                                    <div style="margin-bottom: 16px;">
                                        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Catatan / Ringkasan Diskusi</label>
                                        <textarea name="meeting_result" rows="3" required placeholder="Tuliskan hasil obrolan atau permintaan khusus klien di sini..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">{{ $visit->meeting_result }}</textarea>
                                    </div>

                                <select name="potential_level" id="potential_level-{{ $visit->id }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                                    <option value="hot">Hot Lead</option>
                                    <option value="warm">Warm Lead</option>
                                    <option value="cold">Cold</option>
                                    <option value="non_lead">Non-Lead</option>
                                </select>

                                <div style="margin-bottom: 20px;">
                                    <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Jadwal Follow-Up Berikutnya</label>

                                    <div style="position: relative; display: flex; align-items: center; width: 100%;">
                                        <div style="position: absolute; left: 14px; display: flex; align-items: center; justify-content: center; pointer-events: none; color: #006B3F;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>

                                        <input type="text" id="follow_up_at-{{ $visit->id }}" name="follow_up_at"
                                            value="{{ $visit->follow_up_at ? \Carbon\Carbon::parse($visit->follow_up_at)->format('Y-m-d') : '' }}"
                                            placeholder="Pilih tanggal follow-up..." readonly
                                            style="width: 100%; padding: 10px 14px 10px 44px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; box-sizing: border-box; font-family: inherit; transition: all 0.2s ease;">
                                    </div>
                                    <p id="follow_up_note-{{ $visit->id }}" style="display:none; font-size: 11px; color: #94a3b8; margin: 6px 0 0 0;">Follow-up tidak diperlukan untuk status Deal / Cold.</p>
                                </div>

                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <button type="button" data-bs-dismiss="modal" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                            Batal
                                        </button>
                                        <button type="submit" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                            Simpan & Selesaikan
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- END MODAL -->

                    @empty
                        <tr>
                            <td colspan="8" style="padding: 30px; text-align: center; color: #778195; font-weight: 600;">
                                Belum ada kunjungan tamu hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
                <!-- Pagination -->
        <div style="margin-top: 20px;">
        @include('partials.pagination', ['paginator' => $visits])
        </div>


    </div>
</div>

<!-- CDN CSS Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<style>
    .flatpickr-calendar {
        border-radius: 16px !important;
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
    #follow_up_at:focus {
        border-color: #006B3F !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($visits as $visit)
        (function() {
            var inputEl  = document.getElementById('follow_up_at-{{ $visit->id }}');
            var selectEl = document.getElementById('potential_level-{{ $visit->id }}');
            var noteEl   = document.getElementById('follow_up_note-{{ $visit->id }}');

            if (!inputEl || !selectEl) return;

var fp = flatpickr(inputEl, {
    locale: "id",
    dateFormat: "Y-m-d",
    minDate: "today",
    disableMobile: "true"
});

            function syncFollowUpState() {
                var noFollowUp = ['deal', 'cold'].includes(selectEl.value);

                if (noFollowUp) {
                    fp.clear();          // kosongkan tanggal yang sudah dipilih
                    fp.set('clickOpens', false);
                    inputEl.disabled = true;
                    inputEl.style.background = '#f1f5f9';
                    inputEl.style.color = '#94a3b8';
                    inputEl.style.cursor = 'not-allowed';
                    if (noteEl) noteEl.style.display = 'block';
                } else {
                    fp.set('clickOpens', true);
                    inputEl.disabled = false;
                    inputEl.style.background = '#fbfcfe';
                    inputEl.style.color = '#172033';
                    inputEl.style.cursor = 'pointer';
                    if (noteEl) noteEl.style.display = 'none';
                }
            }

            selectEl.addEventListener('change', syncFollowUpState);
            syncFollowUpState(); // jalankan sekali saat load, jaga-jaga default value bukan "warm"
        })();
        @endforeach
    });
</script>
@endsection