@extends('layouts.app')

@section('content')
<style>
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4b5563;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        background: #ffffff;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        margin-bottom: 20px;
    }
    .back-btn:hover {
        background: #f9fafb;
        color: #111827;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        align-items: center;
        margin-bottom: 40px;
        background: #fcfcfd;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #f3f4f6;
    }
    .chart-container {
        position: relative;
        width: 280px;
        height: 280px;
        flex-shrink: 0;
        margin: 0 auto;
    }
    .chart-center-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
    }
    .chart-center-value {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
    }
    .chart-center-label {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .legend-container {
        flex: 1;
        min-width: 250px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        border: 1px solid #f3f4f6;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .legend-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .legend-color {
        width: 14px;
        height: 14px;
        border-radius: 6px;
        flex-shrink: 0;
        margin-right: 12px;
    }
    .legend-text {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .legend-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }
    .legend-subtitle {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
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
        max-width: 120px;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 99px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 99px;
    }
    .percentage-text {
        font-weight: 700;
        color: #4b5563;
        min-width: 45px;
        text-align: right;
    }
</style>

<!-- TOMBOL KEMBALI -->
<a href="{{ route('dashboard') }}" class="back-btn">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
    </svg>
    Kembali
</a>

<div class="report-card">
    <div class="report-header">
        <h3>Laporan Dominasi Kategori Tamu</h3>
        <p>Analisis visual sebaran jumlah tamu berdasarkan kategori yang terdaftar.</p>
    </div>

    @if($categoryStats->count() > 0)
        <div class="chart-wrapper">
            <!-- Bagian Chart -->
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
                <div class="chart-center-text">
                    <div class="chart-center-value">{{ number_format($totalGuests) }}</div>
                    <div class="chart-center-label">Total Tamu</div>
                </div>
            </div>

            <!-- Bagian Legend (Keterangan) -->
            <div class="legend-container">
                @foreach($categoryStats as $index => $category)
                    @php
                        $percentage = $totalGuests > 0 ? round(($category->total / $totalGuests) * 100) : 0;
                        $color = $chartColors[$index % count($chartColors)];
                    @endphp
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: {{ $color }};"></div>
                        <div class="legend-text">
                            <span class="legend-title">{{ $category->name }}</span>
                            <span class="legend-subtitle">{{ $category->total }} Tamu &bull; {{ $percentage }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Bagian Tabel -->
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center;">Rank</th>
                    <th>Kategori Tamu</th>
                    <th style="text-align: right;">Jumlah Tamu</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categoryStats as $index => $category)
                    @php
                        $percentage = $totalGuests > 0 ? round(($category->total / $totalGuests) * 100) : 0;
                        $color = $chartColors[$index % count($chartColors)];
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            <!-- Perbaikan Nomor Urut agar sesuai halaman pagination -->
                            <span class="rank-badge">#{{ ($categoryStats->firstItem() ?? 1) + $index }}</span>
                        </td>
                        <td style="font-weight: 700; color: #111827;">
                            {{ $category->name }}
                        </td>
                        <td style="text-align: right; font-weight: 600;">
                            {{ number_format($category->total) }}
                        </td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bg">
                                    <div class="progress-fill" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                                </div>
                                <span class="percentage-text">{{ $percentage }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #6b7280;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            Belum ada data kategori tamu yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- INCLUDE PAGINATION COMPONENT -->
    @if($categoryStats->total() > 0)
        @include('partials.pagination', ['paginator' => $categoryStats])
    @endif
</div>

@if($categoryStats->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryStats->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categoryStats->pluck('total')) !!},
                backgroundColor: {!! json_encode($chartColors) !!},
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 8,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleFont: { size: 14, family: "'Segoe UI', sans-serif" },
                    bodyFont: { size: 13, family: "'Segoe UI', sans-serif" },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed !== null) { label += context.parsed + ' Tamu'; }
                            return label;
                        }
                    }
                }
            },
            animation: { animateScale: true, animateRotate: true }
        }
    });
});
</script>
@endif
@endsection