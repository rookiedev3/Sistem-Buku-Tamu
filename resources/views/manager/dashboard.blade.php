@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Sambutan & Statistik Singkat -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Dashboard Monitoring Manager 📊</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Pantau seluruh aktivitas kunjungan tamu secara real-time, kinerja PIC, dan progres konversi lead tim.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Tamu Hari Ini</span>
            <strong style="font-size: 24px; font-weight: 900; color: #1e3a8a; margin-top: 4px;">18 Orang</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Lead Deal Bulan Ini</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">24 Klien</strong>
        </div>
    </div>

    <!-- Tabel Monitoring Kunjungan Real-Time -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Monitoring Kunjungan & Status PIC</h3>
            <span style="font-size: 12px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Kategori</th>
                        <th style="padding: 14px;">Tujuan (PIC)</th>
                        <th style="padding: 14px;">Waktu Masuk</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">1</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">Budi Santoso</strong>
                            <span style="font-size: 11px; color: #778195;">PT Maju Mundur Sejahtera</span>
                        </td>
                        <td style="padding: 14px;">
                            <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">⭐ VIP</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">Siska (Sales / PIC)</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">10:15 WIB</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Sedang Bertemu</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection