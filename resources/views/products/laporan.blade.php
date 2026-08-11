@extends('layouts.app')

@section('content')
<style>
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #ffff;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        background: #0284c7;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        margin-bottom: 20px;
    }
    /* .back-btn:hover {
        background: #f9fafb;
        color: #111827;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    } */
    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .filter-bar select {
        padding: 8px 14px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }
    .report-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 30px;
        border: 1px solid #f0f2f5;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    .report-header h3 {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }
    .report-header p {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .chart-wrapper {
        background: #fcfcfd;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #f3f4f6;
        margin-bottom: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .custom-table th {
        background-color: #f9fafb;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 16px;
        text-align: left;
        border-bottom: 2px solid #e5e7eb;
    }
    .custom-table td {
        padding: 16px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .custom-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .custom-table tbody tr:hover {
        background-color: #f9fafb;
    }
    .rank-badge {
        background: #f3f4f6;
        color: #4b5563;
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
    }
    .progress-bar-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .progress-bg {
        flex: 1;
        max-width: 140px;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 99px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background-color: #013220;
        border-radius: 99px;
    }
    .percentage-text {
        font-weight: 700;
        color: #4b5563;
        min-width: 45px;
        text-align: right;
    }
</style>

<!-- TOMBOL KEMBALI SEPERTI KATEGORI TAMU -->
<a href="{{ route('owner.dashboard') }}" class="back-btn">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
    </svg>
    Kembali
</a>

<div class="report-card">
    <div class="report-header">
        <h3>Laporan Produk Paling Diminati</h3>
        <p>Ranking produk berdasarkan jumlah permintaan dari kunjungan tamu.</p>
    </div>

    <!-- FILTER BULAN & TAHUN -->
    <form method="GET" action="{{ route('products.laporan') }}" class="filter-bar">
        <select name="month" onchange="this.form.submit()">
            @foreach($months as $num => $label)
                <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="year" onchange="this.form.submit()">
            @for($y = now()->year; $y >= now()->year - 4; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>

    @if($productStats->count() > 0)
        <div class="chart-wrapper">
            <canvas id="productChart" style="max-height: 350px;"></canvas>
        </div>
    @endif

    <!-- BAGIAN TABEL DATA -->
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center;">Peringkat</th>
                    <th>Nama Produk</th>
                    <th style="text-align: right;">Jumlah Permintaan</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productStats as $index => $product)
                    @php
                        $percentage = $totalPermintaan > 0 ? round(($product->total / $totalPermintaan) * 100) : 0;
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            <span class="rank-badge">#{{ $index + 1 }}</span>
                        </td>
                        <td style="font-weight: 700; color: #111827;">
                            {{ $product->name }}
                        </td>
                        <td style="text-align: right; font-weight: 600;">
                            {{ number_format($product->total) }}
                        </td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bg">
                                    <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                                </div>
                                <span class="percentage-text">{{ $percentage }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #6b7280;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            Belum ada data permintaan produk pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($productStats->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('productChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($productStats->pluck('name')) !!},
            datasets: [{
                label: 'Jumlah Permintaan',
                data: {!! json_encode($productStats->pluck('total')) !!},
                backgroundColor: '#013220',
                borderRadius: 8,
                barThickness: 24,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleFont: { size: 14, family: "'Segoe UI', sans-serif" },
                    bodyFont: { size: 13, family: "'Segoe UI', sans-serif" },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' Permintaan: ' + context.parsed.x + ' kali';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: '#f3f4f6' }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endif
@endsection