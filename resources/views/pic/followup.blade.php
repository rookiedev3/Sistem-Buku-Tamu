@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Header -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Lead & Follow-Up Penjualan 📈</h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar prospek klien hasil kunjungan, catat status konversi, dan jadwalkan tindak lanjut.</p>
    </div>

    <!-- Tabel Manajemen Lead -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Prospek & Status Konversi</h3>
        </div>
        <!-- Filter Cepat Follow-Up -->
<div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
@php
    $filterOptions = [
        'all'      => 'Semua' . ($countAll > 0 ? " ({$countAll})" : ''),
        'overdue'  => 'Terlambat' . ($countOverdue > 0 ? " ({$countOverdue})" : ''),
        'today'    => 'Hari Ini' . ($countToday > 0 ? " ({$countToday})" : ''),
        'upcoming' => 'Mendatang' . ($countUpcoming > 0 ? " ({$countUpcoming})" : ''),
    ];
    $activeFilter = $filter ?? 'all';
@endphp

    @foreach($filterOptions as $key => $label)
        @php
            $isActive = $activeFilter === $key;
            $bg = $isActive ? '#006B3F' : '#f1f5f9';
            $color = $isActive ? '#ffffff' : '#475569';
            if (!$isActive && $key === 'overdue' && $countOverdue > 0) {
                $bg = '#fef2f2'; $color = '#dc2626';
            }
        @endphp
        <a href="{{ route('pic.followup', ['filter' => $key]) }}"
           style="background: {{ $bg }}; color: {{ $color }}; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1px solid {{ $isActive ? '#006B3F' : '#e2e8f0' }};">
            {{ $label }}
        </a>
    @endforeach
</div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">Kontak (WhatsApp)</th>
                        <th style="padding: 14px;">Catatan / Follow-Up Terakhir</th>
                        <th style="padding: 14px;">Tgl Follow-Up</th>
                        <th style="padding: 14px;">Status Lead</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $index => $lead)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">{{ $leads->firstItem() + $index }}</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">{{ $lead->guest->name ?? '-' }}</strong>
                            <span style="font-size: 11px; color: #778195;">{{ $lead->guest->company_name ?? '-' }}</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">
                            {{ $lead->guest->phone ?? '-' }}
                        </td>
                        <td style="padding: 14px; color: #475569;">
                            @php
                                $latestFollowUp = $lead->followUps->first();
                                $note = $latestFollowUp->result ?? $lead->meeting_result;
                            @endphp
                            {{ Str::limit($note ?? 'Belum ada catatan follow-up.', 50) }}
                        </td>
<td style="padding: 14px;">
    @if($lead->follow_up_at)
        @php
            $fuDate = \Carbon\Carbon::parse($lead->follow_up_at)->startOfDay();
            $today = \Carbon\Carbon::today();
        @endphp
        <div style="font-weight: 700; color: #172033; margin-bottom: 4px;">
            {{ $fuDate->translatedFormat('d M Y') }}
        </div>
        @if($fuDate->lt($today))
            <span style="background: #fef2f2; color: #dc2626; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">
                ⚠ Terlambat {{ $fuDate->diffInDays($today) }} hari
            </span>
        @elseif($fuDate->eq($today))
            <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">
                🔥 Hari Ini
            </span>
@else
    @php $diff = abs($fuDate->diffInDays($today)); @endphp
    <span style="background: #e6f4ed; color: #006B3F; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700;">
        @if($diff == 1)
            Besok
        @else
            {{ $diff }} hari mendatang
        @endif
    </span>
@endif
    @else
        <span style="color: #94a3b8; font-size: 12px;">Belum dijadwalkan</span>
    @endif
