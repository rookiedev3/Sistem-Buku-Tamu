@extends('layouts.manager')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header & Statistik Lead Tim -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Pipeline Lead & Prospek Tim 📈</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Pengawasan menyeluruh terhadap status konversi penjualan yang sedang dikerjakan oleh tim PIC/Sales.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Prospek Aktif</span>
            <strong style="font-size: 24px; font-weight: 900; color: #1e3a8a; margin-top: 4px;">12 Klien</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Deal</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">5 Klien</strong>
        </div>
    </div>

    <!-- Tabel Monitoring Lead Tim -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Prospek & PIC Penanggung Jawab</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">PIC / Sales</th>
                        <th style="padding: 14px;">Minat / Catatan Terakhir</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status Lead</th>
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
                        <td style="padding: 14px; color: #475569;">
                            Tertarik berlangganan Software IT Solution paket tahunan.
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Warm Lead</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection