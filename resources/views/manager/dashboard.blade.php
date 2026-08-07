@extends('layouts.manager')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            MANAGER MONITORING
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Dashboard Monitoring Manager 📊
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Pantau seluruh aktivitas kunjungan tamu secara real-time, kinerja PIC, dan progres konversi lead tim.
        </p>
    </div>

    <!-- Statistik Dinamis dari Database -->
    <div style="display: flex; gap: 12px;">
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Total Tamu Hari Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #1e3a8a;">{{ $totalToday ?? 0 }} Orang</span>
        </div>
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Lead Deal Bulan Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #006B3F;">{{ $leadDealsCount ?? 0 }} Klien</span>
        </div>
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Monitoring Kunjungan & Status PIC</h3>
        
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 12px; color: #778195; font-weight: 600; background: #f1f5f9; padding: 6px 12px; border-radius: 10px; border: 1px solid #e8edf5;">
                📅 {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="table align-middle" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; margin: 0;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Nama Tamu & Instansi</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Kategori</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan (PIC)</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Waktu Masuk</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $index => $v)
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px;">
                        <strong style="display: block; color: #172033; font-weight: 800;">{{ $v->guest->name ?? '-' }}</strong>
                        <span style="font-size: 11px; color: #778195;">{{ $v->guest->company_name ?? '-' }}</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">
                            ⭐ {{ $v->guest->category->name ?? 'Reguler' }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px; color: #475569; font-weight: 600;">{{ $v->assignedUser->name ?? '-' }} (PIC)</td>
                    <td style="padding: 16px 20px; color: #778195; font-weight: 600;">
                        {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->format('H:i') . ' WIB' : '-' }}
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        @if($v->check_out_at)
                            <span style="background: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Selesai</span>
                        @elseif($v->check_in_at)
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Sedang Bertemu</span>
                        @else
                            <span style="background: #fef3c7; color: #b45309; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Menunggu</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b;">
                        Belum ada data kunjungan tamu untuk hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan data monitoring real-time</span>
        <span>Total Data: {{ $visits->count() }}</span>
    </div>

</div>

@endsection