</td>
                        <td style="padding: 14px;">
                            @if($lead->potential_level == 'warm')
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Warm Lead</span>
                            @elseif($lead->potential_level == 'hot')
                                <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Hot Lead 🔥</span>
                            @elseif($lead->potential_level == 'deal')
                                <span style="background: #dcfce7; color: #15803d; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Deal / Closing 🎉</span>
                            @elseif($lead->potential_level == 'cold')
                                <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Cold / Biasa</span>
                            @else
                                <span style="background: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Drop</span>
                            @endif
                        </td>
                        <td style="padding: 14px; text-align: center; white-space: nowrap;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <!-- Tombol Lihat Riwayat Catatan -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $lead->id }}" style="background: #ffffff; color: #006B3F; border: 1px solid #006B3F; padding: 6px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    📝 Riwayat
                                </button>
                                <!-- Tombol Update Status -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus{{ $lead->id }}" style="background: #006B3F; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    Update Status
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Belum ada prospek lead yang perlu di-follow up.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $leads->links() }}
        </div>
    </div>

</div>

<!-- ================================================= -->
<!-- KUMPULAN MODAL (LIHAT RIWAYAT & UPDATE STATUS)    -->
<!-- ================================================= -->
@foreach($leads as $lead)
    @php
        $latestFollowUp = $lead->followUps->first();
        $activeFollowUpDate = $latestFollowUp->due_at ?? $latestFollowUp->follow_up_at ?? $lead->follow_up_at;
        
        // Logika teks dinamis untuk jadwal aktif pada modal riwayat
        $levelModal = strtolower(trim($lead->potential_level ?? ''));
        if ($levelModal == 'deal') {
            $scheduleText = 'Sudah Deal 🎉';
        } elseif ($levelModal == 'drop') {
            $scheduleText = 'Proses Dibatalkan / Drop';
        } elseif ($levelModal == 'cold') {
            $scheduleText = 'Kunjungan Biasa (Tanpa Follow-Up)';
        } else {
            $scheduleText = $activeFollowUpDate ? \Carbon\Carbon::parse($activeFollowUpDate)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan';
        }
    @endphp

    <!-- 1. MODAL LIHAT RIWAYAT CATATAN & FOLLOW-UP -->
    <div class="modal fade" id="noteModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                    <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033;">
                        Riwayat & Hasil Pertemuan - {{ $lead->guest->name ?? 'Klien' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">
                    
                    <!-- Status & Jadwal Terkini -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; gap: 20px;">
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Status Prospek Terakhir:</div>
                            <div style="font-weight: 800; color: #172033; text-transform: capitalize;">
                                {{ $lead->potential_level ?? 'Belum ada status' }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal Follow-Up Aktif:</div>
                            <div style="font-weight: 700; color: #006B3F;">
                                {{ $scheduleText }}
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Awal Pertemuan (Meeting Pertama) -->
                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">
                            📌 Catatan Pertemuan Awal:
                        </label>
                        <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                            {{ $lead->meeting_result ?? 'Tidak ada catatan awal yang ditinggalkan.' }}
                        </div>
                    </div>

                    <!-- Riwayat Update / Follow-Ups -->
                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 8px;">
                            🔄 Riwayat Update & Hasil Follow-Up (Tabel Follow-Ups):
                        </label>
                        
                        @forelse($lead->followUps as $fu)
                            <div style="background: #fdfdfd; border: 1px solid #e2e8f0; border-left: 4px solid #006B3F; border-radius: 8px; padding: 12px; margin-bottom: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; color: #64748b;">
                                    <span>📅 Tanggal Update: <strong>{{ \Carbon\Carbon::parse($fu->created_at)->translatedFormat('d F Y, H:i') }}</strong></span>
                                    <span>Status Follow-Up: <strong style="text-transform: uppercase; color: #006B3F;">{{ $fu->status }}</strong></span>
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
                                Belum ada catatan update follow-up lanjutan untuk prospek ini.
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


    <!-- 2. MODAL UPDATE FOLLOW UP DINAMIS -->
    <div class="modal fade" id="modalUpdateStatus{{ $lead->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                    <h5 class="modal-title" style="font-size: 16px; font-weight: 800; color: #172033;">
                        🔄 Update Status & Follow-Up
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="padding: 24px;">
                    <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                        <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Klien Prospek:</span>
                        <strong style="color: #172033; font-size: 14px;">{{ $lead->guest->name ?? '-' }} ({{ $lead->guest->company_name ?? '-' }})</strong>
                        <div style="color: #475569; font-size: 12px; margin-top: 2px;">WhatsApp: {{ $lead->guest->phone ?? '-' }}</div>
                        
                        @if(isset($latestFollowUp) && $latestFollowUp->result)
                        <div style="margin-top: 8px; border-top: 1px dashed #cbd5e1; padding-top: 6px;">
                            <span style="font-size: 10px; font-weight: 700; color: #006B3F; text-transform: uppercase;">Catatan Follow-Up Sebelumnya:</span>
                            <p style="margin: 2px 0 0 0; color: #172033; font-style: italic;">"{{ $latestFollowUp->result }}"</p>
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('pic.leads.updateFollowUp', $lead->id) }}" method="POST">
                        @csrf
                        
                        <!-- Status Lead Terbaru -->
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Status Lead Terbaru</label>
                            <select name="status" id="statusSelect{{ $lead->id }}" class="form-select status-dropdown" data-id="{{ $lead->id }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                                <option value="warm" {{ $lead->potential_level == 'warm' ? 'selected' : '' }}>Warm Lead (Perlu Follow-Up Lanjutan)</option>
                                <option value="hot" {{ $lead->potential_level == 'hot' ? 'selected' : '' }}>Hot Lead (Prospek Tinggi / Siap Deal)</option>
                                <option value="deal" {{ $lead->potential_level == 'deal' ? 'selected' : '' }}>Deal / Berhasil (Resmi Order)</option>
                                <option value="cold" {{ $lead->potential_level == 'cold' ? 'selected' : '' }}>Cold / Kunjungan Biasa</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Hasil Obrolan Follow-Up Hari Ini</label>
                            <textarea name="result" rows="3" placeholder="Tuliskan respon klien dari WA / telepon..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;" required></textarea>
                        </div>

<!-- Jadwal Follow-Up Berikutnya -->
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

        <input type="text" name="due_at" id="dateInput{{ $lead->id }}"
            value="{{ $lead->follow_up_at ? \Carbon\Carbon::parse($lead->follow_up_at)->format('Y-m-d') : '' }}"
            placeholder="Pilih tanggal follow-up..." readonly
            style="width: 100%; padding: 10px 14px 10px 44px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; box-sizing: border-box; font-family: inherit; transition: all 0.2s ease;">
    </div>
    <small id="dateNote{{ $lead->id }}" style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;">*Tanggal otomatis dinonaktifkan jika status Deal atau Cold.</small>
</div>

                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                            <button type="button" data-bs-dismiss="modal" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">Batal</button>
                            <button type="submit" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

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
    input[id^="dateInput"]:focus {
        border-color: #006B3F !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important;
    }
</style>

<!-- CDN JS Flatpickr & Bahasa Indonesia -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selects = document.querySelectorAll(".status-dropdown");

        selects.forEach(select => {
            const leadId = select.getAttribute("data-id");
            const dateInputEl = document.getElementById("dateInput" + leadId);
            if (!dateInputEl) return;
const fp = flatpickr(dateInputEl, {
    locale: "id",
    dateFormat: "Y-m-d",
    minDate: "today",
    disableMobile: "true"
});

            function handleDateAccess() {
                const noFollowUp = ["deal", "cold", "drop"].includes(select.value);

                if (noFollowUp) {
                    fp.clear();
                    fp.set('clickOpens', false);
                    dateInputEl.disabled = true;
                    dateInputEl.style.backgroundColor = "#f1f5f9";
                    dateInputEl.style.color = "#94a3b8";
                    dateInputEl.style.cursor = "not-allowed";
                } else {
                    fp.set('clickOpens', true);
                    dateInputEl.disabled = false;
                    dateInputEl.style.backgroundColor = "#fbfcfe";
                    dateInputEl.style.color = "#172033";
                    dateInputEl.style.cursor = "pointer";
                }
            }

            handleDateAccess();
            select.addEventListener("change", handleDateAccess);
        });
    });
</script>
@endsection