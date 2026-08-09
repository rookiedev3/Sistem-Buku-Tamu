@extends('layouts.app')

@section('content')


<div style="width: 100%; box-sizing: border-box; padding: 0;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Laporan & Statistik</h1>
            <p style="font-size: 13px; color: #778195; margin: 0;">Rekapitulasi data kunjungan tamu dan tren produk yang diminati.</p>
        </div>
        
        <button onclick="window.print()" style="background: #ce3333; color: #fdfdfd; border: 1px solid #e8edf5; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(31,53,97,.05);">
            🖨️ Cetak / Print Laporan
        </button>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(31,53,97,.06); flex-wrap: wrap; width: 100%; box-sizing: border-box;">
        <div style="font-size: 13px; font-weight: 800; color: #172033;">Filter Periode:</div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <input type="date" value="2026-08-01" style="padding: 8px 12px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; color: #172033; background: #fbfcfe;">
            <span style="color: #778195; font-weight: 700;">s/d</span>
            <input type="date" value="2026-08-04" style="padding: 8px 12px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; color: #172033; background: #fbfcfe;">
        </div>
        <button style="background: #013220; color: #fff; padding: 9px 16px; border-radius: 10px; font-size: 13px; font-weight: 800; border: none; cursor: pointer;">Tampilkan</button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; width: 100%; box-sizing: border-box;">
        
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; box-shadow: 0 10px 30px rgba(31,53,97,.06);">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Total Tamu Masuk</span>
            <div style="font-size: 24px; font-weight: 900; color: #172033;">148 Orang</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; box-shadow: 0 10px 30px rgba(31,53,97,.06);">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Lead Berhasil (Deal)</span>
            <div style="font-size: 24px; font-weight: 900; color: #10b981;">32 Klien</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; box-shadow: 0 10px 30px rgba(31,53,97,.06);">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Produk Paling Diminati</span>
            <div style="font-size: 20px; font-weight: 900; color: #1463ff;">Website (64%)</div>
        </div>

    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden; width: 100%; box-sizing: border-box;">
        
        <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; background: #fbfcfe;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Rekapitulasi Kunjungan Per Produk</h3>
        </div>

        <div style="overflow-x: auto; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                        <th style="padding: 14px 20px; font-weight: 800;">No</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Kategori Produk</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Jumlah Peminat</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Status Konversi (Deal)</th>
                    </tr>
                </thead>
                <tbody style="color: #172033;">
                    
                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        <td style="padding: 16px 20px; font-weight: 700;">1</td>
                        <td style="padding: 16px 20px; font-weight: 800;">Website Development</td>
                        <td style="padding: 16px 20px;">95 Orang</td>
                        <td style="padding: 16px 20px;"><strong style="color: #10b981;">24 Deal</strong></td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        <td style="padding: 16px 20px; font-weight: 700;">2</td>
                        <td style="padding: 16px 20px; font-weight: 800;">Sistem POS (Kasir)</td>
                        <td style="padding: 16px 20px;">30 Orang</td>
                        <td style="padding: 16px 20px;"><strong style="color: #10b981;">6 Deal</strong></td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        <td style="padding: 16px 20px; font-weight: 700;">3</td>
                        <td style="padding: 16px 20px; font-weight: 800;">SEO & Digital Marketing</td>
                        <td style="padding: 16px 20px;">23 Orang</td>
                        <td style="padding: 16px 20px;"><strong style="color: #10b981;">2 Deal</strong></td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection