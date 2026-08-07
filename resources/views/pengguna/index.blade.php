@extends('layouts.app') {{-- Sesuaikan dengan layout kamu --}}

@section('content')

<div style="width: 100%; box-sizing: border-box; padding: 0;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Manajemen Pengguna</h1>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar akun staf, resepsionis, dan admin yang memiliki akses ke dashboard.</p>
        </div>
        
        <button onclick="document.getElementById('modalUser').style.display='flex'" style="background: #1463ff; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(20,99,255,.2);">
            + Tambah Pengguna
        </button>
    </div>

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden; width: 100%;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Akun Aktif</h3>
            <span style="font-size: 12px; color: #778195; font-weight: 700;">3 Pengguna</span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                        <th style="padding: 12px 20px; font-weight: 800;">No</th>
                        <th style="padding: 12px 20px; font-weight: 800;">Nama & Email</th>
                        <th style="padding: 12px 20px; font-weight: 800;">Role / Hak Akses</th>
                        <th style="padding: 12px 20px; font-weight: 800;">Status</th>
                        <th style="padding: 12px 20px; font-weight: 800; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="color: #172033;">
                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        <td style="padding: 14px 20px; font-weight: 700;">1</td>
                        <td style="padding: 14px 20px;">
                            <div style="font-weight: 800;">Super Administrator</div>
                            <div style="font-size: 11px; color: #778195;">admin@perusahaan.com</div>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">Administrator</span>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">Aktif</span>
                        </td>
                        <td style="padding: 14px 20px; text-align: center;">
                            <a href="#" style="color: #1463ff; text-decoration: none; font-weight: 800; margin-right: 10px;">Edit</a>
                            <a href="#" style="color: #e5484d; text-decoration: none; font-weight: 800;">Hapus</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        <td style="padding: 14px 20px; font-weight: 700;">2</td>
                        <td style="padding: 14px 20px;">
                            <div style="font-weight: 800;">Siti Resepsionis</div>
                            <div style="font-size: 11px; color: #778195;">frontdesk@perusahaan.com</div>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">Resepsionis</span>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">Aktif</span>
                        </td>
                        <td style="padding: 14px 20px; text-align: center;">
                            <a href="#" style="color: #1463ff; text-decoration: none; font-weight: 800; margin-right: 10px;">Edit</a>
                            <a href="#" style="color: #e5484d; text-decoration: none; font-weight: 800;">Hapus</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        <td style="padding: 14px 20px; font-weight: 700;">3</td>
                        <td style="padding: 14px 20px;">
                            <div style="font-weight: 800;">Budi Sales</div>
                            <div style="font-size: 11px; color: #778195;">budi@perusahaan.com</div>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="background: #f3e8ff; color: #6b21a8; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">Staff / Sales</span>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800;">Non-Aktif</span>
                        </td>
                        <td style="padding: 14px 20px; text-align: center;">
                            <a href="#" style="color: #1463ff; text-decoration: none; font-weight: 800; margin-right: 10px;">Edit</a>
                            <a href="#" style="color: #e5484d; text-decoration: none; font-weight: 800;">Hapus</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modalUser" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: #ffffff; width: 450px; border-radius: 20px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); box-sizing: border-box;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Tambah Pengguna Baru</h3>
            <button onclick="document.getElementById('modalUser').style.display='none'" style="background: none; border: none; font-size: 18px; font-weight: 800; cursor: pointer; color: #778195;">&times;</button>
        </div>

        <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
            @csrf
            <div>
                <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Nama Lengkap</label>
                <input type="text" placeholder="Masukkan nama staff..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Alamat Email (Untuk Login)</label>
                <input type="email" placeholder="nama@perusahaan.com" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Hak Akses (Role)</label>
                <select style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; cursor: pointer; box-sizing: border-box;">
                    <option value="admin">Administrator</option>
                    <option value="resepsionis">Resepsionis</option>
                    <option value="staff">Staff / Sales</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 800; color: #172033; margin-bottom: 6px;">Password Sementara</label>
                <input type="password" placeholder="••••••••" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('modalUser').style.display='none'" style="flex: 1; padding: 11px; border-radius: 10px; border: 1px solid #e8edf5; background: #fff; color: #5c6678; font-weight: 800; cursor: pointer;">Batal</button>
                <button type="submit" style="flex: 1; padding: 11px; border-radius: 10px; border: none; background: #1463ff; color: #fff; font-weight: 800; cursor: pointer;">Simpan Akun</button>
            </div>
        </form>

    </div>
</div>

@endsection