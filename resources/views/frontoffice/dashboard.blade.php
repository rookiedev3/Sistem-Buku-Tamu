@extends('layouts.frontoffice')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            FRONT OFFICE SYSTEM
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Dashboard Front Office 🛎️
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Kelola antrian tamu aktif, ubah status kunjungan, dan input data tamu manual (walk-in).
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Total Tamu Hari Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #006B3F;">12 Orang</span>
        </div>
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 14px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Sedang Menunggu</span>
            <span style="font-size: 18px; font-weight: 800; color: #f59e0b;">3 Orang</span>
        </div>
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Daftar Kunjungan & Antrian Tamu</h3>
        
        <div style="display: flex; gap: 10px;">
            <input type="text" id="searchGuest" placeholder="Cari nama tamu / instansi..." onkeyup="filterTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 240px;">
            
            <button onclick="openManualModal()" style="background: #006B3F; color: #fff; border: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                + Input Tamu Manual
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="guestTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">Token / Waktu</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Jenis Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi (Check-in / Out)</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                
                <tr style="border-bottom: 1px solid #e8edf5;" id="row-1">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">ANT-001</span>
                        <span style="font-size: 11px; color: #778195;">09:15 WIB</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">Budi Santoso</div>
                        <div style="font-size: 11px; color: #778195;">PT Sinar Jaya (Manager IT)</div>
                    </td>
                    <td style="padding: 16px 20px;">Pertemuan Bisnis</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            Budi Santoso (Sales)
                        </span>
                    </td>
                    <td style="padding: 16px 20px;" id="status-1">
                        <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Menunggu
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;" id="action-1">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <button onclick="changeStatus(1, 'checkin')" style="background: #006B3F; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Check-in
                            </button>
                            <button onclick="alert('Fitur Edit Kunjungan Aktif')" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #e8edf5;" id="row-2">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">ANT-002</span>
                        <span style="font-size: 11px; color: #778195;">08:45 WIB</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">Siti Aminah</div>
                        <div style="font-size: 11px; color: #778195;">CV Berkah Mandiri (Staff)</div>
                    </td>
                    <td style="padding: 16px 20px;">Konsultasi Layanan</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            Siti Aminah (IT Support)
                        </span>
                    </td>
                    <td style="padding: 16px 20px;" id="status-2">
                        <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Sedang Bertemu
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;" id="action-2">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <button onclick="changeStatus(2, 'checkout')" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Check-out
                            </button>
                            <button onclick="alert('Fitur Edit Kunjungan Aktif')" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan data kunjungan hari ini</span>
        <span>Halaman 1 dari 1</span>
    </div>

</div>

<div id="manualModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 999;">
    <div style="background: #ffffff; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; padding: 30px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); box-sizing: border-box;">
        <h3 style="font-size: 18px; font-weight: 800; color: #172033; margin-top: 0; margin-bottom: 16px;">Form Input Tamu Manual (Front Office)</h3>
        
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nama Lengkap *</label>
                <input type="text" id="inputName" placeholder="Masukkan nama tamu" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>
            
            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Asal Instansi / Perusahaan *</label>
                <input type="text" id="inputCompany" placeholder="Masukkan instansi" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Jabatan di Perusahaan *</label>
                <input type="text" id="inputPosition" placeholder="Contoh: Staff, Manager, Direktur" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nomor WhatsApp (Aktif) *</label>
                <input type="tel" id="inputPhone" placeholder="Contoh: 081234567890" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih PIC Tujuan *</label>
                <select id="inputPic" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;">
                    <option value="Budi Santoso">Budi Santoso (Sales Manager)</option>
                    <option value="Siti Aminah">Siti Aminah (IT Support)</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="closeManualModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="button" onclick="simulateSaveManualGuest()" style="flex: 2; background: #006B3F; color: #fff; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">Simpan & Buat Antrian</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openManualModal() {
        document.getElementById('manualModal').style.display = 'flex';
    }
    
    function closeManualModal() {
        document.getElementById('manualModal').style.display = 'none';
    }

    function simulateSaveManualGuest() {
        const name = document.getElementById('inputName').value;
        const company = document.getElementById('inputCompany').value;
        if(!name || !company) {
            alert('Mohon lengkapi Nama dan Asal Instansi tamu terlebih dahulu.');
            return;
        }
        alert('Data tamu manual atas nama "' + name + '" dari "' + company + '" berhasil disimulasikan masuk ke antrian!');
        closeManualModal();
    }

    // Fungsi Interaktif Check-in & Check-out Frontend
    function changeStatus(id, type) {
        const statusCell = document.getElementById('status-' + id);
        const actionCell = document.getElementById('action-' + id);

        if (type === 'checkin') {
            statusCell.innerHTML = `
                <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                    Sedang Bertemu
                </span>
            `;
            actionCell.innerHTML = `
                <div style="display: flex; gap: 6px; justify-content: center;">
                    <button onclick="changeStatus(${id}, 'checkout')" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                        Check-out
                    </button>
                    <button onclick="alert('Fitur Edit Kunjungan Aktif')" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                        Edit
                    </button>
                </div>
            `;
            alert('Tamu berhasil Check-in! Status berubah menjadi Sedang Bertemu.');
        } else if (type === 'checkout') {
            statusCell.innerHTML = `
                <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                    Selesai
                </span>
            `;
            actionCell.innerHTML = `
                <span style="font-size: 11px; color: #64748b; font-weight: 600;">Kunjungan Selesai</span>
            `;
            alert('Tamu berhasil Check-out! Kunjungan telah selesai.');
        }
    }

    // Fungsi Pencarian / Filter Tabel secara Real-time
    function filterTable() {
        const input = document.getElementById('searchGuest');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('guestTable');
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
</script>

@endsection