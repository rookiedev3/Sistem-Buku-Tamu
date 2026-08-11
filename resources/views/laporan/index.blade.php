@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Header Section -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Laporan & Export Data Kunjungan
        </h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Unduh rekapitulasi data tamu, durasi, dan konversi lead dalam format Excel atau PDF untuk laporan bulanan perusahaan.</p>
    </div>

    <!-- Grid KPI Stat Cards (5 Kolom, Kompak & Rapi) -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px;">Total Kunjungan</span>
            <strong style="font-size: 18px; font-weight: 800; color: #1e3a8a; display: block; margin-top: 2px;">{{ $totalKunjungan }} Kunjungan</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px;">Total Deal</span>
            <strong style="font-size: 18px; font-weight: 800; color: #013220; display: block; margin-top: 2px;">{{ $totalDeal }} Klien</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px;">Conversion Rate</span>
            <strong style="font-size: 18px; font-weight: 800; color: #2563eb; display: block; margin-top: 2px;">{{ $conversionRate ?? 0 }}%</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px;">Rata-rata Durasi</span>
            <strong style="font-size: 18px; font-weight: 800; color: #475569; display: block; margin-top: 2px;">{{ round($avgDuration ?? 0) }} Menit</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 12px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <span style="font-size: 10px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px;">Tamu VIP</span>
            <strong style="font-size: 18px; font-weight: 800; color: #d97706; display: block; margin-top: 2px;">⭐ {{ $totalVip }} Tamu</strong>
        </div>
    </div>

    <!-- Filter Periode Laporan Bulanan (6 Kolom Sejajar dan Kompak) -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 14px; font-weight: 800; color: #172033; margin-bottom: 14px;">Filter Periode Laporan Bulanan</h3>

        <form action="{{ route('laporan.index') }}" method="GET" style="display: flex; flex-direction: column; gap: 16px;">

            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px;">
                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 4px; text-transform: uppercase;">Pilih Bulan</label>
                    <select name="month" style="width: 100%; padding: 8px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ];
                        @endphp
                        @foreach($months as $num => $label)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 4px; text-transform: uppercase;">Tahun</label>
                    <select name="year" style="width: 100%; padding: 8px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 4px; text-transform: uppercase;">Kategori Tamu</label>
                    <select name="category" style="width: 100%; padding: 8px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                        <option value="" {{ $category === '' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="vip" {{ $category === 'vip' ? 'selected' : '' }}>⭐ VIP</option>
                        <option value="reguler" {{ $category === 'reguler' ? 'selected' : '' }}>Reguler</option>
                    </select>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 4px; text-transform: uppercase;">Cabang</label>
                    <select name="branch_id" style="width: 100%; padding: 8px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                        <option value="" {{ $branchId === '' ? 'selected' : '' }}>Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 4px; text-transform: uppercase;">PIC</label>
                    <select name="pic_id" style="width: 100%; padding: 8px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                        <option value="" {{ $picId === '' ? 'selected' : '' }}>Semua PIC</option>
                        @foreach($picUsers as $pic)
                            <option value="{{ $pic->id }}" {{ $picId == $pic->id ? 'selected' : '' }}>{{ $pic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" style="width: 100%; background: #1e3a8a; color: #fff; border: none; padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; height: 35px;">
                        Tampilkan Preview
                    </button>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

            <div style="display: flex; gap: 10px; align-items: center;">
                <span style="font-size: 12px; font-weight: 700; color: #172033;">Export File:</span>

                <button type="submit"
                    formaction="{{ route('laporan.exportExcel') }}"
                    formmethod="GET"
                    style="background: #013220; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    📊 Export Bulanan ke Excel (.xlsx)
                </button>

                <button type="submit"
                    formaction="{{ route('laporan.exportPdf') }}"
                    formmethod="GET"
                    style="background: #e5484d; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    📄 Export Bulanan ke PDF (.pdf)
                </button>
            </div>
        </form>
    </div>

    <!-- Data Preview Table -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">
            Preview Data — {{ $months[$month] ?? $month }} {{ $year }}
        </h3>

        @php
            $leadBadges = [
                'new'         => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Baru'],
                'contacted'   => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => 'Dihubungi'],
                'negotiation' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'Negosiasi 🔥'],
                'deal'        => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Deal 🎉'],
                'lost'        => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Lost'],
            ];
            $potentialBadges = [
                'hot'      => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Hot 🔥'],
                'warm'     => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => 'Warm'],
                'cold'     => ['bg' => '#e0f2fe', 'color' => '#0284c7', 'label' => 'Cold'],
                'non_lead' => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Non-Lead'],
            ];
        @endphp

        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0; min-width: 1200px;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px; width: 50px;">No</th>
                        <th style="padding: 14px;">Waktu & Durasi</th>
                        <th style="padding: 14px;">Tamu & Kontak</th>
                        <th style="padding: 14px;">Cabang & PIC</th>
                        <th style="padding: 14px;">Tujuan & Produk</th>
                        <th style="padding: 14px;">Sumber & Potensi</th>
                        <th style="padding: 14px;">Catatan Hasil</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $index => $v)
                    @php
                        $statusLower = strtolower(trim($v->status ?? ''));
                        $isCompleted = in_array($statusLower, ['completed', 'selesai', 'meeting selesai']);
                        $leadStatus = optional($v->lead)->status;
                        $pb = $potentialBadges[$v->potential_level] ?? null;

                        $durasi = '-';
                        if($v->check_in_at && $v->check_out_at) {
                            $durasiMinutes = \Carbon\Carbon::parse($v->check_in_at)->diffInMinutes(\Carbon\Carbon::parse($v->check_out_at));
                            $durasi = round($durasiMinutes) . ' Menit';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">{{ $visits->firstItem() + $index }}</td>

                        <td style="padding: 14px;">
                            <span style="color: #475569; display: block; font-weight: 600;">
                                In: {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->translatedFormat('d M Y, H:i') : '-' }}
                            </span>
                            <span style="font-size: 11px; color: #778195; display: block;">
                                Out: {{ $v->check_out_at ? \Carbon\Carbon::parse($v->check_out_at)->format('H:i') : '-' }}
                            </span>
                            <span style="font-size: 11px; color: #2563eb; font-weight: 600;">⏱️ {{ $durasi }}</span>
                        </td>

                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">
                                {{ $v->guest->name ?? '-' }}
                                @if(isset($v->guest) && $v->guest->is_vip)
                                    <span title="VIP" style="color: #d97706;">⭐</span>
                                @endif
                            </strong>
                            <span style="font-size: 11px; color: #778195; display: block;">{{ $v->guest->company_name ?? '-' }}</span>
                            <span style="font-size: 11px; color: #475569;">📞 {{ $v->guest->phone ?? '-' }}</span>
                        </td>

                        <td style="padding: 14px;">
                            <span style="color: #475569; display: block;">🏢 {{ optional($v->branch)->name ?? '-' }}</span>
                            <span style="color: #475569; display: block; font-weight: 600; font-size: 11px;">👤 {{ $v->assignedUser->name ?? '-' }}</span>
                        </td>

                        <td style="padding: 14px; color: #475569;">
                            <span style="display: block; font-weight: 600;">{{ optional($v->purpose)->name ?? '-' }}</span>
                            <span style="font-size: 11px; color: #778195;">
                                {{ $v->products && $v->products->isNotEmpty() ? $v->products->pluck('name')->implode(', ') : 'Tanpa Produk' }}
                            </span>
                        </td>

                        <td style="padding: 14px;">
                            <span style="color: #475569; display: block; font-size: 12px; margin-bottom: 4px;">{{ optional($v->source)->name ?? '-' }}</span>
                            @if($pb)
                                <span style="background: {{ $pb['bg'] }}; color: {{ $pb['color'] }}; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 800; display: inline-block;">{{ $pb['label'] }}</span>
                            @endif
                        </td>

                        <td style="padding: 14px; color: #475569; max-width: 200px;">
                            <span style="font-size: 11px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $v->notes ?? 'Tidak ada catatan.' }}
                            </span>
                        </td>

                        <td style="padding: 14px; text-align: center;">
                            @if(in_array($statusLower, ['cancelled', 'dibatalkan', 'ditolak']))
                                <span style="background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Dibatalkan</span>
                            @elseif($isCompleted && $leadStatus)
                                @php $b = $leadBadges[$leadStatus] ?? $leadBadges['new']; @endphp
                                <span style="background: {{ $b['bg'] }}; color: {{ $b['color'] }}; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; display: block; margin-bottom: 4px;">{{ $b['label'] }}</span>
                                {{-- <span style="font-size: 10px; color: #15803d; font-weight: 600;">Selesai</span> --}}
                            @elseif($isCompleted)
                                <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Non-Lead</span>
                            @else
                                <span style="background: #fef3c7; color: #b45309; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Tidak ada data kunjungan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            @include('partials.pagination', ['paginator' => $visits])
        </div>
    </div>

</div>
@endsection