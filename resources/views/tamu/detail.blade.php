@extends('layouts.app')

@section('content')

<div style="margin-bottom: 20px;">
    <a href="{{ url('/database-tamu') }}" style="color: #778195; text-decoration: none; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
        ← Kembali ke Database Tamu
    </a>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; padding: 24px; box-shadow: 0 18px 50px rgba(31,53,97,.12); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0;">Ahmad Fauzan</h1>
            <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Minat: Website</span>
        </div>
        <p style="font-size: 13px; color: #778195; margin: 0;">PT Maju Jaya • Manager • WhatsApp: 081234567890</p>
    </div>
    <div style="background: #f8fafc; padding: 12px 18px; border-radius: 12px; border: 1px solid #e8edf5; text-align: right;">
        <div style="font-size: 11px; color: #778195; font-weight: 700;">Total Kunjungan</div>
        <div style="font-size: 18px; font-weight: 800; color: #1463ff;">3 Kali</div>
    </div>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Timeline Riwayat Kunjungan</h3>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800;">Tanggal & Waktu</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Bertemu Dengan (PIC)</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Keperluan / Catatan Pertemuan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Status</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">Selasa, 04 Agu 2026<br><span style="font-size: 11px; color: #778195; font-weight: normal;">10:15 WIB</span></td>
                    <td style="padding: 16px 20px;">Budi (Sales Manager)</td>
                    <td style="padding: 16px 20px;">Konsultasi pembuatan company profile website perusahaan.</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6fcf5; color: #0ca678; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Selesai (Deal)</span>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">Rabu, 15 Jul 2026<br><span style="font-size: 11px; color: #778195; font-weight: normal;">13:30 WIB</span></td>
                    <td style="padding: 16px 20px;">Siti (Support)</td>
                    <td style="padding: 16px 20px;">Diskusi awal perbandingan harga paket maintenance website.</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Follow Up</span>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">Senin, 10 Jun 2026<br><span style="font-size: 11px; color: #778195; font-weight: normal;">09:00 WIB</span></td>
                    <td style="padding: 16px 20px;">Budi (Sales Manager)</td>
                    <td style="padding: 16px 20px;">Pengenalan produk dan penyerahan brosur perusahaan.</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #f8fafc; color: #5c6678; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Pertama Datang</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection