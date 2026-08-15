@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 16px;">

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 4px;">Pipeline Lead & Prospek Tim </h2>
            <p style="font-size: 11px; color: #778195; margin: 0; line-height: 1.5;">Pengawasan menyeluruh terhadap status konversi penjualan yang sedang dikerjakan oleh tim PIC/Sales.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Prospek Aktif</span>
            <strong style="font-size: 19px; font-weight: 900; color: #1e3a8a; margin-top: 2px;">{{ $countActive }} Klien</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Deal</span>
            <strong style="font-size: 19px; font-weight: 900; color: #013220; margin-top: 2px;">{{ $countDeal }} Klien</strong>
        </div>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 14px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
            <h3 style="font-size: 14px; font-weight: 800; color: #172033; margin: 0;">Daftar Prospek & PIC Penanggung Jawab</h3>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px;">

            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
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
                        $bg = $isActive ? '#013220' : '#f1f5f9';
                        $color = $isActive ? '#ffffff' : '#475569';
                        if (!$isActive && $key === 'overdue' && $countOverdue > 0) {
                            $bg = '#fef2f2'; $color = '#dc2626';
                        }
                    @endphp
                    <a href="{{ route('owner.leads', array_merge(request()->query(), ['filter' => $key])) }}"
                        style="background: {{ $bg }}; color: {{ $color }}; padding: 6px 12px; border-radius: 16px; font-size: 11px; font-weight: 700; text-decoration: none; border: 1px solid {{ $isActive ? '#006B3F' : '#e2e8f0' }};">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678;">Status:</label>
                <select onchange="window.location.href=this.value" style="height: 32px; padding: 4px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 11px; font-weight: 700; color: #172033; background: #fff; outline: none; cursor: pointer;">
                    @php
                        $vipOptions = ['all' => 'Semua Status', 'vip' => 'VIP', 'reguler' => 'Reguler'];
                        $activeVipFilter = $vipFilter ?? 'all';
                    @endphp
                    @foreach($vipOptions as $key => $label)
                        <option
                            value="{{ route('owner.leads', array_merge(request()->query(), ['vip_status' => $key])) }}"
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
                'negotiation' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'Negosiasi'],
                'deal'        => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Deal'],
                'lost'        => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Lost'],
            ];
        @endphp

        <div class="table-responsive">
            <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 12px; color: #172033; margin: 0; min-width: 900px;">
                <thead>
                    <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                        <th style="padding: 8px 10px; font-weight: 700;">No</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Token</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Tamu & Jabatan</th>
                        <th style="padding: 8px 10px; font-weight: 700;">PIC / Sales</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Value</th>
                        <th style="padding: 8px 10px; font-weight: 700;">Tgl Follow Up</th>
                        <th style="padding: 8px 10px; font-weight: 700; text-align: center;">Tahap Pipeline</th>
                        <th style="padding: 8px 10px; font-weight: 700; text-align: center;">Catatan</th>
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
                        <td style="padding: 8px 10px; font-weight: 700;">{{ $leads->firstItem() + $index }}</td>

                        <td style="padding: 8px 10px;">
                            <strong style="color: #006B3F; font-weight: 800;">
                                {{ optional($lead->visit)->visit_code ?? ('VST-' . str_pad(optional($lead->visit)->id ?? $lead->id, 4, '0', STR_PAD_LEFT)) }}
                            </strong>
                        </td>

                        <td style="padding: 8px 10px;">
                            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <strong style="color: #172033; font-weight: 800;">
                                    {{ $lead->guest->name ?? '-' }}
                                </strong>
                                @if(isset($lead->guest) && $lead->guest->is_vip)
                                    <span style="background: #fef3c7; color: #b45309; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 800;">VIP</span>
                                @endif
                            </div>
                            <span style="font-size: 10px; color: #778195; display: block; margin-top: 2px;">
                                {{ $lead->guest->company_name ?? '-' }} ({{ $lead->guest->position ?? '-' }})
                            </span>
                        </td>

                        <td style="padding: 8px 10px; color: #475569; font-weight: 600;">{{ $lead->owner->name ?? '-' }}</td>

                        <td style="padding: 8px 10px; color: #172033; font-weight: 700;">
                            {{ $lead->estimated_value ? rupiah($lead->estimated_value, true) : '-' }}
                        </td>

                        <td style="padding: 8px 10px;">
                            @if($lead->follow_up_at)
                                @php
                                    $fuDate = \Carbon\Carbon::parse($lead->follow_up_at)->startOfDay();
                                    $today  = \Carbon\Carbon::today();
                                @endphp
                                <div style="font-weight: 700; color: #172033; margin-bottom: 3px;">{{ $fuDate->translatedFormat('d M Y') }}</div>
                                @if($lead->status === 'deal')
                                    {{-- selesai --}}
                                @elseif($fuDate->lt($today))
                                    <span style="background: #fef2f2; color: #dc2626; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 800;"> Terlambat {{ $fuDate->diffInDays($today) }} hari</span>
                                @elseif($fuDate->eq($today))
                                    <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 800;"> Hari Ini</span>
                                @else
                                    @php $diff = abs($fuDate->diffInDays($today)); @endphp
                                    <span style="background: #e6f4ed; color: #006B3F; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 700;">
                                        @if($diff == 1) Besok @else {{ $diff }} hari mendatang @endif
                                    </span>
                                @endif
                            @else
                                <span style="color: #94a3b8; font-size: 11px;">Belum dijadwalkan</span>
                            @endif
                        </td>

                        <td style="padding: 8px 10px; text-align: center;">
                            <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; padding: 4px 10px; border-radius: 16px; font-size: 10px; font-weight: 800;">{{ $badge['label'] }}</span>
                        </td>

                        <td style="padding: 8px 10px; text-align: center;">
                            @if($lead->followUps->isNotEmpty() || optional($lead->visit)->meeting_result)
                                <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $lead->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 4px 10px; border-radius: 7px; font-size: 10px; font-weight: 700; cursor: pointer;">
                                    Lihat Catatan
                                </button>
                            @else
                                <span style="font-size: 10px; color: #94a3b8; font-style: italic;">Belum ada catatan.</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #94a3b8; font-size: 12px;">
                            Belum ada data prospek/lead.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($leads as $lead)
            @php
                $scheduleTextMap = ['deal' => 'Sudah Deal', 'lost' => 'Lead Hilang / Lost'];
                $scheduleText = $scheduleTextMap[$lead->status]
                    ?? ($lead->follow_up_at ? \Carbon\Carbon::parse($lead->follow_up_at)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan');
            @endphp

            <div class="modal fade" id="noteModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                        <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                            <div>
                                <h5 class="modal-title" style="font-size: 14px; font-weight: 800; color: #172033; margin-bottom: 2px;">
                                    Riwayat & Hasil Pertemuan – {{ $lead->guest->name ?? 'Klien' }}
                                </h5>
                                <span style="font-size: 11px; color: #778195; font-weight: 600;">
                                    Ditangani oleh: {{ $lead->owner->name ?? '-' }} (PIC)
                                </span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="padding: 20px; color: #334155; font-size: 12px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-bottom: 16px; display: flex; gap: 20px; flex-wrap: wrap;">
                                <div>
                                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase;">Tahap Pipeline Terakhir:</div>
                                    <div style="font-weight: 800; color: #172033;">{{ $leadBadges[$lead->status]['label'] ?? $lead->status }}</div>
                                </div>
                                <div>
                                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal Follow-Up:</div>
                                    <div style="font-weight: 700; color: #006B3F;">{{ $scheduleText }}</div>
                                </div>
                                <div>
                                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase;">Estimasi Value:</div>
                                    <div style="font-weight: 700; color: #172033;">{{ $lead->estimated_value ? rupiah($lead->estimated_value, true) : '-' }}</div>
                                </div>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">Catatan Awal Kunjungan:</label>
                                <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                                    {{ optional($lead->visit)->notes ?? 'Tidak ada catatan awal.' }}
                                </div>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">Hasil Meeting Pertama:</label>
                                <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                                    {{ optional($lead->visit)->meeting_result ?? 'Tidak ada hasil meeting.' }}
                                </div>
                            </div>

                            <div>
                                <label style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 8px;">Riwayat Update Pipeline:</label>
                                @forelse($lead->followUps as $fu)
                                    <div style="background: #fdfdfd; border: 1px solid #e2e8f0; border-left: 4px solid #006B3F; border-radius: 8px; padding: 10px; margin-bottom: 8px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 10px; color: #64748b; flex-wrap: wrap; gap: 4px;">
                                            <span>{{ \Carbon\Carbon::parse($fu->created_at)->translatedFormat('d F Y, H:i') }}</span>
                                            <span>Tahap: <strong style="color: #006B3F;">{{ $leadBadges[$fu->status]['label'] ?? $fu->status }}</strong></span>
                                        </div>
                                        <div style="color: #334155; font-size: 12px; white-space: pre-line;">{{ $fu->result ?? '-' }}</div>
                                        <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 8px;">
                                            <div style="font-size: 10px; color: #006B3F; font-weight: 700;">
                                                Estimasi Value: {{ $fu->estimated_value ? rupiah($fu->estimated_value, true) : '-' }}
                                            </div>
                                            @if($fu->due_at)
                                                <div style="font-size: 10px; color: #475569;">Tanggal Follow Up: {{ \Carbon\Carbon::parse($fu->due_at)->translatedFormat('d F Y') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div style="font-style: italic; color: #94a3b8; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 10px; border-radius: 8px; text-align: center; font-size: 11px;">
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

        <div style="margin-top: 14px;">
            @include('partials.pagination', ['paginator' => $leads])
        </div>
    </div>

</div>
@endsection