@extends('layouts.frontoffice') {{-- Mengikuti layout frontoffice yang konsisten --}}

@section('content')

@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 20px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Manajemen Pengguna
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Kelola daftar akun staf, resepsionis, dan admin yang memiliki akses ke dashboard.
        </p>
    </div>

    <a href="{{ route('user.create') }}" style="background: #013220; color: #fff; padding: 10px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,107,63,0.2); white-space: nowrap;">
        + Tambah Pengguna
    </a>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">

    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Daftar Akun Aktif</h3>

        <div style="width: 100%; sm:width: auto;">
            <input type="text" id="searchUser" placeholder="Cari nama / email pengguna..." onkeyup="filterUserTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 100%; sm:width: 240px; box-sizing: border-box;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="userTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 850px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700; width: 60px;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Nama Lengkap</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Email / Telepon</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Hak Akses (Role)</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status Akun</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($users as $index => $u)
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px; font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px; font-weight: 700; color: #172033;">{{ $u->name }}</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700; color: #172033;">{{ $u->email }}</div>
                        <div style="font-size: 11px; color: #778195; margin-top: 2px;">{{ $u->phone ?? '-' }}</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($u->role)
                            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; display: inline-block;">
                                {{ strtoupper($u->role) }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 11px;">Belum ditentukan</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px;">
                        @if(is_null($u->role))
                            <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                Menunggu Persetujuan
                            </span>
                        @elseif($u->is_active)
                            <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                Aktif
                            </span>
                        @else
                            <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: wrap;">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('user.edit', $u->id) }}" style="background: #e8f8f1; color: #013220; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-pencil-fill" style="font-size: 11px;"></i> Edit
                            </a>

                            {{-- Tombol Hapus (Memicu Modal Konfirmasi Custom) --}}
                            <button type="button" onclick="confirmDelete('{{ $u->id }}', '{{ addslashes($u->name) }}')" style="background: #fef2f2; border: none; color: #e5484d; padding: 6px 12px; border-radius: 8px; font-weight: 800; cursor: pointer; font-size: 12px; font-family: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-trash-fill" style="font-size: 11px;"></i> Hapus
                            </button>

                            {{-- Form Tersembunyi untuk Eksekusi Hapus --}}
                            <form id="delete-form-{{ $u->id }}" action="{{ route('user.destroy', $u->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b;">Belum ada data pengguna yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px; flex-wrap: wrap; gap: 8px;">
        <span>Menampilkan data pengguna terdaftar</span>
        <span>Total: {{ count($users) }} Pengguna</span>
    </div>

</div>

<div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; padding: 28px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); text-align: center; box-sizing: border-box;">
        
        <div style="width: 52px; height: 52px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #dc2626; font-size: 22px;">
            ⚠️
        </div>

        <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0 0 8px 0;">Hapus Pengguna?</h3>
        <p style="font-size: 13px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">
            Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName" style="color: #172033;">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="closeDeleteModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>
            <button type="button" id="confirmDeleteSubmitBtn" style="flex: 1; background: #dc2626; color: #ffffff; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
    let activeDeleteUserId = null;

    function confirmDelete(userId, userName) {
        activeDeleteUserId = userId;
        document.getElementById('deleteUserName').innerText = userName;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        activeDeleteUserId = null;
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteSubmitBtn').addEventListener('click', function() {
        if (activeDeleteUserId) {
            document.getElementById('delete-form-' + activeDeleteUserId).submit();
        }
    });

    function filterUserTable() {
        const input = document.getElementById('searchUser');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('userTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let tdName = tr[i].getElementsByTagName('td')[1];
            let tdEmail = tr[i].getElementsByTagName('td')[2];
            if (tdName || tdEmail) {
                let txtName = tdName ? (tdName.textContent || tdName.innerText) : '';
                let txtEmail = tdEmail ? (tdEmail.textContent || tdEmail.innerText) : '';
                if (txtName.toLowerCase().indexOf(filter) > -1 || txtEmail.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

@endsection