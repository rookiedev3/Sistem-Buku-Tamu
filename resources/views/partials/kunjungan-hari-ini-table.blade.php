@php
    $leadBadges = [
        'new'         => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Baru'],
        'contacted'   => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Dihubungi'],
        'negotiation' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'Negosiasi '],
        'deal'        => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Deal '],
        'lost'        => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Lost'],
    ];

    $visitStatusBadges = [
        'menunggu'        => ['bg' => '#fef3c7', 'color' => '#b45309', 'label' => 'Menunggu'],
        'waiting'         => ['bg' => '#fef3c7', 'color' => '#b45309', 'label' => 'Menunggu'],
        'dikonfirmasi'    => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Dikonfirmasi'],
        'confirmed'       => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Dikonfirmasi'],
        'sedang bertemu'  => ['bg' => '#f1eaff', 'color' => '#6741b5', 'label' => 'Sedang Bertemu'],
        'completed'       => ['bg' => '#e8f8f1', 'color' => '#15803d', 'label' => 'Selesai'],
        'selesai'         => ['bg' => '#e8f8f1', 'color' => '#15803d', 'label' => 'Selesai'],
        'meeting selesai' => ['bg' => '#e8f8f1', 'color' => '#15803d', 'label' => 'Selesai'],
        'cancelled'       => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Dibatalkan'],
        'dibatalkan'      => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Dibatalkan'],
        'ditolak'         => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Dibatalkan'],
        'batal'           => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Dibatalkan'],
    ];
@endphp

<div class="table-responsive">
    <table class="table align-middle" style="font-size:13px; color:#172033; margin:0; min-width:900px;">
        <thead style="background:#f8fafc; color:#5c6678; font-weight:700;">
            <tr>
                <th style="padding:14px; border-top-left-radius:10px; border-bottom-left-radius:10px;">No</th>
                <th style="padding:14px;">Token</th>
                <th style="padding:14px;">Tamu & Jabatan</th>
                <th style="padding:14px;">Waktu</th>
                <th style="padding:14px;">Jenis Kunjungan</th>
                <th style="padding:14px;">Keperluan</th>
                <th style="padding:14px;">PIC / Sales</th>
                <th style="padding:14px; text-align:center;">Catatan</th>
                <th style="padding:14px; text-align:center;">Status Kunjungan</th>
                <th style="padding:14px; border-top-right-radius:10px; border-bottom-right-radius:10px; text-align:center;">Status Lead</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    $statusLower = strtolower(trim($visit->status ?? ''));
                    $leadStatus = optional($visit->lead)->status;
                    $vb = $visitStatusBadges[$statusLower] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => $visit->status ?? '-'];
                    $catName  = $visit->guest->category->name ?? 'Reguler';
                    $catColor = $visit->guest->category->color ?? '#006B3F';
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:14px; font-weight:600;">{{ $index + 1 }}</td>

                    <td style="padding:14px;">
                        <strong style="color:#006B3F; font-weight:800;">
                            {{ $visit->visit_code ?? ('VST-' . str_pad($visit->id, 4, '0', STR_PAD_LEFT)) }}
                        </strong>
                    </td>

                    <td style="padding:14px;">
                        <strong style="display:block; color:#172033; font-weight:800;">
                            {{ $visit->guest->name ?? '-' }}
                            @if(isset($visit->guest) && $visit->guest->is_vip)
                                <span title="VIP" style="color:#d97706;"></span>
                            @endif
                        </strong>
                        <span style="font-size:11px; color:#778195;">
                            {{ $visit->guest->company_name ?? '-' }} ({{ $visit->guest->position ?? '-' }})
                        </span>
                    </td>

                    <td style="padding:14px; color:#778195; font-weight:600;">
                        {{ $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->format('H:i') . ' WIB' : '-' }}
                    </td>

                    <td style="padding:14px;">
                        <span style="background:{{ $catColor }}22; color:{{ $catColor }}; padding:3px 8px; border-radius:6px; font-size:11px; font-weight:800;">
                            {{ $catName }}
                        </span>
                    </td>

                    <td style="padding:14px; color:#475569;">{{ $visit->purpose->name ?? '-' }}</td>

                    <td style="padding:14px; color:#475569; font-weight:600;">{{ $visit->assignedUser->name ?? '-' }}</td>

                    <td style="padding:14px; text-align:center;">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $visit->id }}" style="background:transparent; color:#006B3F; border:1px solid #006B3F; padding:6px 12px; border-radius:8px; font-size:11px; font-weight:700; cursor:pointer;">
                             Lihat Catatan
                        </button>
                    </td>

                    <td style="padding:14px; text-align:center;">
                        <span style="background:{{ $vb['bg'] }}; color:{{ $vb['color'] }}; padding:6px 12px; border-radius:20px; font-size:11px; font-weight:800;">{{ $vb['label'] }}</span>
                    </td>

                    <td style="padding:14px; text-align:center;">
                        @if($leadStatus)
                            @php $lb = $leadBadges[$leadStatus] ?? $leadBadges['new']; @endphp
                            <span style="background:{{ $lb['bg'] }}; color:{{ $lb['color'] }}; padding:6px 12px; border-radius:20px; font-size:11px; font-weight:800;">{{ $lb['label'] }}</span>
                        @else
                            <span style="background:#f8fafc; color:#94a3b8; padding:6px 12px; border-radius:20px; font-size:11px; font-weight:800;">Bukan Lead</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding:24px; color:#94a3b8;">
                        Tidak ada data kunjungan yang cocok.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px; flex-wrap: wrap; gap: 8px;">
    <span>Menampilkan data monitoring real-time</span>
    <span>Total Data: {{ $visits->count() }}</span>
</div>

{{-- ============================================== --}}
{{-- MODAL CATATAN PER KUNJUNGAN                     --}}
{{-- ============================================== --}}
@foreach($visits as $visit)
    @php
        $leadModal = $visit->lead ?? null;
        $scheduleTextMap = ['deal' => 'Sudah Deal ', 'lost' => 'Lead Hilang / Lost'];
        $scheduleText = $leadModal
            ? ($scheduleTextMap[$leadModal->status] ?? ($leadModal->follow_up_at ? \Carbon\Carbon::parse($leadModal->follow_up_at)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan'))
            : 'Kunjungan biasa, tidak dikonversi jadi lead';
    @endphp

    <div class="modal fade" id="noteModal{{ $visit->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                    <div>
                        <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 2px;">
                            Riwayat & Hasil Pertemuan – {{ $visit->guest->name ?? 'Tamu' }}
                        </h5>
                        <span style="font-size: 11px; color: #778195; font-weight: 600;">
                            Ditangani oleh: {{ $visit->assignedUser->name ?? '-' }} (PIC)
                        </span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; gap: 20px;">
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Tahap Pipeline Terakhir:</div>
                            <div style="font-weight: 800; color: #172033;">
                                {{ $leadModal ? ($leadBadges[$leadModal->status]['label'] ?? $leadModal->status) : 'Bukan Lead' }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal / Keterangan Status:</div>
                            <div style="font-weight: 700; color: #006B3F;">{{ $scheduleText }}</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;"> Catatan Pertemuan Awal:</label>
                        <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                            {{ $visit->meeting_result ?? 'Tidak ada catatan awal yang ditinggalkan.' }}
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 8px;"> Riwayat Update Pipeline:</label>
                        @forelse(optional($leadModal)->followUps ?? [] as $fu)
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
@endforeach