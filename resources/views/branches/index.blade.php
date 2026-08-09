@extends('layouts.app')

@section('content')
@include('master.nav-tabs')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Data Branch</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola dan pantau seluruh data cabang (branch) perusahaan.</p>
    </div>
    
    <a href="{{ route('branches.create') }}" style="background: #013220; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(0,107,63,.2); border: none; cursor: pointer;">
        + Tambah Branch
    </a>
</div>

{{-- Alert Notifikasi --}}
@if (session('success'))
    <div style="background: #e6f4ea; border: 1px solid #ceebd6; color: #137333; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: #fde8e8; border: 1px solid #f8b4b4; color: #c81e1e; padding: 12px 16px; border-radius: 12px; font-size: 13px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <input type="text" placeholder="Cari nama atau kode branch..." style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Branch: <strong style="color: #172033; font-weight: 800;">{{ $branches->count() }} Cabang</strong>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800; width: 60px;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Kode</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama Branch</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Alamat</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Telepon</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Status</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse ($branches as $index => $b)
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">
                            {{ $b->code }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px; font-weight: 800;">{{ $b->name }}</td>
                    <td style="padding: 16px 20px; color: #778195;">{{ $b->address }}</td>
                    <td style="padding: 16px 20px; text-align: center; font-weight: 600;">{{ $b->phone }}</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        @if ($b->is_active)
                            <span style="background: #e6f4ea; color: #137333; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-block;">
                                Aktif
                            </span>
                        @else
                            <span style="background: #f1f4f9; color: #778195; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-block;">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('branches.edit', $b->id) }}" style="background: #e8f8f1; color: #013220; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-pencil-fill" style="font-size: 11px;"></i> Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('branches.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Branch ini?')" style="margin: 0; display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #fef2f2; border: none; color: #e5484d; padding: 6px 12px; border-radius: 8px; font-weight: 800; cursor: pointer; font-size: 12px; font-family: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-trash-fill" style="font-size: 11px;"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 32px 20px; text-align: center; color: #778195; font-size: 13px;">
                        Belum ada data branch.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195;">
        <span>Menampilkan {{ $branches->count() }} data branch</span>
    </div>

</div>

@endsection