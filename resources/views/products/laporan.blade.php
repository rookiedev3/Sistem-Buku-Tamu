@extends('layouts.app')

@section('content')
<div class="card border-0 rounded-4 p-4 shadow-sm" style="background:#fff; border:1px solid #e8edf5;">
    <h3 style="font-size:18px; font-weight:800; color:#172033; margin-bottom:4px;">Laporan Produk Paling Diminati</h3>
    <p style="color:#778195; font-size:13px; margin-bottom:20px;">Ranking produk berdasarkan jumlah permintaan dari kunjungan tamu.</p>

    <table style="width:100%; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="border-bottom:1px solid #e8edf5; color:#778195; text-transform:uppercase; font-size:11px;">
                <th style="padding:12px 10px; text-align:left;">Peringkat</th>
                <th style="padding:12px 10px; text-align:left;">Nama Produk</th>
                <th style="padding:12px 10px; text-align:right;">Jumlah Permintaan</th>
                <th style="padding:12px 10px; text-align:right;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productStats as $index => $product)
                <tr style="border-bottom:1px solid #f7faff;">
                    <td style="padding:14px 10px;">#{{ $index + 1 }}</td>
                    <td style="padding:14px 10px; font-weight:700;">{{ $product->name }}</td>
                    <td style="padding:14px 10px; text-align:right;">{{ $product->total }}</td>
                    <td style="padding:14px 10px; text-align:right;">
                        {{ $totalPermintaan > 0 ? round(($product->total / $totalPermintaan) * 100) : 0 }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding:24px 10px; text-align:center; color:#778195;">Belum ada data permintaan produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection