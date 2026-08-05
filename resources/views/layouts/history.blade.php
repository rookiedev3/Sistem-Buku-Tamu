<div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 550px; max-width: 90%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden; animation: fadeIn 0.3s ease;">
        
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Detail Kunjungan Tamu 📄</h3>
            <button onclick="closeDetailModal()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 14px; max-height: 70vh; overflow-y: auto;">
            
            <div style="display: flex; align-items: center; gap: 16px; background: #f8fafc; padding: 14px; border-radius: 14px; border: 1px solid #e8edf5;">
                <div id="modalPhotoContainer" style="width: 60px; height: 60px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; border: 2px solid #006B3F;">
                    <span id="modalPhotoPlaceholder" style="font-size: 11px; font-weight: 700; color: #64748b;">No Foto</span>
                    <img id="modalPhotoImg" src="" alt="Foto Tamu" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                </div>
                <div>
                    <span style="font-size: 11px; color: #778195; font-weight: 600; text-transform: uppercase; display: block;">Dokumentasi Wajah</span>
                    <span id="modalPhotoStatus" style="font-size: 13px; font-weight: 700; color: #172033;">Tamu tidak mengunggah foto</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Nomor Token:</span>
                <span id="modalToken" style="font-weight: 800; color: #006B3F;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Nama Lengkap:</span>
                <span id="modalName" style="font-weight: 700;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Asal Instansi & Jabatan:</span>
                <span id="modalInstansi" style="font-weight: 600; text-align: right;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Nomor WhatsApp:</span>
                <span id="modalPhone" style="font-weight: 600;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Jenis Kunjungan:</span>
                <span id="modalKeperluan" style="font-weight: 600;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Tujuan PIC Pegawai:</span>
                <span id="modalPic" style="font-weight: 600; color: #0369a1;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Waktu Check-in:</span>
                <span id="modalCheckin" style="font-weight: 600;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #778195;">Waktu Check-out:</span>
                <span id="modalCheckout" style="font-weight: 600; color: #006B3F;">-</span>
            </div>
        </div>

        <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; justify-content: flex-end;">
            <button onclick="closeDetailModal()" style="background: #172033; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                Tutup
            </button>
        </div>

    </div>
</div>


<script>
    // Fungsi Membuka Modal Detail (Sekaligus Menangani Foto)
    function openDetailModal(token, name, instansi, jabatan, phone, keperluan, pic, checkin, checkout, photoUrl) {
        document.getElementById('modalToken').innerText = token;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalInstansi').innerText = instansi + ' (' + jabatan + ')';
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalKeperluan').innerText = keperluan;
        document.getElementById('modalPic').innerText = pic;
        document.getElementById('modalCheckin').innerText = checkin;
        document.getElementById('modalCheckout').innerText = checkout;

        // Logika Kondisional Foto (Opsional)
        const photoImg = document.getElementById('modalPhotoImg');
        const photoPlaceholder = document.getElementById('modalPhotoPlaceholder');
        const photoStatus = document.getElementById('modalPhotoStatus');

        if (photoUrl && photoUrl !== '') {
            photoImg.src = photoUrl;
            photoImg.style.display = 'block';
            photoPlaceholder.style.display = 'none';
            photoStatus.innerText = 'Foto tersedia';
        } else {
            photoImg.style.display = 'none';
            photoPlaceholder.style.display = 'block';
            photoStatus.innerText = 'Tamu tidak mengunggah foto (Opsional)';
        }

        document.getElementById('detailModal').style.display = 'flex';
    }

    // Fungsi Menutup Modal Detail
    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
</script>