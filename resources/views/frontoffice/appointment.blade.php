@extends('layouts.frontoffice')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            FRONT OFFICE SYSTEM
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Kelola Janji Temu (Appointment) 📅
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Daftar jadwal kunjungan terjadwal yang diajukan oleh tamu sebelum datang ke kantor.
        </p>
    </div>

    <button onclick="openAppointmentModal()" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
        + Buat Janji Temu
    </button>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Daftar Jadwal Tamu Terjadwal</h3>
        
        <div>
            <input type="text" id="searchApp" placeholder="Cari nama tamu / PIC..." onkeyup="filterAppTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 240px;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="appTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">Kode / Jadwal Temu</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Instansi</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Keperluan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi Konfirmasi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                
                <tr id="row-apt-101" style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">APT-101</span>
                        <span style="font-size: 11px; color: #778195;">Besok, 10:00 WIB</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">Diana Puspita</div>
                        <div style="font-size: 11px; color: #778195;">PT Graha Mandiri (HRD)</div>
                    </td>
                    <td style="padding: 16px 20px;">Wawancara Kerjasama</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            Budi Santoso (Sales)
                        </span>
                    </td>
                    <td style="padding: 16px 20px;" class="status-cell">
                        <span class="badge-status" style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Menunggu Konfirmasi
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;" class="action-cell">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <button onclick="updateStatus('row-apt-101', 'approved')" style="background: #006B3F; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Setujui
                            </button>
                            <button onclick="updateStatus('row-apt-101', 'rejected')" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>

                <tr id="row-apt-102" style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">APT-102</span>
                        <span style="font-size: 11px; color: #778195;">07 Agustus 2026, 13:30</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">Hendra Setiawan</div>
                        <div style="font-size: 11px; color: #778195;">CV Solusi Digital (Engineer)</div>
                    </td>
                    <td style="padding: 16px 20px;">Maintenance Server</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            Siti Aminah (IT Support)
                        </span>
                    </td>
                    <td style="padding: 16px 20px;" class="status-cell">
                        <span class="badge-status" style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Disetujui
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;" class="action-cell">
                        <span style="font-size: 11px; color: #64748b; font-weight: 600;">Terkonfirmasi</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan jadwal janji temu aktif</span>
        <span>Halaman 1 dari 1</span>
    </div>

</div>


<div id="appointmentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 500px; max-width: 90%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">
        
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Form Buat Janji Temu 📅</h3>
            <button onclick="closeAppointmentModal()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 12px; max-height: 70vh; overflow-y: auto;">
            <div>
                <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nama Lengkap Tamu *</label>
                <input type="text" id="appName" placeholder="Masukkan nama tamu" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
            </div>
            <div>
                <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Asal Instansi / Perusahaan *</label>
                <input type="text" id="appCompany" placeholder="Masukkan nama perusahaan" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
            </div>
            <div>
                <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Rencana Tanggal & Waktu *</label>
                <input type="datetime-local" id="appDateTime" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
            </div>
            <div>
                <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Keperluan Kunjungan *</label>
                <input type="text" id="appPurpose" placeholder="Contoh: Wawancara, Presentasi, dll" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
            </div>
            <div>
                <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih PIC Tujuan *</label>
                <select id="appPic" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                    <option value="Budi Santoso">Budi Santoso (Sales Manager)</option>
                    <option value="Siti Aminah">Siti Aminah (IT Support)</option>
                </select>
            </div>
        </div>

        <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeAppointmentModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Batal</button>
            <button onclick="simulateSaveAppointment()" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Simpan Jadwal</button>
        </div>

    </div>
</div>

<script>
    // Fungsi Pencarian Tabel
    function filterAppTable() {
        const input = document.getElementById('searchApp');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('appTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let tdName = tr[i].getElementsByTagName('td')[1];
            if (tdName) {
                let txtValue = tdName.textContent || tdName.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Fungsi Modal Tambah Janji Temu
    function openAppointmentModal() {
        document.getElementById('appointmentModal').style.display = 'flex';
    }

    function closeAppointmentModal() {
        document.getElementById('appointmentModal').style.display = 'none';
    }

    function simulateSaveAppointment() {
        const name = document.getElementById('appName').value;
        if(!name) {
            alert('Mohon isi nama tamu terlebih dahulu.');
            return;
        }
        alert('Janji temu atas nama "' + name + '" berhasil disimpan!');
        closeAppointmentModal();
        // Reset field form
        document.getElementById('appName').value = '';
        document.getElementById('appCompany').value = '';
        document.getElementById('appPurpose').value = '';
    }

    // Fungsi Interaktif Setuju / Tolak
    function updateStatus(rowId, action) {
        const row = document.getElementById(rowId);
        const statusCell = row.querySelector('.status-cell');
        const actionCell = row.querySelector('.action-cell');

        if (action === 'approved') {
            statusCell.innerHTML = `
                <span class="badge-status" style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                    Disetujui
                </span>
            `;
            actionCell.innerHTML = `<span style="font-size: 11px; color: #15803d; font-weight: 600;">Terkonfirmasi (Disetujui)</span>`;
        } else if (action === 'rejected') {
            statusCell.innerHTML = `
                <span class="badge-status" style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                    Ditolak
                </span>
            `;
            actionCell.innerHTML = `<span style="font-size: 11px; color: #b91c1c; font-weight: 600;">Dibatalkan</span>`;
        }
    }
</script>

@endsection