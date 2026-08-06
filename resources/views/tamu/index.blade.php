@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Database Tamu</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Arsip lengkap seluruh riwayat kunjungan dan data instansi tamu.</p>
    </div>
    
    <button onclick="alert('Fitur ekspor data Excel/PDF akan diproses backend.')" style="background: #ffffff; color: #172033; border: 1px solid #e8edf5; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(31,53,97,.05);">
        📥 Export Data
    </button>
</div>

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" placeholder="Cari nama, instansi, atau nomor WA..." style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">
            <select style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; background: #fff; color: #5c6678; outline: none; cursor: pointer;">
                <option value="">Semua Produk</option>
                <option value="website">Website</option>
                <option value="pos">Sistem POS</option>
                <option value="seo">SEO</option>
            </select>
        </div>
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Arsip Tamu: <strong style="color: #172033; font-weight: 800;">148 Orang</strong>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama & Kontak</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Instansi / Perusahaan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Minat Produk</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Total Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Terakhir Berkunjung</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                
                <!-- Data Dummy 1 -->
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">1</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Ahmad Fauzan</div>
                        <div style="font-size: 11px; color: #778195;">081234567890</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">PT Maju Jaya</div>
                        <div style="font-size: 11px; color: #778195;">Manager</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">Website</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">3 Kali</td>
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">Selasa, 04 Agu 2026</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #006B3F; text-decoration: none; font-weight: 800;">Lihat Riwayat</a>
                    </td>
                </tr>

                <!-- Data Dummy 2 -->
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">2</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Siti Aminah</div>
                        <div style="font-size: 11px; color: #778195;">089876543211</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">CV Berkah Mandiri</div>
                        <div style="font-size: 11px; color: #778195;">Owner</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">Sistem POS</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">1 Kali</td>
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">Senin, 03 Agu 2026</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #006B3F; text-decoration: none; font-weight: 800;">Lihat Riwayat</a>
                    </td>
                </tr>

                <!-- Data Dummy 3 -->
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">3</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Rian Pratama</div>
                        <div style="font-size: 11px; color: #778195;">085711223344</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">PT Solusi Digital Nusantara</div>
                        <div style="font-size: 11px; color: #778195;">IT Consultant</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">SEO</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">2 Kali</td>
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">Minggu, 02 Agu 2026</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #006B3F; text-decoration: none; font-weight: 800;">Lihat Riwayat</a>
                    </td>
                </tr>

                <!-- Data Dummy 4 -->
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">4</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">Dewi Lestari</div>
                        <div style="font-size: 11px; color: #778195;">082199887766</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">UD Sumber Rejeki</div>
                        <div style="font-size: 11px; color: #778195;">Purchasing</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">Website</span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 700;">4 Kali</td>
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">Sabtu, 01 Agu 2026</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="#" style="color: #006B3F; text-decoration: none; font-weight: 800;">Lihat Riwayat</a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195;">
        <span>Menampilkan data tamu</span>
        <div style="display: flex; gap: 6px; align-items: center;" id="pagination-wrapper">
            {{-- Tombol Sebelumnya --}}
            <button type="button" onclick="ubahHalaman('prev')" style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; color: #778195; font-weight: 700; cursor: pointer; pointer-events: auto !important;">Sebelumnya</button>
            
            {{-- Daftar Nomor Halaman --}}
            <button type="button" onclick="pilihHalaman(1)" class="page-btn" data-page="1" style="padding: 6px 12px; border: 1px solid #006B3F; background: #006B3F; color: #fff; border-radius: 8px; font-weight: 800; cursor: pointer; pointer-events: auto !important;">1</button>
            <button type="button" onclick="pilihHalaman(2)" class="page-btn" data-page="2" style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; color: #778195; border-radius: 8px; font-weight: 700; cursor: pointer; pointer-events: auto !important;">2</button>
            <button type="button" onclick="pilihHalaman(3)" class="page-btn" data-page="3" style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; color: #778195; border-radius: 8px; font-weight: 700; cursor: pointer; pointer-events: auto !important;">3</button>
            
            {{-- Tombol Selanjutnya --}}
            <button type="button" onclick="ubahHalaman('next')" style="padding: 6px 12px; border: 1px solid #e8edf5; background: #fff; border-radius: 8px; color: #778195; font-weight: 700; cursor: pointer; pointer-events: auto !important;">Selanjutnya</button>
        </div>
    </div>

    {{-- Script Interaktif untuk Memindahkan Warna Hijau --}}
    <script>
        let halamanAktif = 1;
        const totalHalaman = 3; // Ubah sesuai jumlah total halaman yang kamu inginkan

        function pilihHalaman(nomor) {
            halamanAktif = nomor;
            perbaruiTampilanTombol();
        }

        function ubahHalaman(arah) {
            if (arah === 'prev' && halamanAktif > 1) {
                halamanAktif--;
            } else if (arah === 'next' && halamanAktif < totalHalaman) {
                halamanAktif++;
            }
            perbaruiTampilanTombol();
        }

        function perbaruiTampilanTombol() {
            const tombolNomor = document.querySelectorAll('.page-btn');
            tombolNomor.forEach(btn => {
                const halaman = parseInt(btn.getAttribute('data-page'));
                if (halaman === halamanAktif) {
                    // Gaya saat tombol aktif (Warna Hijau)
                    btn.style.background = '#006B3F';
                    btn.style.borderColor = '#006B3F';
                    btn.style.color = '#fff';
                    btn.style.fontWeight = '800';
                } else {
                    // Gaya saat tombol tidak aktif (Warna Putih biasa)
                    btn.style.background = '#fff';
                    btn.style.borderColor = '#e8edf5';
                    btn.style.color = '#778195';
                    btn.style.fontWeight = '700';
                }
            });
        }
    </script>
@endsection