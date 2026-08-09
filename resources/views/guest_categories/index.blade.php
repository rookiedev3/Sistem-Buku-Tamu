@extends('layouts.app')

@section('content')

{{-- Navigasi Tab Master Data --}}
<div class="d-flex gap-2 border-bottom pb-3 mb-4">
    <a href="{{ route('branches.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('branches*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('branches*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Branches
    </a>
    
    <a href="{{ route('products.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('products*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('products*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Products
    </a>
    
    <a href="{{ route('lead-sources.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('lead-sources*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('lead-sources*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Lead Sources
    </a>
    
    <a href="{{ route('visit-purposes.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('visit-purposes*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('visit-purposes*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Visit Purposes
    </a>
    
    <a href="{{ route('guest-categories.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('guest-categories*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('guest-categories*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Guest Categories
    </a>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Daftar Guest Categories</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola dan pantau seluruh kategori tamu perusahaan.</p>
    </div>
    
    <a href="{{ route('guest-categories.create') }}" style="background: #013220; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(0,107,63,.2); border: none; cursor: pointer;">
        + Tambah Guest Categories
    </a>
</div>

{{-- Alert Notifikasi --}}
@if (session('success'))
    <div style="background: #e6f4ea; border: 1px solid #ceebd6; color: #137333; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <input type="text" placeholder="Cari kategori tamu..." style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Kategori: <strong style="color: #172033; font-weight: 800;">{{ $guest_category->count() }} Kategori</strong>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800; width: 60px;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama Kategori</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Warna (Color)</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse ($guest_category as $index => $guest)
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px; font-weight: 800;">{{ $guest->name }}</td>
                    <td style="padding: 16px 20px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="display: inline-block; width: 16px; height: 16px; border-radius: 4px; background-color: {{ $guest->color }}; border: 1px solid rgba(0,0,0,0.1);"></span>
                            <span style="font-family: monospace; color: #778195; font-weight: 600;">{{ $guest->color }}</span>
                        </div>
                    </td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('guest-categories.edit', $guest->id) }}" style="background: #e8f8f1; color: #013220; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-pencil-fill" style="font-size: 11px;"></i> Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('guest-categories.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Guest Category ini?')" style="margin: 0; display: inline;">
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
                    <td colspan="4" style="padding: 32px 20px; text-align: center; color: #778195; font-size: 13px;">
                        Belum ada data Guest Categories.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195;">
        <span>Menampilkan {{ $guest_category->count() }} data kategori</span>
    </div>

</div>

@endsection