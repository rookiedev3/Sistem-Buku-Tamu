@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Header + Statistik -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Lead & Follow-Up Penjualan 📈</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola pipeline prospek klien hasil kunjungan, update tahapan, dan pantau konversi Deal. Lead yang Lost otomatis dipindah ke Riwayat Kunjungan.</p>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Berhasil (Deal)</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">{{ $countDeal }} Klien</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Pipeline Aktif</span>
            <strong style="font-size: 24px; font-weight: 900; color: #d97706; margin-top: 4px;">{{ $countActive }} Lead</strong>
        </div>
    </div>

    <!-- Tabel Pipeline -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Pipeline Lead & Status Konversi</h3>
                    <span style="font-size: 12px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>

<!-- Filter Cepat + Dropdown VIP (satu baris, sejajar seperti dashboard) -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">

            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @php
                    $filterOptions = [
                        'all'      => 'Semua' . ($countAll > 0 ? " ({$countAll})" : ''),
                        'active'   => 'Aktif' . ($countActive > 0 ? " ({$countActive})" : ''),
                        'overdue'  => 'Terlambat' . ($countOverdue > 0 ? " ({$countOverdue})" : ''),
                        'today'    => 'Hari Ini' . ($countToday > 0 ? " ({$countToday})" : ''),
                        'upcoming' => 'Mendatang' . ($countUpcoming > 0 ? " ({$countUpcoming})" : ''),
                        // 'deal'     => 'Deal' . ($countDeal > 0 ? " ({$countDeal})" : ''),
                    ];
                    $activeFilter = $filter ?? 'active';
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
                    <a href="{{ route('pic.leads', array_merge(request()->query(), ['filter' => $key])) }}"
                       style="background: {{ $bg }}; color: {{ $color }}; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1px solid {{ $isActive ? '#006B3F' : '#e2e8f0' }};">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <label style="font-size: 12px; font-weight: 700; color: #5c6678;">Status:</label>
                <select onchange="window.location.href=this.value" style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 12px; font-weight: 700; color: #172033; background: #fff; outline: none; cursor: pointer;">
                    @php
                        $vipOptions = ['all' => 'Semua Status', 'vip' => '⭐ VIP', 'reguler' => 'Reguler'];
                        $activeVipFilter = $vipFilter ?? 'all';
                    @endphp
                    @foreach($vipOptions as $key => $label)
                        <option
                            value="{{ route('pic.leads', array_merge(request()->query(), ['vip_status' => $key])) }}"
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
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">Kontak (WhatsApp)</th>
                        <th style="padding: 14px;">Value</th>
                        <th style="padding: 14px;">Tgl Follow-Up</th>
                        <th style="padding: 14px;">Tahap Pipeline</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $index => $lead)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px 20px; font-weight: 700;">{{ $leads->firstItem() + $index }}</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">
                                {{ $lead->guest->name ?? '-' }}
                                @if(isset($lead->guest) && $lead->guest->is_vip)
                                    <span title="VIP" style="color: #d97706;">⭐</span>
                                @endif
                            </strong>
                            <span style="font-size: 11px; color: #778195;">{{ $lead->guest->company_name ?? '-' }}</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">{{ $lead->guest->phone ?? '-' }}</td>
                        <td style="padding: 14px; color: #172033; font-weight: 700;">
                            {{ $lead->estimated_value ? rupiah($lead->estimated_value, true) : '-' }}
                        </td>
                        <td style="padding: 14px;">
                            @if($lead->follow_up_at)
                                @php
                                    $fuDate = \Carbon\Carbon::parse($lead->follow_up_at)->startOfDay();
                                    $today  = \Carbon\Carbon::today();
                                @endphp
                                <div style="font-weight: 700; color: #172033; margin-bottom: 4px;">{{ $fuDate->translatedFormat('d M Y') }}</div>
                                @if($lead->status === 'deal')
                                    {{-- tidak perlu badge terlambat/hari ini kalau sudah deal --}}
                                @elseif($fuDate->lt($today))
                                    <span style="background: #fef2f2; color: #dc2626; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">⚠ Terlambat {{ $fuDate->diffInDays($today) }} hari</span>
                                @elseif($fuDate->eq($today))
                                    <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">🔥 Hari Ini</span>
                                @else
                                    @php $diff = abs($fuDate->diffInDays($today)); @endphp
                                    <span style="background: #e6f4ed; color: #006B3F; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700;">
                                        @if($diff == 1) Besok @else {{ $diff }} hari mendatang @endif
                                    </span>
                                @endif
                            @else
                                <span style="color: #94a3b8; font-size: 12px;">Belum dijadwalkan</span>
                            @endif
                        </td>
                        <td style="padding: 14px;">
                            @php
                                $badges = [
                                    'new'         => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Baru'],
                                    'contacted'   => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Dihubungi'],
                                    'negotiation' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'Negosiasi 🔥'],
                                    'deal'        => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Deal 🎉'],
                                    'lost'        => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Lost'],
                                ];
                                $b = $badges[$lead->status] ?? $badges['new'];
                            @endphp
                            <span style="background: {{ $b['bg'] }}; color: {{ $b['color'] }}; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">{{ $b['label'] }}</span>
                        </td>
<td style="padding: 14px; text-align: center; white-space: nowrap;">
    <div style="display: flex; gap: 6px; justify-content: center;">
        <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $lead->id }}" style="background: #ffffff; color: #006B3F; border: 1px solid #006B3F; padding: 6px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">📝 Riwayat</button>

        @if($lead->status === 'deal')
            <button type="button" disabled title="Lead sudah Deal, tidak bisa diubah lagi" style="background: #f1f5f9; color: #94a3b8; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: not-allowed;">
                ✔ Selesai
            </button>
        @else
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus{{ $lead->id }}" style="background: #006B3F; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">Update Tahap</button>
        @endif
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8;">Belum ada prospek lead.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
@include('partials.pagination', ['paginator' => $leads])
        </div>
    </div>
</div>

@foreach($leads as $lead)
    @php
        $latestFollowUp = $lead->followUps->first();
        $scheduleTextMap = ['deal' => 'Sudah Deal 🎉', 'lost' => 'Lead Hilang / Lost'];
        $scheduleText = $scheduleTextMap[$lead->status]
            ?? ($lead->follow_up_at ? \Carbon\Carbon::parse($lead->follow_up_at)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan');
    @endphp

    <!-- MODAL RIWAYAT -->
    <div class="modal fade" id="noteModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                    <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033;">
                        Riwayat & Hasil Pertemuan - {{ $lead->guest->name ?? 'Klien' }}
                        @if(isset($lead->guest) && $lead->guest->is_vip)⭐@endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; gap: 20px;">
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Tahap Pipeline Terakhir:</div>
                            <div style="font-weight: 800; color: #172033;">{{ $badges[$lead->status]['label'] ?? $lead->status }}</div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal Follow-Up:</div>
                            <div style="font-weight: 700; color: #006B3F;">{{ $scheduleText }}</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">📌 Catatan Pertemuan Awal:</label>
                        <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                            {{ optional($lead->visit)->meeting_result ?? 'Tidak ada catatan awal.' }}
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 8px;">🔄 Riwayat Update Pipeline:</label>
                        @forelse($lead->followUps as $fu)
                            <div style="background: #fdfdfd; border: 1px solid #e2e8f0; border-left: 4px solid #006B3F; border-radius: 8px; padding: 12px; margin-bottom: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; color: #64748b;">
                                    <span>📅 {{ \Carbon\Carbon::parse($fu->created_at)->translatedFormat('d F Y, H:i') }}</span>
                                    <span>Tahap: <strong style="color: #006B3F;">{{ $badges[$fu->status]['label'] ?? $fu->status }}</strong></span>
                                </div>
                                <div style="color: #334155; font-size: 13px; white-space: pre-line;">{{ $fu->result ?? '-' }}</div>
                                <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 8px;">
                                    <div style="font-size: 11px; color: #006B3F; font-weight: 700;">
                                        💰 Estimasi Value: {{ $fu->estimated_value ? rupiah($fu->estimated_value, true) : '-' }}
                                    </div>
                                    @if($fu->due_at)
                                        <div style="font-size: 11px; color: #475569;">Target Due Date: {{ \Carbon\Carbon::parse($fu->due_at)->translatedFormat('d F Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="font-style: italic; color: #94a3b8; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 12px; border-radius: 8px; text-align: center; font-size: 12px;">
                                Belum ada catatan update follow-up.
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

    <!-- MODAL UPDATE TAHAP -->
    <div class="modal fade" id="modalUpdateStatus{{ $lead->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                    <h5 class="modal-title" style="font-size: 16px; font-weight: 800; color: #172033;">🔄 Update Tahap Pipeline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                        <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Klien Prospek:</span>
                        <strong style="color: #172033; font-size: 14px;">
                            {{ $lead->guest->name ?? '-' }}
                            @if(isset($lead->guest) && $lead->guest->is_vip)⭐@endif
                            ({{ $lead->guest->company_name ?? '-' }})
                        </strong>
                        <div style="color: #475569; font-size: 12px; margin-top: 2px;">WhatsApp: {{ $lead->guest->phone ?? '-' }}</div>
                    </div>

                    <form action="{{ route('pic.leads.updateFollowUp', $lead->id) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Tahap Pipeline Terbaru</label>
                            <select name="status" id="statusSelect{{ $lead->id }}" class="form-select status-dropdown" data-id="{{ $lead->id }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                                <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>Baru (Belum Dihubungi)</option>
                                <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Dihubungi</option>
                                <option value="negotiation" {{ $lead->status == 'negotiation' ? 'selected' : '' }}>Negosiasi</option>
                                <option value="deal" {{ $lead->status == 'deal' ? 'selected' : '' }}>Deal / Berhasil</option>
                                <option value="lost" {{ $lead->status == 'lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                            <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0 0;">*Kalau dipilih "Lost", data ini akan hilang dari halaman ini dan hanya bisa dilihat di Riwayat Kunjungan.</p>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Hasil Obrolan Follow-Up Hari Ini</label>
                            <textarea name="result" rows="3" placeholder="Tuliskan respon klien dari WA / telepon..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;" required></textarea>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Estimasi Nilai Deal (Rp)</label>
                            <div style="position: relative; display: flex; align-items: center; width: 100%;">
                                <div style="position: absolute; left: 14px; color: #006B3F; font-weight: 700; font-size: 13px; pointer-events: none;">Rp</div>
                                <input type="text"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    class="rupiah-input"
                                    data-hidden-target="estimatedValueRaw{{ $lead->id }}"
                                    id="estimatedValueDisplay{{ $lead->id }}"
                                    value=""
                                    placeholder="Contoh: 5.000.000"
                                    style="width: 100%; padding: 10px 14px 10px 34px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; box-sizing: border-box;">
                            </div>
                            <input type="hidden" name="estimated_value" id="estimatedValueRaw{{ $lead->id }}" value="">
                            <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0 0;">
                                Nilai saat ini: <strong style="color: #475569;">{{ $lead->estimated_value ? rupiah($lead->estimated_value, true) : 'Belum diisi' }}</strong>.
                                Kosongkan kalau belum ada perubahan dari estimasi sebelumnya.
                            </p>
                        </div>


                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Jadwal Follow-Up Berikutnya</label>
                            <div style="position: relative; display: flex; align-items: center; width: 100%;">
                                <div style="position: absolute; left: 14px; color: #006B3F; pointer-events: none;">
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
                                    style="width: 100%; padding: 10px 14px 10px 44px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; box-sizing: border-box;">
                            </div>
                            <small style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;">*Tanggal otomatis dinonaktifkan jika tahap Deal atau Lost.</small>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar { border-radius: 16px !important; box-shadow: 0 12px 32px rgba(31, 53, 97, 0.15) !important; border: 1px solid #e8edf5 !important; padding: 8px !important; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange { background: #006B3F !important; border-color: #006B3F !important; border-radius: 10px !important; }
    .flatpickr-day:hover { border-radius: 10px !important; }
    input[id^="dateInput"]:focus { border-color: #006B3F !important; box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important; }
    input.rupiah-input:focus { border-color: #006B3F !important; box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1) !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".status-dropdown").forEach(select => {
            const leadId = select.getAttribute("data-id");
            const dateInputEl = document.getElementById("dateInput" + leadId);
            if (!dateInputEl) return;

            const fp = flatpickr(dateInputEl, { locale: "id", dateFormat: "Y-m-d", minDate: "today", disableMobile: "true" });

            function handleDateAccess() {
                const noFollowUp = ["deal", "lost"].includes(select.value);
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

        // Format input Rupiah otomatis: user ngetik "7000000" -> tampil "7.000.000"
        // Nilai murni angkanya disimpan di hidden input yang dikirim ke server.
        document.querySelectorAll(".rupiah-input").forEach(function (input) {
            const hidden = document.getElementById(input.dataset.hiddenTarget);

            input.addEventListener("input", function () {
                const raw = this.value.replace(/\D/g, ""); // buang semua selain angka
                this.value = raw ? new Intl.NumberFormat("id-ID").format(raw) : "";
                if (hidden) hidden.value = raw;
            });
        });
    });
</script>
@endsection