@extends('layouts.frontoffice')

@section('content')

<div style="width: 100%; box-sizing: border-box; padding: 0;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Manajemen Pengguna</h1>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar akun staf, resepsionis, dan admin yang memiliki akses ke dashboard.</p>
        </div>
        
        <a href="{{ route('user.create') }}" style="background: #006B3F; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(0,107,63,.2); transition: 0.2s ease;" onmouseover="this.style.background='#004d2e'" onmouseout="this.style.background='#006B3F'">
            + Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div style="background: #e6f4ea; border: 1px solid #c8e6d3; color: #006B3F; padding: 14px 18px; border-radius: 12px; font-size: 13px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.08); overflow: hidden; width: 100%;">
        
        <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Akun Aktif</h3>
            <span style="font-size: 12px; color: #778195; font-weight: 700;">{{ count($users) }} Pengguna</span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                        <th style="padding: 14px 20px; font-weight: 800; width: 60px;">No</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Nama Lengkap</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Email / Telepon</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Hak Akses (Role)</th>
                        <th style="padding: 14px 20px; font-weight: 800;">Status Akun</th>
                        <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="color: #172033;">
                    @forelse($users as $index => $u)
                    <tr style="border-bottom: 1px solid #f1f4f9; transition: background 0.2s;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 16px 20px; font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                        <td style="padding: 16px 20px; font-weight: 800; color: #0f172a;">{{ $u->name }}</td>
                        <td style="padding: 16px 20px;">
                            <div style="font-weight: 700; color: #0f172a;">{{ $u->email }}</div>
                            <div style="font-size: 11.5px; color: #778195; margin-top: 2px;">{{ $u->phone ?? '-' }}</div>
                        </td>
                        <td style="padding: 16px 20px;">
                            <span style="background: #e6f4ea; color: #006B3F; padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 800; display: inline-block;">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>
                        <td style="padding: 16px 20px;">
                            @if($u->is_active)
                                <span style="background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 800; display: inline-block;">Aktif</span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 800; display: inline-block;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding: 16px 20px; text-align: center;">
                            <div style="display: flex; justify-content: center; gap: 8px; align-items: center;">
                                <a href="{{ route('user.edit', $u->id) }}" style="background: #eff6ff; color: #1e40af; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 800; text-decoration: none; transition: 0.2s;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">Edit</a>
                                
                                <form action="{{ route('user.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fef2f2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 800; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #778195; font-weight: 600;">
                            Belum ada data pengguna yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection