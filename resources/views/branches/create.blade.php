@extends('layouts.frontoffice') {{-- Menggunakan layout frontoffice yang konsisten --}}

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Tambah Branch Baru</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Lengkapi formulir di bawah ini untuk menambahkan data cabang baru.</p>
    </div>
    
    <a href="{{ route('branches.index') }}" style="background: #e5484d; color: #ffffff; padding: 10px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; border: 1px solid #e8edf5; transition: all 0.2s; white-space: nowrap;">
        &larr; Kembali
    </a>
</div>

{{-- Alert Error Validation --}}
@if ($errors->any())
    <div style="background: #fde8e8; border: 1px solid #f8b4b4; color: #c81e1e; padding: 14px 18px; border-radius: 14px; font-size: 13px; margin-bottom: 24px;">
        <div style="font-weight: 800; margin-bottom: 6px;">Terdapat beberapa kesalahan:</div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); padding: 24px; width: 100%; max-width: 680px; box-sizing: border-box; margin-bottom: 32px;">
    
    <form action="{{ route('branches.store') }}" method="POST">
        @csrf

        {{-- Kode Branch --}}
        <div style="margin-bottom: 20px;">
            <label for="code" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Kode Branch <span style="color: #e5484d;">*</span>
            </label>
            <input type="text" name="code" id="code" value="{{ old('code') }}"
                placeholder="Contoh: BR-001"
                style="width: 100%; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;">
        </div>

        {{-- Nama Branch --}}
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Nama Branch <span style="color: #e5484d;">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                placeholder="Contoh: Branch Yogyakarta"
                style="width: 100%; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;">
        </div>

        {{-- Alamat --}}
        <div style="margin-bottom: 20px;">
            <label for="address" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Alamat Lengkap
            </label>
            <textarea name="address" id="address" rows="3"
                placeholder="Masukkan alamat lengkap branch..."
                style="width: 100%; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; resize: vertical; font-family: inherit;">{{ old('address') }}</textarea>
        </div>

        {{-- Nomor Telepon --}}
        <div style="margin-bottom: 20px;">
            <label for="phone" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Nomor Telepon
            </label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                placeholder="Contoh: 0274-123456"
                style="width: 100%; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;">
        </div>

        {{-- Status Aktif --}}
        <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 10px; background: #fbfcfe; padding: 12px 16px; border-radius: 10px; border: 1px solid #e8edf5;">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', true) ? 'checked' : '' }}
                style="width: 16px; height: 16px; accent-color: #1463ff; cursor: pointer;">
            <label for="is_active" style="font-size: 13px; font-weight: 700; color: #172033; cursor: pointer;">
                Aktifkan Branch Ini
            </label>
        </div>

        {{-- Tombol Aksi (Responsif & Fleksibel) --}}
        <div style="display: flex; gap: 10px; padding-top: 8px; flex-wrap: wrap;">
            <button type="submit"
                style="background: #0284c7 ; color: #fff; padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 8px 20px rgba(20,99,255,.2); flex: 1; min-width: 120px;">
                Simpan Data
            </button>
            <a href="{{ route('branches.index') }}"
                style="background: #f1f4f9; color: #778195; padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; border: none; display: inline-block; text-align: center; flex: 1; min-width: 100px;">
                Batal
            </a>
        </div>
    </form>

</div>

@endsection