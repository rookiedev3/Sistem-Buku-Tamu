@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Sambutan & Statistik Ringkas -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Dashboard PIC / Sales 👋</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar tamu berdasarkan kategori VIP & Reguler, catat hasil pertemuan, dan pantau konversi lead.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu VIP Menunggu</span>
            <strong style="font-size: 24px; font-weight: 900; color: #d97706; margin-top: 4px;">1 Orang</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu Reguler</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">1 Orang</strong>
        </div>
    </div>

    <!-- Tabel Daftar Tamu Ditugaskan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Tamu Masuk & Kategori Pelanggan</h3>
            <span style="font-size: 12px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Kategori</th>
                        <th style="padding: 14px;">Keperluan</th>
                        <th style="padding: 14px;">Waktu Check-in</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris Tamu VIP -->
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">1</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">Budi Santoso</strong>
                            <span style="font-size: 11px; color: #778195;">PT Maju Mundur Sejahtera</span>
                        </td>
                        <td style="padding: 14px;">
                            <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; border: 1px solid #fde68a;">⭐ VIP (Sering Order)</span>
                        </td>
                        <td style="padding: 14px; color: #475569;">Repeat Order & Kontrak Berkala</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">10:15 WIB</td>
                        <td style="padding: 14px; text-align: center;">
                            <!-- Tombol ini memicu Modal Bootstrap -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalCatatPertemuan" style="background: #006B3F; color: white; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
                                Mulai Pertemuan
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- POP-UP MODAL FORM CATATAN HASIL PERTEMUAN -->
<!-- ========================================== -->
<div class="modal fade" id="modalCatatPertemuan" tabindex="-1" aria-labelledby="modalCatatPertemuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            
            <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                <h5 class="modal-title" id="modalCatatPertemuanLabel" style="font-size: 16px; font-weight: 800; color: #172033;">
                    📝 Catat Hasil Pertemuan & Lead
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding: 24px;">
                <!-- Info Tamu Ringkas -->
                <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                    <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Tamu yang Ditemui:</span>
                    <strong style="color: #172033; font-size: 14px;">Budi Santoso (PT Maju Mundur Sejahtera)</strong>
                    <div style="margin-top: 4px;"><span style="background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700;">⭐ VIP</span></div>
                </div>

                <!-- Form Input Catatan -->
                <form action="#" method="POST">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Catatan / Ringkasan Diskusi</label>
                        <textarea rows="3" placeholder="Tuliskan hasil obrolan atau permintaan khusus klien di sini..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;"></textarea>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Status Konversi Lead</label>
                        <select style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                            <option value="warm">Warm Lead (Perlu Follow-Up via WhatsApp)</option>
                            <option value="hot">Hot Lead (Prospek Tinggi / Minta Penawaran)</option>
                            <option value="deal">Deal / Berhasil (Resmi Order)</option>
                            <option value="cold">Cold / Selesai Kunjungan Biasa</option>
                        </select>
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
                            Simpan & Selesaikan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection