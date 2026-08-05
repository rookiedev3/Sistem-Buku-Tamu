@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header Statistik Lead -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Lead & Follow-Up Penjualan 📈</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar prospek klien hasil kunjungan, catat status konversi, dan jadwalkan tindak lanjut.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Prospek</span>
            <strong style="font-size: 24px; font-weight: 900; color: #172033; margin-top: 4px;">12 Klien</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Berhasil (Deal)</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">5 Klien</strong>
        </div>
    </div>

    <!-- Tabel Manajemen Lead -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Prospek & Status Konversi</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">Kontak (WhatsApp)</th>
                        <th style="padding: 14px;">Catatan / Minat Produk</th>
                        <th style="padding: 14px;">Status Lead</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">1</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">Budi Santoso</strong>
                            <span style="font-size: 11px; color: #778195;">PT Maju Mundur Sejahtera</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">0812-3456-7890</td>
                        <td style="padding: 14px; color: #475569;">Tertarik berlangganan Software IT Solution paket tahunan.</td>
                        <td style="padding: 14px;">
                            <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Warm Lead (Follow Up)</span>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <!-- Tombol pemicu Modal Update Status -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus" style="background: #f8fafc; color: #006B3F; border: 1px solid #006B3F; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                Update Status
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- POP-UP MODAL UPDATE STATUS LEAD & FOLLOW-UP -->
<!-- ========================================== -->
<div class="modal fade" id="modalUpdateStatus" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            
            <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                <h5 class="modal-title" id="modalUpdateStatusLabel" style="font-size: 16px; font-weight: 800; color: #172033;">
                    🔄 Update Status & Follow-Up Prospek
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding: 24px;">
                <!-- Info Klien Ringkas -->
                <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                    <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Klien Prospek:</span>
                    <strong style="color: #172033; font-size: 14px;">Budi Santoso (PT Maju Mundur Sejahtera)</strong>
                    <div style="color: #475569; font-size: 12px; margin-top: 2px;">WhatsApp: 0812-3456-7890</div>
                </div>

                <!-- Form Update Status -->
                <form action="#" method="POST">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Status Lead Terbaru</label>
                        <select style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                            <option value="warm">Warm Lead (Perlu Follow-Up Lanjutan)</option>
                            <option value="hot">Hot Lead (Prospek Tinggi / Siap Deal)</option>
                            <option value="deal">Deal / Berhasil (Resmi Order)</option>
                            <option value="drop">Drop / Batal</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Catatan Perkembangan / Follow-Up</label>
                        <textarea rows="3" placeholder="Tuliskan hasil obrolan saat follow-up via WhatsApp atau telepon..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;"></textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Jadwal Follow-Up Berikutnya</label>
                        <input type="date" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" data-bs-dismiss="modal" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                            Batal
                        </button>
                        <button type="submit" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection