@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Lead & Follow Up</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Pantau status tindak lanjut calon klien yang berminat pada produk.</p>
    </div>
    
    <button onclick="document.getElementById('modalLead').style.display='flex'" style="background: #1463ff; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(20,99,255,.2);">
    + Tambah Lead
</button>

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
                <button type="submit" style="flex: 1; padding: 11px; border-radius: 10px; border: none; background: #1463ff; color: #fff; font-weight: 800; cursor: pointer;">Simpan Lead</button>
            </div>
        </form>

    </div>
</div>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; gap: 8px;">
            <button style="padding: 8px 14px; border-radius: 10px; border: 1px solid #1463ff; background: #eef4ff; color: #1463ff; font-size: 12px; font-weight: 800; cursor: pointer;">Semua Lead</button>
            <button style="padding: 8px 14px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-size: 12px; font-weight: 700; cursor: pointer;">Belum Dihubungi</button>
            <button style="padding: 8px 14px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-size: 12px; font-weight: 700; cursor: pointer;">Dalam Proses</button>
            <button style="padding: 8px 14px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-size: 12px; font-weight: 700; cursor: pointer;">Deal / Selesai</button>
        </div>
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Lead Aktif: <strong style="color: #172033; font-weight: 800;">5 Orang</strong>
        </div>
    </div>

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
                
                <tr style="border-bottom: 1px solid #f1f4f9;">
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
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Website</span>
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

                <tr style="border-bottom: 1px solid #f1f4f9;">
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
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">Sistem POS</span>
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

            </tbody>
        </table>
    </div>

</div>

@endsection