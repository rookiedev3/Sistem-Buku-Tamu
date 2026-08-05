@extends('layouts.security')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Header & Tombol Check-in Cepat -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 4px;">Daftar Tamu Hari Ini 📋</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Pantau daftar tamu yang masuk dan keluar</p>
        </div>
       
    </div>

    <!-- Tabel Daftar Tamu Hari Ini -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Log Aktivitas Tamu (Check-in & Check-out)</h3>
            <span style="font-size: 12px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Tujuan (PIC)</th>
                        <th style="padding: 14px;">Waktu Check-in</th>
                        <th style="padding: 14px;">Status Kehadiran</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">1</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">Budi Santoso</strong>
                            <span style="font-size: 11px; color: #778195;">PT Maju Mundur Sejahtera</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">Siska (Sales)</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">10:15 WIB</td>
                        <td style="padding: 14px;">
                            <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">Di dalam area</span>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <button onclick="alert('Proses Check-out berhasil. Tamu telah meninggalkan area.')" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                Check-out
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection