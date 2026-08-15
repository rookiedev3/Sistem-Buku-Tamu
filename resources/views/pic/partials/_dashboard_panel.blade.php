@php
    $visits = $visits ?? collect();
    $vipCount = $vipCount ?? 0;
    $regularCount = $regularCount ?? 0;
    $activeFilter = $filter ?? 'all';
    $activeVipFilter = $vipFilter ?? 'all';
@endphp

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu VIP Menunggu</span>
        <strong style="display: block; font-size: 19px; font-weight: 900; color: #d97706; margin-top: 2px;">{{ $vipCount }} Orang</strong>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 14px 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu Reguler</span>
        <strong style="display: block; font-size: 19px; font-weight: 900; color: #013220; margin-top: 2px;">{{ $regularCount }} Orang</strong>
    </div>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 14px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
        <h3 style="font-size: 14px; font-weight: 800; color: #172033; margin: 0;">Daftar Tamu Masuk & Kategori Pelanggan</h3>
        <span style="font-size: 11px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px;">

        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            @php
                $filterOptions = [
                    'all'      => 'Semua',
                    'today'    => 'Hari Ini' . ($countToday > 0 ? " ({$countToday})" : ''),
                    'upcoming' => 'Terjadwal Mendatang' . ($countUpcoming > 0 ? " ({$countUpcoming})" : ''),
                ];
            @endphp

            @foreach($filterOptions as $key => $label)
                @php
                    $isActive = $activeFilter === $key;
                @endphp
                {{-- 'page' SENGAJA dibuang dari query lama sebelum di-merge, supaya tiap ganti tab filter
                     selalu mulai dari halaman 1. Kalau tidak, page lama (misal page=2) ikut kebawa dan
                     bisa jadi kosong karena tab baru datanya lebih sedikit. --}}
                <a href="{{ route('pic.dashboard', array_merge(request()->except('page'), ['filter' => $key])) }}"
                   style="background: {{ $isActive ? '#013220' : '#f1f5f9' }}; color: {{ $isActive ? '#ffffff' : '#475569' }}; padding: 6px 12px; border-radius: 16px; font-size: 11px; font-weight: 700; text-decoration: none; border: 1px solid {{ $isActive ? '#006B3F' : '#e2e8f0' }};">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div style="display: flex; align-items: center; gap: 6px;">
            <label style="font-size: 11px; font-weight: 700; color: #5c6678;">Status:</label>
            {{-- data-role="vip-status" dipakai JS di dashboard.blade.php buat nangkep perubahan tanpa reload --}}
            <select data-role="vip-status" style="height: 32px; padding: 4px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 11px; font-weight: 700; color: #172033; background: #fff; outline: none; cursor: pointer;">
                @php
                    $vipOptions = [
                        'all'     => 'Semua Status',
                        'vip'     => '⭐ VIP',
                        'reguler' => 'Reguler',
                    ];
                @endphp
                @foreach($vipOptions as $key => $label)
                    {{-- Sama seperti tab filter di atas: 'page' dibuang dulu supaya ganti Status VIP/Reguler
                         juga selalu balik ke halaman 1. --}}
                    <option
                        value="{{ route('pic.dashboard', array_merge(request()->except('page'), ['vip_status' => $key])) }}"
                        {{ $activeVipFilter === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- Mengalir natural seperti frontoffice/dashboard, scroll ikut halaman (bukan kotak scroll sendiri) --}}
    <div style="overflow-x: auto;">
        <table class="table align-middle" style="font-size: 12px; color: #172033; margin: 0; width: 100%;">
            <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                <tr>
                    <th style="padding: 8px 10px;">No</th>
                    <th style="padding: 8px 10px;">Token</th>
                    <th style="padding: 8px 10px;">Tamu & Jabatan</th>
                    <th style="padding: 8px 10px;">Waktu</th>
                    <th style="padding: 8px 10px;">Jenis Kunjungan</th>
                    <th style="padding: 8px 10px;">Keperluan</th>
                    <th style="padding: 8px 10px; text-align: center;">Catatan</th>
                    <th style="padding: 8px 10px; text-align: center;">Konfirmasi Kehadiran</th>
                    <th style="padding: 8px 10px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $index => $visit)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 8px 10px; font-weight: 600;">{{ $visits->firstItem() + $index }}</td>

                        {{-- TOKEN: sebelumnya kosong / ketimpa data tamu, sekarang benar-benar visit_code --}}
                        <td style="padding: 8px 10px;">
                            <strong style="color: #006B3F; font-weight: 800;">
                                {{ $visit->visit_code ?? ('VST-' . str_pad($visit->id, 4, '0', STR_PAD_LEFT)) }}
                            </strong>
                        </td>
                        
                        <td style="padding: 8px 10px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">
                                {{ $visit->guest->name ?? '-' }}
                                @if(isset($visit->guest) && $visit->guest->is_vip)
                                    <span title="VIP" style="color: #d97706;">⭐</span>
                                @endif
                            </strong>
                            {{-- Ditambah perusahaan, meniru frontoffice/dashboard: "Instansi (Jabatan)" --}}
                            <span style="font-size: 10px; color: #778195;">
                                {{ $visit->guest->company_name ?? '-' }} ({{ $visit->guest->position ?? '-' }})
                            </span>
                        </td>

                        <td style="padding: 8px 10px; color: #778195; font-weight: 600;">
                            @if($visit->check_in_at)
                                {{ \Carbon\Carbon::parse($visit->check_in_at)->format('H:i') }} WIB
                            @elseif($visit->scheduled_at)
                                {{ \Carbon\Carbon::parse($visit->scheduled_at)->format('H:i') }} WIB
                            @else
                                -
                            @endif

                            {{-- Tanggal selalu diambil dari scheduled_at, tampil terus (bukan cuma pas belum check-in) --}}
                            @if($visit->scheduled_at)
                                @php $schedDate = \Carbon\Carbon::parse($visit->scheduled_at); @endphp
                                @if($schedDate->isToday())
                                    <div style="font-size: 9px; color: #d97706; margin-top: 2px; font-weight: 700;">🔥 Hari Ini</div>
                                @else
                                    <div style="font-size: 9px; color: #1d4ed8; margin-top: 2px; font-weight: 700;">📅 {{ $schedDate->translatedFormat('d M Y') }}</div>
                                @endif
                            @endif
                        </td>

                        <td style="padding: 8px 10px;">
                            @php
                                $catName  = $visit->guest->category->name  ?? '-';
                                $catColor = $visit->guest->category->color ?? '#006B3F';
                            @endphp
                            <span style="background: {{ $catColor }}22; color: {{ $catColor }}; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 800;">
                                {{ $catName }}
                            </span>
                        </td>

                        <td style="padding: 8px 10px; color: #475569;">{{ $visit->purpose->name ?? '-' }}</td>

                        <td style="padding: 8px 10px; text-align: center;">
                            @if(!empty($visit->notes))
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalCatatanTamu-{{ $visit->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700; cursor: pointer;">
                                📝 Lihat
                            </button>
                            @else
                            <span style="font-style: italic; color: #94a3b8; font-size: 11px;">-</span>
                            @endif
                        </td>

                        <td style="padding: 8px 10px; text-align: center;">
                            @php $statusLower = strtolower($visit->status); @endphp

                            @if(in_array($statusLower, ['pending', 'waiting', 'menunggu']))
                                <div style="display: flex; justify-content: center; gap: 4px;">
                                    <form action="{{ route('pic.updateStatus', $visit->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Dikonfirmasi">
                                        <button type="submit" title="Konfirmasi Benar Bertemu" style="background: #e6f4ed; color: #006B3F; border: 1px solid #bbf7d0; width: 26px; height: 26px; border-radius: 7px; font-size: 12px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">✓</button>
                                    </form>

                                    <form action="{{ route('pic.updateStatus', $visit->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Dibatalkan">
                                        <button type="submit" title="Tolak / Salah Tujuan" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; width: 26px; height: 26px; border-radius: 7px; font-size: 12px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
                                    </form>
                                </div>
                            @elseif($statusLower === 'terjadwal')
                                <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Terjadwal</span>
                            @elseif(in_array($statusLower, ['confirmed', 'dikonfirmasi']))
                                <span style="background: #dcfce7; color: #15803d; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Dikonfirmasi ✓</span>
                            @elseif(in_array($statusLower, ['cancelled', 'dibatalkan']))
                                <span style="background: #fee2e2; color: #b91c1c; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Dibatalkan ✕</span>
                            @elseif(in_array($statusLower, ['meeting', 'sedang bertemu']))
                                <span style="background: #f1eaff; color: #6741b5; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Sedang Bertemu</span>
                            @elseif($statusLower === 'meeting selesai' || !empty($visit->meeting_result))
                                <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Meeting Selesai</span>
                            @elseif(in_array($statusLower, ['completed', 'selesai']))
                                <span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Selesai</span>
                            @else
                                <span style="background: #dcfce7; color: #15803d; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Dikonfirmasi ✓</span>
                            @endif
                        </td>

                        <td style="padding: 8px 10px; text-align: center;">
                            @php $statusLower = strtolower($visit->status); @endphp

                            @if($statusLower === 'terjadwal')
                            <button type="button" disabled style="background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: not-allowed;">
                                Belum Check-In
                            </button>
                            @elseif(in_array($statusLower, ['confirmed', 'dikonfirmasi']))
                            <form action="{{ route('pic.startMeeting', $visit->id) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: #006B3F; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    Mulai Pertemuan
                                </button>
                            </form>
@elseif(in_array($statusLower, ['meeting', 'sedang bertemu']))
<button type="button" data-bs-toggle="modal" data-bs-target="#modalCatatPertemuan-{{ $visit->id }}" style="background: #d97706; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
    📝 Catat Hasil
</button>
@elseif($statusLower === 'meeting selesai')
<span style="color: #0d9488; font-size: 11px; font-weight: 700;">✔ Hasil Tercatat</span>
                            @else
                            <button type="button" disabled style="background: #cbd5e1; color: #64748b; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                Mulai Pertemuan
                            </button>
                            @endif
                        </td>
                    </tr>

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

                                    <form action="{{ route('pic.completeMeeting', $visit->id) }}" method="POST" class="js-complete-meeting-form" onsubmit="return validateFollowUpDate(this);">
    @csrf
    <input type="hidden" name="_visit_id" value="{{ $visit->id }}">

    <div style="margin-bottom: 16px;">
        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Catatan / Ringkasan Diskusi<span class="js-followup-required-mark" style="color: #dc2626;">*</span></label>
        <textarea name="meeting_result" rows="3" required placeholder="Tuliskan hasil obrolan atau permintaan khusus klien di sini..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">{{ old('_visit_id') == $visit->id ? old('meeting_result') : $visit->meeting_result }}</textarea>
    </div>

    <div style="margin-bottom: 16px;">
    <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Potensi Klien <span class="js-followup-required-mark" style="color: #dc2626;">*</span></label>
    @php
        $selectedPotential = old('_visit_id') == $visit->id ? old('potential_level') : $visit->potential_level;
    @endphp
    <select name="potential_level" id="potential_level-{{ $visit->id }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
        <option value="hot" {{ $selectedPotential == 'hot' ? 'selected' : '' }}>Hot Lead</option>
        <option value="warm" {{ $selectedPotential == 'warm' ? 'selected' : '' }}>Warm Lead</option>
        <option value="cold" {{ $selectedPotential == 'cold' ? 'selected' : '' }}>Cold</option>
        <option value="non_lead" {{ $selectedPotential == 'non_lead' ? 'selected' : '' }}>Non-Lead</option>
        <option value="deal" {{ $selectedPotential == 'deal' ? 'selected' : '' }}>🎉 Deal</option>
    </select>
</div>

{{-- Estimasi Nilai: cuma muncul untuk Hot/Warm/Deal, wajib khusus Deal --}}
<div id="estimatedValueGroup-{{ $visit->id }}" style="margin-bottom: 16px; display: none;">
    <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">
        Estimasi Nilai (Rp)
        <span class="js-followup-required-mark" style="color: #dc2626;">*</span>
    </label>
    @php
        $existingEstValue = $visit->lead->estimated_value ?? null;
    @endphp
    <div style="position: relative; display: flex; align-items: center; width: 100%;">
        <div style="position: absolute; left: 14px; color: #006B3F; font-weight: 700; font-size: 13px; pointer-events: none;">Rp</div>
        <input type="text"
            inputmode="numeric"
            autocomplete="off"
            class="rupiah-input"
            data-hidden-target="estimatedValueRaw{{ $visit->id }}"
            data-has-existing="{{ ($existingEstValue && (float) $existingEstValue > 0) ? '1' : '0' }}"
            id="estimatedValueDisplay{{ $visit->id }}"
            value=""
            required
            placeholder="Contoh: 5.000.000"
            style="width: 100%; padding: 10px 14px 10px 34px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; box-sizing: border-box;">
    </div>
    <input type="hidden" name="estimated_value" id="estimatedValueRaw{{ $visit->id }}" value="">
    <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0 0;">
        Nilai saat ini: <strong style="color: #475569;">{{ $existingEstValue ? rupiah($existingEstValue, true) : 'Belum diisi' }}</strong>.
        Kosongkan kalau belum ada perubahan dari estimasi sebelumnya.
    </p>
</div>

<div style="margin-bottom: 20px;">
        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">
            Jadwal Follow-Up Berikutnya
            {{-- <span class="js-followup-required-mark" id="followUpRequiredMark-{{ $visit->id }}" style="color: #dc2626;">*</span> --}}
        </label>

        <div style="position: relative; display: flex; align-items: center; width: 100%;">
            <div style="position: absolute; left: 14px; display: flex; align-items: center; justify-content: center; pointer-events: none; color: #006B3F;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>

            @php
                $followUpValue = old('_visit_id') == $visit->id
                    ? old('follow_up_at')
                    : ($visit->follow_up_at ? \Carbon\Carbon::parse($visit->follow_up_at)->format('Y-m-d') : '');
            @endphp
            <input type="text" id="follow_up_at-{{ $visit->id }}" name="follow_up_at"
                value="{{ $followUpValue }}"
                placeholder="Pilih tanggal follow-up..." readonly
                style="width: 100%; padding: 10px 14px 10px 44px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fbfcfe; cursor: pointer; box-sizing: border-box; font-family: inherit;">
        </div>
        <small class="js-followup-date-error" style="display: none; color: #dc2626; font-size: 11px; margin-top: 4px;">
            {{ $errors->first('follow_up_at') ?: 'Tanggal follow-up wajib dipilih.' }}
        </small>
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

                @empty
                <tr>
                    <td colspan="9" style="padding: 24px; text-align: center; color: #778195; font-weight: 600; font-size: 12px;">
                        Belum ada kunjungan tamu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 14px;">
        @include('partials.pagination', ['paginator' => $visits])
    </div>

</div>

<script>
    // Cegah submit "Catat Hasil Pertemuan & Lead" tanpa tanggal follow-up.
    // Dipasang lewat window supaya tetap ada walau panel ini di-swap ulang via AJAX
    // (lihat script initRowWidgets di dashboard.blade.php).
function validateFollowUpDate(form) {
    const dateInput = form.querySelector('input[name="follow_up_at"]');
    const potentialSelect = form.querySelector('select[name="potential_level"]');
    const errorEl = form.querySelector('.js-followup-date-error');

    const rupiahInput = form.querySelector('.rupiah-input');
    const hiddenInput = form.querySelector("input[name='estimated_value']");

    const isDeal = potentialSelect && potentialSelect.value === 'deal';
    const isDateOptional = potentialSelect && ['cold', 'non_lead'].includes(potentialSelect.value);

    if (!isDateOptional && !dateInput.value) {
        if (errorEl) errorEl.style.display = 'block';
        dateInput.style.borderColor = '#dc2626';
        dateInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }

    if (isDeal && rupiahInput && hiddenInput) {
        const hasExisting = rupiahInput.dataset.hasExisting === '1';
        const hasNewValue = !!hiddenInput.value;

        if (!hasNewValue && !hasExisting) {
            rupiahInput.classList.add('is-invalid');
            rupiahInput.focus();
            rupiahInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            alert('Estimasi Nilai Deal wajib diisi sebelum bisa ditandai Deal.');
            return false;
        }
    }

    if (errorEl) errorEl.style.display = 'none';
    dateInput.style.borderColor = '#e8edf5';

    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = 'Menyimpan...';
    return true;
}
</script>
@if ($errors->any() && old('_visit_id'))
<script>
(function () {
    var visitId = "{{ old('_visit_id') }}";
    var modalEl = document.getElementById('modalCatatPertemuan-' + visitId);
    if (!modalEl) return;

    function openErrorModal() {
        var errorSmall = modalEl.querySelector('.js-followup-date-error');
        var dateInput = modalEl.querySelector('input[name="follow_up_at"]');

        @if ($errors->has('follow_up_at'))
            if (errorSmall) errorSmall.style.display = 'block';
            if (dateInput) dateInput.style.borderColor = '#dc2626';
        @endif

        if (window.bootstrap && window.bootstrap.Modal) {
            var modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    // Modal butuh Bootstrap JS sudah kebaca & flatpickr sudah nyala di panel ini
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openErrorModal);
    } else {
        openErrorModal();
    }
})();
</script>
@endif