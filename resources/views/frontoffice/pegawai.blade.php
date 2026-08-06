@extends('layouts.frontoffice')

@section('content')

@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 20px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            SISTEM FRONT OFFICE
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Daftar Pegawai & PIC Tujuan 👥
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Informasi kontak, jabatan, dan status ketersediaan pegawai untuk tujuan kunjungan tamu.
        </p>
    </div>

    <button onclick="bukaModalTambah()" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
        + Tambah Pegawai
    </button>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Direktori Pegawai Kantor</h3>
        
        <div>
            <input type="text" id="cariPegawai" placeholder="Cari nama pegawai / departemen..." onkeyup="filterTabelPegawai()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 240px;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="tabelPegawai" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">Nama & Email</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Jabatan & Departemen</th>
                    <th style="padding: 14px 20px; font-weight: 700;">No. WhatsApp / Kontak</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status Ketersediaan</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($pegawaiList as $user)
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">{{ $user->name }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $user->email }}</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="text-transform: capitalize;">{{ $user->role }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $user->branch ? $user->branch->name : 'Semua Cabang' }}</div>
                    </td>
                    <td style="padding: 16px 20px;">{{ $user->phone ?? '-' }}</td>
                    <td style="padding: 16px 20px;">
                        @if($user->is_active)
                        <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            🟢 Di Tempat (Tersedia)
                        </span>
                        @else
                        <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            🔴 Non-aktif / Di Luar
                        </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <button onclick="bukaModalUbah(
                            '{{ $user->id }}', 
                            '{{ addslashes($user->name) }}', 
                            '{{ $user->email }}', 
                            '{{ $user->role }}', 
                            '{{ $user->branch_id }}', 
                            '{{ $user->phone }}'
                        )" 
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                            Ubah
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 30px; text-align: center; color: #64748b;">Belum ada data pegawai di database.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan seluruh data pegawai aktif</span>
        <span>Total: {{ $pegawaiList->count() }} Pegawai</span>
    </div>

</div>


<div id="modalTambahPegawai" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 500px; max-width: 90%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">
        
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Tambah Pegawai / PIC Baru 👤</h3>
            <button onclick="tutupModalTambah()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('frontoffice.storePegawai') }}" method="POST">
            @csrf
            <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 12px; max-height: 70vh; overflow-y: auto;">
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Masukkan nama pegawai" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Email Kantor *</label>
                    <input type="email" name="email" required placeholder="nama@company.com" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih Peran / Level *</label>
                    <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        <option value="pic">PIC (Person in Charge)</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="security">Security</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Cabang Kerja *</label>
                    <select name="branch_id" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        <option value="">Semua Cabang / Pusat</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nomor WhatsApp *</label>
                    <input type="tel" name="phone" required placeholder="081234567890" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
            </div>

            <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="tutupModalTambah()" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Simpan Pegawai</button>
            </div>
        </form>

    </div>
</div>


<div id="modalUbahPegawai" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 500px; max-width: 90%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">
        
        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Ubah Data Pegawai ✏️</h3>
            <button onclick="tutupModalUbah()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <form id="formUbahPegawai" method="POST">
            @csrf
            <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 12px; max-height: 70vh; overflow-y: auto;">
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nama Lengkap *</label>
                    <input type="text" name="name" id="ubahNama" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Email Kantor *</label>
                    <input type="email" name="email" id="ubahEmail" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Peran / Level *</label>
                    <select name="role" id="ubahRole" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        <option value="pic">PIC (Person in Charge)</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="security">Security</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Cabang Kerja *</label>
                    <select name="branch_id" id="ubahBranch" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        <option value="">Semua Cabang / Pusat</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nomor WhatsApp *</label>
                    <input type="tel" name="phone" id="ubahTelepon" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
            </div>

            <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="tutupModalUbah()" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

<script>
    function filterTabelPegawai() {
        const input = document.getElementById('cariPegawai');
        const filter = input.value.toLowerCase();
        const tabel = document.getElementById('tabelPegawai');
        const tr = tabel.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let tdNama = tr[i].getElementsByTagName('td')[0];
            let tdDept = tr[i].getElementsByTagName('td')[1];
            if (tdNama && tdDept) {
                let teksNama = tdNama.textContent || tdNama.innerText;
                let teksDept = tdDept.textContent || tdDept.innerText;
                if (teksNama.toLowerCase().indexOf(filter) > -1 || teksDept.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Modal Tambah
    function bukaModalTambah() {
        document.getElementById('modalTambahPegawai').style.display = 'flex';
    }

    function tutupModalTambah() {
        document.getElementById('modalTambahPegawai').style.display = 'none';
    }

    // Modal Ubah
    function bukaModalUbah(id, name, email, role, branchId, phone) {
        document.getElementById('ubahNama').value = name;
        document.getElementById('ubahEmail').value = email;
        document.getElementById('ubahRole').value = role;
        document.getElementById('ubahBranch').value = branchId ? branchId : '';
        document.getElementById('ubahTelepon').value = phone;

        // Set action form dynamically
        const form = document.getElementById('formUbahPegawai');
        form.action = `/frontoffice/pegawai/${id}/update`;

        document.getElementById('modalUbahPegawai').style.display = 'flex';
    }

    function tutupModalUbah() {
        document.getElementById('modalUbahPegawai').style.display = 'none';
    }
</script>

@endsection