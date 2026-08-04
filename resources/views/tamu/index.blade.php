@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Database Tamu</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Arsip lengkap seluruh riwayat kunjungan dan data instansi tamu.</p>
    </div>
    
    <button onclick="alert('Fitur ekspor data Excel/PDF akan diproses backend.')" style="background: #ffffff; color: #172033; border: 1px solid #e8edf5; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(31,53,97,.05);">
        📥 Export Data
    </button>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" placeholder="Cari nama, instansi, atau nomor WA..." style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">
            <select style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; background: #fff; color: #5c6678; outline: none; cursor: pointer;">
                <option value="">Semua Produk</option>
                <option value="website">Website</option>
                <option value="pos">Sistem POS</option>
                <option value="seo">SEO</option>
            </select>
        </div>
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Arsip Tamu: <strong style="color: #172033; font-weight: 800;">148 Orang</strong>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama & Kontak</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Instansi / Perusahaan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Minat Produk</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Total Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Terakhir Berkunjung</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">1</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Ahmad Fauzan</div>
                        <div style="font-size: 11px; color: #778195;">081234567890</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">PT Maju Jaya</div>
                        <div style="font-size: 11px; color: #778195;">Manager</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">Website</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">3 Kali</td>
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">Selasa, 04 Agu 2026</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="{{ url('/database-tamu/1') }}" style="color: #1463ff; text-decoration: none; font-weight: 800;">Lihat Riwayat</a>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">2</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Siti Aminah</div>
                        <div style="font-size: 11px; color: #778195;">089876543211</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">CV Berkah Mandiri</div>
                        <div style="font-size: 11px; color: #778195;">Owner</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">Sistem POS</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">1 Kali</td>
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">Senin, 03 Agu 2026</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #1463ff; text-decoration: none; font-weight: 800;">Lihat Riwayat</a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195;">
        <span>Menampilkan 1 - 10 dari 148 total data</span>
        <div style="display: flex; gap: 6px;">
            <button style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; cursor: pointer; color: #778195; font-weight: 700;" disabled>Sebelumnya</button>
            <button style="padding: 6px 12px; border: 1px solid #1463ff; background: #1463ff; color: #fff; border-radius: 8px; cursor: pointer; font-weight: 800;">1</button>
            <button style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; cursor: pointer; color: #778195; font-weight: 700;">2</button>
            <button style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; cursor: pointer; color: #778195; font-weight: 700;">Selanjutnya</button>
        </div>
    </div>

</div>

@endsection