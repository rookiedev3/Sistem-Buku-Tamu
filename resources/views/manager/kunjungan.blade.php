@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header & Filter -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 4px;">Arsip Semua Kunjungan Tamu 📋</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Rekapitulasi seluruh riwayat tamu yang datang ke perusahaan dari berbagai PIC.</p>
            </div>
        </div>

        <!-- Filter Form -->
        <form action="" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Cari Nama / Instansi / PIC</label>
                <input type="text" name="keyword" placeholder="Contoh: Budi atau Siska..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
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
                <button type="submit" style="background: #1e3a8a; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; height: 41px;">
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Arsip Semua Kunjungan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">Daftar Seluruh Kunjungan</h3>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Tanggal & Waktu</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Kategori</th>
                        <th style="padding: 14px;">Tujuan PIC</th>
                        <th style="padding: 14px;">Keperluan & Hasil</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">1</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">
                            05 Agustus 2026<br>
                            <span style="font-size: 11px;">10:15 WIB</span>
                        </td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">Budi Santoso</strong>
                            <span style="font-size: 11px; color: #778195;">PT Maju Mundur Sejahtera</span>
                        </td>
                        <td style="padding: 14px;">
                            <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">⭐ VIP</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">Siska (Sales)</td>
                        <td style="padding: 14px; color: #475569;">
                            <strong>Keperluan:</strong> Repeat Order<br>
                            <span style="font-size: 11px; color: #778195;">Catatan: Klien sepakat lanjut kontrak tahunan.</span>
                        </td>
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