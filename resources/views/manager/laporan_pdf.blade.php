<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan - {{ $monthLabel }} {{ $year }}</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #172033; }

        .letterhead {
            display: table;
            width: 100%;
            border-bottom: 2px solid #006B3F;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .letterhead .company {
            display: table-cell;
            vertical-align: middle;
        }
        .letterhead .company h1 {
            font-size: 18px;
            margin: 0;
            color: #006B3F;
        }
        .letterhead .company p {
            font-size: 10px;
            color: #64748b;
            margin: 2px 0 0;
        }
        .letterhead .meta {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }

        h2 { margin-bottom: 2px; font-size: 16px; }
        p.subtitle { color: #64748b; margin-top: 0; margin-bottom: 14px; font-size: 11px; }

        .narrative {
            background: #f8fafc;
            border-left: 3px solid #006B3F;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 10.5px;
            line-height: 1.6;
            color: #334155;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }
        .stats-grid .stat-box {
            display: table-cell;
            width: 20%;
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: left;
        }
        .stats-grid .stat-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #778195;
            font-weight: 700;
        }
        .stats-grid .stat-value {
            font-size: 13px;
            font-weight: 700;
            color: #172033;
            margin-top: 2px;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #e2e8f0; padding: 4px 5px; text-align: left; word-wrap: break-word; }
        th { background: #f8fafc; font-weight: 700; }
        th.center, td.center { text-align: center; }

        .footer-note {
            margin-top: 18px;
            font-size: 8.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="letterhead">
        <div class="company">
            <h1>IT Solution Yogyakarta</h1>
            <p>Sistem Buku Tamu Digital - Laporan Internal</p>
        </div>
        <div class="meta">
            Dicetak oleh: {{ $generatedBy }}<br>
            Pada: {{ $generatedAt->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

    <h2>Laporan Kunjungan Tamu</h2>
    <p class="subtitle">
        Periode: {{ $monthLabel }} {{ $year }}
        @if($category) - Kategori: {{ ucfirst($category) }} @endif
        @if($branchName) - Cabang: {{ $branchName }} @endif
        @if($picName) - PIC: {{ $picName }} @endif
    </p>

    <div class="narrative">
        Selama periode <strong>{{ $monthLabel }} {{ $year }}</strong>, IT Solution Yogyakarta menerima
        <strong>{{ $totalKunjungan }} kunjungan tamu</strong>@if($totalVip > 0), dengan <strong>{{ $totalVip }} di antaranya berkategori VIP</strong>@endif.
        Dari seluruh kunjungan tersebut, tercatat <strong>{{ $totalDeal }} konversi menjadi klien (deal)</strong>,
        sehingga conversion rate periode ini sebesar <strong>{{ $conversionRate }}%</strong>.
        @if($topSourceName)
            Sumber tamu terbanyak berasal dari <strong>{{ $topSourceName }}</strong> ({{ $topSourceCount }} kunjungan).
        @endif
        @if($topPicName)
            PIC dengan jumlah penanganan kunjungan terbanyak adalah <strong>{{ $topPicName }}</strong> ({{ $topPicCount }} kunjungan).
        @endif
        @if($avgDuration !== null)
            Rata-rata durasi pertemuan (check-in sampai check-out) adalah <strong>{{ round($avgDuration) }} menit</strong>.
        @endif
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Kunjungan</div>
            <div class="stat-value">{{ $totalKunjungan }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Deal</div>
            <div class="stat-value">{{ $totalDeal }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Conversion Rate</div>
            <div class="stat-value">{{ $conversionRate }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Tamu VIP</div>
            <div class="stat-value">{{ $totalVip }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Rata-rata Durasi</div>
            <div class="stat-value">{{ $avgDuration !== null ? round($avgDuration) . ' mnt' : '-' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="center">No</th>
                <th>Tanggal</th>
                <th class="center">Jam Masuk</th>
                <th class="center">Jam Keluar</th>
                <th class="center">Durasi</th>
                <th>Nama Tamu</th>
                <th class="center">Status VIP</th>
                <th>Instansi</th>
                <th>Telepon</th>
                <th>Cabang</th>
                <th>PIC</th>
                <th>Keperluan</th>
                <th>Produk Diminati</th>
                <th>Sumber Lead</th>
                <th>Potential Level</th>
                <th>Catatan</th>
                <th class="center">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
                $leadLabels = ['new' => 'Baru', 'contacted' => 'Dihubungi', 'negotiation' => 'Negosiasi', 'deal' => 'Deal', 'lost' => 'Lost'];
                $potentialLabels = ['hot' => 'Hot', 'warm' => 'Warm', 'cold' => 'Cold', 'non_lead' => 'Non-Lead'];
            @endphp
            @forelse($visits as $index => $v)
            @php
                $statusLower = strtolower(trim($v->status ?? ''));
                $isCompleted = in_array($statusLower, ['completed', 'selesai', 'meeting selesai']);
                $leadStatus = optional($v->lead)->status;

                if (in_array($statusLower, ['cancelled', 'dibatalkan', 'ditolak'])) {
                    $statusAkhir = 'Dibatalkan';
                } elseif ($isCompleted && $leadStatus) {
                    $statusAkhir = $leadLabels[$leadStatus] ?? ucfirst($leadStatus);
                } elseif ($isCompleted) {
                    $statusAkhir = 'Non-Lead';
                } else {
                    $statusAkhir = 'Menunggu';
                }

                // FIX: Carbon 3.x mengembalikan float pada diffInMinutes(), bukan int
                // seperti di Carbon 2.x. Dibulatkan supaya tidak menampilkan angka
                // desimal panjang seperti "51.683333333333 mnt".
                $durasi = '-';
                if ($v->check_in_at && $v->check_out_at) {
                    $menit = (int) round(
                        \Carbon\Carbon::parse($v->check_in_at)->diffInMinutes(\Carbon\Carbon::parse($v->check_out_at))
                    );
                    $durasi = $menit . ' mnt';
                }
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->translatedFormat('d F Y') : '-' }}</td>
                <td class="center">{{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->format('H:i') : '-' }}</td>
                <td class="center">{{ $v->check_out_at ? \Carbon\Carbon::parse($v->check_out_at)->format('H:i') : '-' }}</td>
                <td class="center">{{ $durasi }}</td>
                <td>{{ $v->guest->name ?? '-' }}</td>
                <td class="center">{{ (isset($v->guest) && $v->guest->is_vip) ? 'VIP' : 'Reguler' }}</td>
                <td>{{ $v->guest->company_name ?? '-' }}</td>
                <td>{{ $v->guest->phone ?? '-' }}</td>
                <td>{{ optional($v->branch)->name ?? '-' }}</td>
                <td>{{ $v->assignedUser->name ?? '-' }}</td>
                <td>{{ optional($v->purpose)->name ?? '-' }}</td>
                <td>{{ $v->products && $v->products->isNotEmpty() ? $v->products->pluck('name')->implode(', ') : '-' }}</td>
                <td>{{ optional($v->source)->name ?? '-' }}</td>
                <td>{{ $potentialLabels[$v->potential_level] ?? '-' }}</td>
                <td>{{ $v->notes ?? '-' }}</td>
                <td class="center">{{ $statusAkhir }}</td>
            </tr>
            @empty
            <tr><td colspan="17" style="text-align:center; padding: 16px;">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        Laporan ini dibuat otomatis oleh Sistem Buku Tamu IT Solution Yogyakarta. Data bersifat rahasia internal dan hanya untuk keperluan evaluasi manajemen.
    </div>

</body>
</html>