@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Laporan & Export Data Kunjungan 📥</h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Unduh rekapitulasi data tamu dan konversi lead dalam format Excel atau PDF untuk laporan bulanan perusahaan.</p>
    </div>

    <!-- Statistik Ringkas Periode -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Kunjungan</span>
            <strong style="font-size: 24px; font-weight: 900; color: #1e3a8a; display: block; margin-top: 4px;">{{ $totalKunjungan }} Kunjungan</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Deal</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; display: block; margin-top: 4px;">{{ $totalDeal }} Klien</strong>
        </div>
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Tamu VIP</span>
            <strong style="font-size: 24px; font-weight: 900; color: #d97706; display: block; margin-top: 4px;">⭐ {{ $totalVip }} Tamu</strong>
        </div>
    </div>

    <!-- Filter & Tombol Export -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">Filter Periode Laporan Bulanan</h3>

        <form action="{{ route('manager.laporan') }}" method="GET" style="display: flex; flex-direction: column; gap: 20px;">

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Pilih Bulan</label>
            <select name="month" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
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
            <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Tahun</label>
            <select name="year" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                @php $currentYear = date('Y'); @endphp
                @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Kategori Tamu</label>
            <select name="category" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                <option value="" {{ $category === '' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="vip" {{ $category === 'vip' ? 'selected' : '' }}>⭐ VIP</option>
                <option value="reguler" {{ $category === 'reguler' ? 'selected' : '' }}>Reguler</option>
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Cabang</label>
            <select name="branch_id" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                <option value="" {{ $branchId === '' ? 'selected' : '' }}>Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">PIC</label>
            <select name="pic_id" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                <option value="" {{ $picId === '' ? 'selected' : '' }}>Semua PIC</option>
                @foreach($picUsers as $pic)
                    <option value="{{ $pic->id }}" {{ $picId == $pic->id ? 'selected' : '' }}>{{ $pic->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; align-items: flex-end;">
            <button type="submit" style="width: 100%; background: #1e3a8a; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; height: 41px;">
                Tampilkan Preview
            </button>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <span style="font-size: 13px; font-weight: 700; color: #172033;">Export File:</span>

        <button type="submit"
            formaction="{{ route('manager.laporan.exportExcel') }}"
            formmethod="GET"
            style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
            📊 Export Bulanan ke Excel (.xlsx)
        </button>

        <button type="submit"
            formaction="{{ route('manager.laporan.exportPdf') }}"
            formmethod="GET"
            style="background: #dc2626; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
            📄 Export Bulanan ke PDF (.pdf)
        </button>
    </div>
</form>
    </div>

    <!-- Tabel Preview Data -->
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
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Tanggal</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Cabang</th>
                        <th style="padding: 14px;">Tujuan PIC</th>
                        <th style="padding: 14px;">Keperluan</th>
                        <th style="padding: 14px;">Produk Diminati</th>
                        <th style="padding: 14px;">Sumber Lead</th>
                        <th style="padding: 14px; text-align: center;">Potential Level</th>
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
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">{{ $visits->firstItem() + $index }}</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">
                            {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">
                                {{ $v->guest->name ?? '-' }}
                                @if(isset($v->guest) && $v->guest->is_vip)
                                    <span title="VIP" style="color: #d97706;">⭐</span>
                                @endif
                            </strong>
                            <span style="font-size: 11px; color: #778195;">{{ $v->guest->company_name ?? '-' }}</span>
                        </td>
                    <td style="padding: 14px; color: #475569;">{{ optional($v->branch)->name ?? '-' }}</td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">{{ $v->assignedUser->name ?? '-' }}</td>
                        <td style="padding: 14px; color: #475569;">{{ optional($v->purpose)->name ?? '-' }}</td>
                        <td style="padding: 14px; color: #475569;">
                            {{ $v->products && $v->products->isNotEmpty() ? $v->products->pluck('name')->implode(', ') : '-' }}
                        </td>
                        <td style="padding: 14px; color: #475569;">{{ optional($v->source)->name ?? '-' }}</td>
                        <td style="padding: 14px; text-align: center;">
                            @if($pb)
                                <span style="background: {{ $pb['bg'] }}; color: {{ $pb['color'] }}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">{{ $pb['label'] }}</span>
                            @else
                                <span style="color: #94a3b8; font-size: 12px;">-</span>
                            @endif
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            @if(in_array($statusLower, ['cancelled', 'dibatalkan', 'ditolak']))
                                <span style="background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Dibatalkan</span>
                            @elseif($isCompleted && $leadStatus)
                                @php $b = $leadBadges[$leadStatus] ?? $leadBadges['new']; @endphp
                                <span style="background: {{ $b['bg'] }}; color: {{ $b['color'] }}; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">{{ $b['label'] }}</span>
                            @elseif($isCompleted)
                                <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Non-Lead</span>
                            @else
                                <span style="background: #fef3c7; color: #b45309; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 24px; color: #94a3b8;">
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