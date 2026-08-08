@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Laporan & Export Data Kunjungan 📥</h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Unduh rekapitulasi data tamu dan konversi lead dalam format Excel atau PDF untuk laporan bulanan perusahaan.</p>
    </div>

    <!-- Filter & Tombol Export -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Laporan & Export Data Kunjungan 📥</h2>
        <p style="font-size: 13px; color: #778195; margin: 0;">Unduh rekapitulasi data tamu dan konversi lead bulanan dalam format Excel atau PDF untuk laporan perusahaan.</p>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">Filter Periode Laporan Bulanan</h3>
        
        <form action="" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 24px;">
            
            <div style="width: 220px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Pilih Bulan</label>
                <select name="month" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                    <option value="1" {{ date('m') == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ date('m') == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ date('m') == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ date('m') == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ date('m') == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ date('m') == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ date('m') == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ date('m') == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ date('m') == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ date('m') == 12 ? 'selected' : '' }}>Desember</option>
                </select>
            </div>

            <div style="width: 180px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Tahun</label>
                <select name="year" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
                    @php $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                        <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div style="width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Kategori Tamu</label>
                <select name="category" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff; cursor: pointer;">
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

        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <span style="font-size: 13px; font-weight: 700; color: #172033;">Aksi Export File:</span>
            <button onclick="alert('Mengunduh Laporan Bulanan dalam format Excel...')" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                📊 Export Bulanan ke Excel (.xlsx)
            </button>
            <button onclick="alert('Mengunduh Laporan Bulanan dalam format PDF...')" style="background: #dc2626; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                📄 Export Bulanan ke PDF (.pdf)
            </button>
        </div>
    </div>

</div>
@endsection