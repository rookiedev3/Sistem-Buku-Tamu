@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header & Filter -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 4px;">Riwayat Kunjungan Tamu 📋</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Arsip lengkap tamu yang pernah datang dan menemui Anda di masa lalu.</p>
            </div>
        </div>

        <!-- Filter Form Sederhana -->
        <form action="" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Cari Nama / Instansi</label>
                <input type="text" name="keyword" placeholder="Contoh: Budi atau PT..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="width: 180px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Dari Tanggal</label>
                <input type="date" name="start_date" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="width: 180px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Sampai Tanggal</label>
                <input type="date" name="end_date" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div>
                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; height: 41px;">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Arsip Riwayat Kunjungan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">Daftar Arsip Kunjungan</h3>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Tanggal & Waktu</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Keperluan</th>
                        <th style="padding: 14px;">Catatan Hasil Pertemuan</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">1</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">
                            01 Agustus 2026<br>
                            <span style="font-size: 11px;">14:30 WIB</span>
                        </td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">Siti Aminah</strong>
                            <span style="font-size: 11px; color: #778195;">CV Berkah Jaya</span>
                        </td>
                        <td style="padding: 14px; color: #475569;">Konsultasi Sistem Administrasi Kantor</td>
                        <td style="padding: 14px; color: #475569;">Klien tertarik berlangganan paket tahunan, menunggu ACC anggaran.</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: #e6f4ed; color: #006B3F; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Selesai (Deal)</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection