@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header & Statistik Lead Tim -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Pipeline Lead & Prospek Tim 📈</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Pengawasan menyeluruh terhadap status konversi penjualan yang sedang dikerjakan oleh tim PIC/Sales.</p>
        </div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
    <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Prospek Aktif</span>
    <strong style="font-size: 24px; font-weight: 900; color: #1e3a8a; margin-top: 4px;">{{ $countActive }} Klien</strong>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
    <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Deal</span>
    <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">{{ $countDeal }} Klien</strong>
</div>
    </div>

    <!-- Tabel Monitoring Lead Tim -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Prospek & PIC Penanggung Jawab</h3>
        </div>
<!-- Ganti div filter cepat lama dengan versi berikut (satu baris, sejajar) -->
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">

    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @php
            $filterOptions = [
                'all'      => 'Semua' . ($countAll > 0 ? " ({$countAll})" : ''),
                'active'   => 'Aktif' . ($countActive > 0 ? " ({$countActive})" : ''),
                'overdue'  => 'Terlambat' . ($countOverdue > 0 ? " ({$countOverdue})" : ''),
                'today'    => 'Hari Ini' . ($countToday > 0 ? " ({$countToday})" : ''),
                'upcoming' => 'Mendatang' . ($countUpcoming > 0 ? " ({$countUpcoming})" : ''),
                'deal'     => 'Deal' . ($countDeal > 0 ? " ({$countDeal})" : ''),
                'lost'     => 'Lost' . ($countLost > 0 ? " ({$countLost})" : ''),
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
            <a href="{{ route('manager.leads', array_merge(request()->query(), ['filter' => $key])) }}"
               style="background: {{ $bg }}; color: {{ $color }}; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1px solid {{ $isActive ? '#006B3F' : '#e2e8f0' }};">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- TAMBAHKAN DROPDOWN INI -->
    <div style="display: flex; align-items: center; gap: 8px;">
        <label style="font-size: 12px; font-weight: 700; color: #5c6678;">Status:</label>
        <select onchange="window.location.href=this.value" style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 12px; font-weight: 700; color: #172033; background: #fff; outline: none; cursor: pointer;">
            @php
                $vipOptions = ['all' => 'Semua Status', 'vip' => '⭐ VIP', 'reguler' => 'Reguler'];
                $activeVipFilter = $vipFilter ?? 'all';
            @endphp
            @foreach($vipOptions as $key => $label)
                <option
                    value="{{ route('manager.leads', array_merge(request()->query(), ['vip_status' => $key])) }}"
                    {{ $activeVipFilter === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

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
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">PIC / Sales</th>
                        <th style="padding: 14px;">Minat / Catatan Terakhir</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status Lead</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $index => $lead)
                    @php
                        $latestNote = optional($lead->followUps->first())->result
                            ?? optional($lead->visit)->meeting_result
                            ?? 'Belum ada catatan.';
                        $badge = $leadBadges[$lead->status] ?? $leadBadges['new'];
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">{{ $leads->firstItem() + $index }}</td>
<td style="padding: 14px;">
    <strong style="display: block; color: #172033; font-weight: 800;">
        {{ $lead->guest->name ?? '-' }}
        @if(isset($lead->guest) && $lead->guest->is_vip)
            <span title="VIP" style="color: #d97706;">⭐</span>
        @endif
    </strong>
    <span style="font-size: 11px; color: #778195;">{{ $lead->guest->company_name ?? '-' }}</span>
</td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">{{ $lead->owner->name ?? '-' }}</td>
<td style="padding: 14px; color: #475569;">
    @if($lead->followUps->isNotEmpty() || optional($lead->visit)->meeting_result)
        <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $lead->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
            📝 Lihat Catatan
        </button>
    @else
        <span style="font-size: 11px; color: #94a3b8; font-style: italic;">Belum ada catatan.</span>
    @endif
</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">{{ $badge['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Belum ada data prospek/lead.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- ============================================== -->
<!-- KUMPULAN MODAL CATATAN DI LUAR TABEL           -->
<!-- ============================================== -->
@foreach($leads as $lead)
    @php
        $scheduleTextMap = ['deal' => 'Sudah Deal 🎉', 'lost' => 'Lead Hilang / Lost'];
        $scheduleText = $scheduleTextMap[$lead->status]
            ?? ($lead->follow_up_at ? \Carbon\Carbon::parse($lead->follow_up_at)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan');
    @endphp

    <div class="modal fade" id="noteModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                    <div>
                        <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 2px;">
                            Riwayat & Hasil Pertemuan – {{ $lead->guest->name ?? 'Klien' }}
                        </h5>
                        <span style="font-size: 11px; color: #778195; font-weight: 600;">
                            Ditangani oleh: {{ $lead->owner->name ?? '-' }} (PIC)
                        </span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; gap: 20px;">
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Tahap Pipeline Terakhir:</div>
                            <div style="font-weight: 800; color: #172033;">{{ $leadBadges[$lead->status]['label'] ?? $lead->status }}</div>
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
                                    <span>Tahap: <strong style="color: #006B3F;">{{ $leadBadges[$fu->status]['label'] ?? $fu->status }}</strong></span>
                                </div>
                                <div style="color: #334155; font-size: 13px; white-space: pre-line;">{{ $fu->result ?? '-' }}</div>
                                @if($fu->due_at)
                                    <div style="font-size: 11px; color: #475569; margin-top: 6px;">Target Due Date: {{ \Carbon\Carbon::parse($fu->due_at)->translatedFormat('d F Y') }}</div>
                                @endif
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
@endforeach
        </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan data monitoring real-time</span>
        <span>Total Data: {{ $leads->count() }}</span>
    </div>
</div>
@endsection