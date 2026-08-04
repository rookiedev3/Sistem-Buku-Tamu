@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Daftar Kunjungan Tamu</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola dan pantau seluruh data kunjungan tamu secara real-time.</p>
    </div>
    
    {{-- <a href="{{ url('/check-in') }}" style="background: #1463ff; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(20,99,255,.2);">
        + Tambah Tamu
    </a> --}}
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <input type="text" placeholder="Cari nama tamu atau instansi..." style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Tamu Hari Ini: <strong style="color: #172033; font-weight: 800;">2 Orang</strong>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama Tamu</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Instansi / Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Produk Diminati</th>
                    <th style="padding: 14px 20px; font-weight: 800;">PIC Tujuan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Status</th>
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
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">SEO</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">Budi (IT Support)</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ea; color: #137333; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Selesai</span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #1463ff; text-decoration: none; font-weight: 800; margin-right: 12px;">Detail</a>
                        <a href="#" style="color: #e5484d; text-decoration: none; font-weight: 800;">Hapus</a>
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
                    <td style="padding: 16px 20px; font-weight: 700;">Rina (Sales)</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #fef7e0; color: #b06000; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Meeting</span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #1463ff; text-decoration: none; font-weight: 800; margin-right: 12px;">Detail</a>
                        <a href="#" style="color: #e5484d; text-decoration: none; font-weight: 800;">Hapus</a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195;">
        <span>Menampilkan 1 - 2 dari 2 data kunjungan</span>
        <div style="display: flex; gap: 6px;">
            <button style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; cursor: pointer; color: #778195; font-weight: 700;" disabled>Sebelumnya</button>
            <button style="padding: 6px 12px; border: 1px solid #1463ff; background: #1463ff; color: #fff; border-radius: 8px; cursor: pointer; font-weight: 800;">1</button>
            <button style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; cursor: pointer; color: #778195; font-weight: 700;" disabled>Selanjutnya</button>
        </div>
    </div>

</div>

@endsection