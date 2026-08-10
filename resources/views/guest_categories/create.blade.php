@extends('layouts.frontoffice') {{-- Menggunakan layout frontoffice yang konsisten --}}

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Tambah Guest Categories</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Lengkapi formulir di bawah ini untuk menambahkan kategori tamu baru.</p>
    </div>
    
    <a href="{{ route('guest-categories.index') }}" style="background: #e5484d; color: #ffffff; padding: 10px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; border: 1px solid #e8edf5; transition: all 0.2s; white-space: nowrap;">
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
    
    <form action="{{ route('guest-categories.store') }}" method="POST">
        @csrf

        {{-- Nama Guest Categories --}}
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Nama Guest Categories <span style="color: #e5484d;">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                placeholder="Contoh: VIP / Klien Utama"
                style="width: 100%; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;">
        </div>

        {{-- Color Guest Categories --}}
        <div style="margin-bottom: 28px;">
            <label for="color_text" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Color Guest Categories <span style="color: #e5484d;">*</span>
            </label>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <input type="color" name="color_picker" id="color_picker" value="{{ old('color', '#006B3F') }}"
                    style="width: 45px; height: 42px; border: 1px solid #e8edf5; border-radius: 10px; background: #fff; cursor: pointer; padding: 2px;"
                    oninput="document.getElementById('color_text').value = this.value">
                <input type="text" name="color" id="color_text" value="{{ old('color', '#006B3F') }}"
                    placeholder="Contoh: #006B3F"
                    style="flex: 1; min-width: 200px; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;"
                    oninput="document.getElementById('color_picker').value = this.value">
            </div>
        </div>

        {{-- Tombol Aksi (Responsif & Fleksibel) --}}
        <div style="display: flex; gap: 10px; padding-top: 8px; flex-wrap: wrap;">
            <button type="submit"
                style="background:#013220; color: #fff; padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 8px 20px rgba(0,107,63,.2); flex: 1; min-width: 120px;">
                Simpan Data
            </button>
            <a href="{{ route('guest-categories.index') }}"
                style="background: #f1f4f9; color: #778195; padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; border: none; display: inline-block; text-align: center; flex: 1; min-width: 100px;">
                Batal
            </a>
        </div>
    </form>

</div>

@endsection