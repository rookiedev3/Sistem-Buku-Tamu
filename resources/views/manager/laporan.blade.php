@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Laporan & Export Data Kunjungan 📥</h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Unduh rekapitulasi data tamu dan konversi lead dalam format Excel atau PDF untuk laporan bulanan perusahaan.</p>
    </div>

    <!-- Filter & Tombol Export -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">Filter Periode Laporan</h3>
        
        <form action="" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 24px;">
            <div style="width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Dari Tanggal</label>
                <input type="date" name="start_date" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Sampai Tanggal</label>
                <input type="date" name="end_date" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Kategori Tamu</label>
                <select style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                    <option value="">Semua Kategori</option>
                    <option value="vip">VIP</option>
                    <option value="reguler">Reguler</option>
                </select>
            </div>
            <div>
                <button type="submit" style="background: #1e3a8a; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; height: 41px;">
                    Tampilkan Preview
                </button>
            </div>
        </form>

        <hr style="border: 0; border-top: 1px solid #f1f5f9; margin-bottom: 24px;">

        <div style="display: flex; gap: 12px; align-items: center;">
            <span style="font-size: 13px; font-weight: 700; color: #172033;">Aksi Export File:</span>
            <button onclick="alert('Mengunduh Laporan dalam format Excel...')" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                📊 Export ke Excel (.xlsx)
            </button>
            <button onclick="alert('Mengunduh Laporan dalam format PDF...')" style="background: #dc2626; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                📄 Export ke PDF (.pdf)
            </button>
        </div>
    </div>

</div>
@endsection