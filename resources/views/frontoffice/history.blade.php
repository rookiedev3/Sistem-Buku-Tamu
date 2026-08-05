@extends('layouts.frontoffice')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            FRONT OFFICE SYSTEM
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Riwayat Kunjungan Tamu 📚
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Arsip lengkap data tamu yang telah selesai melakukan kunjungan dan check-out dari sistem.
        </p>
    </div>

    <div style="display: flex; gap: 10px; align-items: center; background: #ffffff; padding: 8px 14px; border-radius: 12px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
        <span style="font-size: 12px; font-weight: 600; color: #64748b;">Filter Tanggal:</span>
        <input type="date" style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; outline: none; color: #172033;" value="2026-08-05">
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Arsip Data Kunjungan Selesai</h3>
        
        <div>
            <input type="text" id="searchHistory" placeholder="Cari nama tamu / instansi..." onkeyup="filterHistoryTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 240px;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="historyTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">Token & Tanggal</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Jenis Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status Akhir</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi Detail</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">ANT-099</span>
                        <span style="font-size: 11px; color: #778195;">04 Agustus 2026, 14:20</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">Ahmad Hidayat</div>
                        <div style="font-size: 11px; color: #778195;">PT Nusantara Tekno (Direktur)</div>
                    </td>
                    <td style="padding: 16px 20px;">Kerjasama Proyek</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            Budi Santoso (Sales)
                        </span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Selesai (Check-out)
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <button onclick="openDetailModal('ANT-099', 'Ahmad Hidayat', 'PT Nusantara Tekno', 'Direktur', '081234567890', 'Kerjasama Proyek', 'Budi Santoso (Sales)', '04 Agustus 2026, 09:15', '04 Agustus 2026, 14:20')" 
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                            Detail
                        </button>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">ANT-098</span>
                        <span style="font-size: 11px; color: #778195;">04 Agustus 2026, 11:10</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">Rina Marlina</div>
                        <div style="font-size: 11px; color: #778195;">CV Media Kreasi (Staff Marketing)</div>
                    </td>
                    <td style="padding: 16px 20px;">Presentasi Produk</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            Siti Aminah (IT Support)
                        </span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Selesai (Check-out)
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <button onclick="openDetailModal('ANT-098', 'Rina Marlina', 'CV Media Kreasi', 'Staff Marketing', '089876543210', 'Presentasi Produk', 'Siti Aminah (IT Support)', '04 Agustus 2026, 08:30', '04 Agustus 2026, 11:10')" 
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                            Detail
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan arsip kunjungan sebelumnya</span>
        <span>Halaman 1 dari 5</span>
    </div>

</div>


<div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 550px; max-width: 90%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">
        
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Detail Kunjungan Tamu 📄</h3>
            <button onclick="closeDetailModal()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 14px; max-height: 70vh; overflow-y: auto;">
            
            <div style="display: flex; align-items: center; gap: 16px; background: #f8fafc; padding: 14px; border-radius: 14px; border: 1px solid #e8edf5;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #006B3F; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; flex-shrink: 0;">
                    AH
                </div>
                <div>
                    <span style="font-size: 11px; color: #778195; font-weight: 600; text-transform: uppercase; display: block;">Dokumentasi Wajah</span>
                    <span style="font-size: 13px; font-weight: 700; color: #172033;">Foto Tersedia (Mockup)</span>
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
    function filterHistoryTable() {
        const input = document.getElementById('searchHistory');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('historyTable');
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

    function openDetailModal(token, name, instansi, jabatan, phone, keperluan, pic, checkin, checkout) {
        document.getElementById('modalToken').innerText = token;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalInstansi').innerText = instansi + ' (' + jabatan + ')';
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalKeperluan').innerText = keperluan;
        document.getElementById('modalPic').innerText = pic;
        document.getElementById('modalCheckin').innerText = checkin;
        document.getElementById('modalCheckout').innerText = checkout;

        document.getElementById('detailModal').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
</script>

@endsection