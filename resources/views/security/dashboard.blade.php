@extends('layouts.security')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            SECURITY PORTAL
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Daftar Tamu Hari Ini 📋
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Pantau daftar tamu yang masuk dan keluar secara real-time di area penjagaan.
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Total Masuk Hari Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #006B3F;">1 Orang</span>
        </div>
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Log Aktivitas Tamu (Check-in & Check-out)</h3>
        
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
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan (PIC)</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Waktu Check-in</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status Kehadiran</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px; font-weight: 700;">1</td>
                    <td style="padding: 16px 20px;">
                        <strong style="display: block; color: #172033; font-weight: 800;">Budi Santoso</strong>
                        <span style="font-size: 11px; color: #778195;">PT Maju Mundur Sejahtera</span>
                    </td>
                    <td style="padding: 16px 20px; color: #475569; font-weight: 600;">Siska (Sales)</td>
                    <td style="padding: 16px 20px; color: #778195; font-weight: 600;">10:15 WIB</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Di dalam area</span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <button onclick="alert('Proses Check-out berhasil. Tamu telah meninggalkan area.')" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                            Check-out
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan log aktivitas hari ini</span>
        <span>Halaman 1 dari 1</span>
    </div>

</div>

@endsection