@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Lead & Follow Up</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Pantau status tindak lanjut calon klien yang berminat pada produk.</p>
    </div>
    
    <button onclick="document.getElementById('modalLead').style.display='flex'" style="background: #006B3F; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(0,107,63,.2);">
        + Tambah Lead
    </button>

    <!-- Modal Tambah Lead -->
    <div id="modalLead" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: #ffffff; width: 450px; border-radius: 20px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); box-sizing: border-box;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Tambah Lead Baru</h3>
                <button onclick="document.getElementById('modalLead').style.display='none'" style="background: none; border: none; font-size: 18px; font-weight: 800; cursor: pointer; color: #778195;">&times;</button>
            </div>

            <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Nama Klien</label>
                    <input type="text" placeholder="Contoh: Budi Santoso" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Instansi / Perusahaan</label>
                    <input type="text" placeholder="Contoh: PT Sinar Abadi" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Nomor WhatsApp</label>
                    <input type="text" placeholder="Contoh: 081234567890" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Produk Diminati</label>
                    <select style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; cursor: pointer; box-sizing: border-box;">
                        <option value="Website">Website</option>
                        <option value="Sistem POS">Sistem POS</option>
                        <option value="SEO">SEO</option>
                        <option value="Digital Marketing">Digital Marketing</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="document.getElementById('modalLead').style.display='none'" style="flex: 1; padding: 11px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-weight: 800; cursor: pointer;">Batal</button>
                    <button type="submit" style="flex: 1; padding: 11px; border-radius: 10px; border: none; background: #006B3F; color: #fff; font-weight: 800; cursor: pointer;">Simpan Lead</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <!-- Bagian Filter Tab -->
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; gap: 8px;">
            <button class="filter-btn active-filter" onclick="filterTable('semua', this)" style="padding: 8px 14px; border-radius: 10px; border: 1px solid #006B3F; background: #e6f4ed; color: #006B3F; font-size: 12px; font-weight: 800; cursor: pointer; transition: 0.2s;">Semua Lead</button>
            <button class="filter-btn" onclick="filterTable('belum', this)" style="padding: 8px 14px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;">Belum Dihubungi</button>
            <button class="filter-btn" onclick="filterTable('proses', this)" style="padding: 8px 14px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;">Dalam Proses</button>
            <button class="filter-btn" onclick="filterTable('deal', this)" style="padding: 8px 14px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;">Deal / Selesai</button>
        </div>
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Lead Aktif: <strong style="color: #172033; font-weight: 800;">3 Orang</strong>
        </div>
    </div>

    <!-- Tabel Data Lead -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama Klien & Instansi</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Kontak WhatsApp</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Produk Diminati</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Status Follow Up</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi / Update Status</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                
                <!-- Baris 1: Deal -->
                <tr class="lead-row" data-status="deal" style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">1</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Ahmad Fauzan</div>
                        <div style="font-size: 11px; color: #778195;">PT Maju Jaya</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <a href="https://wa.me/6281234567890" target="_blank" style="color: #10b981; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                            💬 081234567890
                        </a>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Website</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #eefcf5; color: #0ca678; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Deal / Berhasil</span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <select style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; font-weight: 700; color: #172033; background: #fff; cursor: pointer; outline: none;">
                            <option value="proses">Dalam Proses</option>
                            <option value="deal" selected>Deal</option>
                            <option value="batal">Batal</option>
                        </select>
                    </td>
                </tr>

                <!-- Baris 2: Proses -->
                <tr class="lead-row" data-status="proses" style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">2</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Siti Aminah</div>
                        <div style="font-size: 11px; color: #778195;">CV Berkah Mandiri</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <a href="https://wa.me/6289876543211" target="_blank" style="color: #10b981; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                            💬 089876543211
                        </a>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Sistem POS</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #fef7e0; color: #b06000; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Dalam Proses</span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <select style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; font-weight: 700; color: #172033; background: #fff; cursor: pointer; outline: none;">
                            <option value="proses" selected>Dalam Proses</option>
                            <option value="deal">Deal</option>
                            <option value="batal">Batal</option>
                        </select>
                    </td>
                </tr>

                <!-- Baris 3: Belum Dihubungi -->
                <tr class="lead-row" data-status="belum" style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">3</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Joko Susilo</div>
                        <div style="font-size: 11px; color: #778195;">PT Nusantara Jaya</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <a href="https://wa.me/6281122334455" target="_blank" style="color: #10b981; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                            💬 081122334455
                        </a>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">SEO</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #f1f5f9; color: #475569; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Belum Dihubungi</span>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <select style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; font-weight: 700; color: #172033; background: #fff; cursor: pointer; outline: none;">
                            <option value="belum" selected>Belum Dihubungi</option>
                            <option value="proses">Dalam Proses</option>
                            <option value="deal">Deal</option>
                        </select>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

<!-- Script JavaScript untuk Filter Tabel -->
<script>
    function filterTable(status, buttonElement) {
        // 1. Ubah styling tombol aktif
        const buttons = document.querySelectorAll('.filter-btn');
        buttons.forEach(btn => {
            btn.style.background = '#fff';
            btn.style.color = '#5c6678';
            btn.style.borderColor = '#e8edf5';
            btn.style.fontWeight = '700';
        });

        buttonElement.style.background = '#e6f4ed';
        buttonElement.style.color = '#006B3F';
        buttonElement.style.borderColor = '#006B3F';
        buttonElement.style.fontWeight = '800';

        // 2. Filter baris tabel berdasarkan data-status
        const rows = document.querySelectorAll('.lead-row');
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'semua' || rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

@endsection