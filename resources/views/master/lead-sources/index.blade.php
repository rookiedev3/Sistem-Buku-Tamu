@extends('layouts.app')

@section('content')

{{-- Navigasi Tab Master Data --}}
<div class="d-flex gap-2 border-bottom pb-3 mb-4">
    <a href="{{ route('branches.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('branches*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('branches*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Branches
    </a>
    
    <a href="{{ route('products.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('products*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('products*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Products
    </a>
    
    <a href="{{ route('lead-sources.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('lead-sources*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('lead-sources*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Lead Sources
    </a>
    
    <a href="{{ route('visit-purposes.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('visit-purposes*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('visit-purposes*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Visit Purposes
    </a>
    
    <a href="{{ route('guest-categories.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('guest-categories*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('guest-categories*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Guest Categories
    </a>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Daftar Lead Sources</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola dan pantau seluruh data sumber lead perusahaan.</p>
    </div>
    
    <a href="{{ route('lead-sources.create') }}" style="background: #006B3F; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(0,107,63,.2); border: none; cursor: pointer;">
        + Tambah Lead Sources
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
        <input type="text" placeholder="Cari sumber lead..." style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Lead Sources: <strong style="color: #172033; font-weight: 800;">{{ $lead_sources->count() }} Sumber</strong>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800; width: 60px;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama Sumber Lead</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse ($lead_sources as $index => $lead_src)
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px; font-weight: 800;">{{ $lead_src->name }}</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; justify-content: center; align-items: center; gap: 12px;">
                            <a href="{{ route('lead-sources.edit', $lead_src->id) }}" style="color: #006B3F; text-decoration: none; font-weight: 800;">
                                Edit
                            </a>
                            <form action="{{ route('lead-sources.destroy', $lead_src->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Lead Source ini?')" style="margin: 0; display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e5484d; text-decoration: none; font-weight: 800; cursor: pointer; padding: 0; font-size: 13px; font-family: inherit;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 32px 20px; text-align: center; color: #778195; font-size: 13px;">
                        Belum ada data Lead Sources.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195;">
        <span>Menampilkan {{ $lead_sources->count() }} data lead sources</span>
    </div>

</div>

@endsection