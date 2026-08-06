@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Edit Guest Categories</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Perbarui data kategori tamu perusahaan.</p>
    </div>
    
    <a href="{{ route('guest-categories.index') }}" style="background: #ffffff; color: #778195; padding: 10px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; border: 1px solid #e8edf5; transition: all 0.2s;">
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

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); padding: 28px; max-width: 680px;">
    
    <form action="{{ route('guest-categories.update', $guest_category->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama Guest Categories --}}
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Nama Kategori <span style="color: #e5484d;">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name', $guest_category->name) }}"
                placeholder="Contoh: VIP / Klien Utama"
                style="width: 100%; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;">
        </div>

        {{-- Color Guest Categories --}}
        <div style="margin-bottom: 28px;">
            <label for="color" style="display: block; font-size: 13px; font-weight: 700; color: #172033; margin-bottom: 8px;">
                Warna Label (Color) <span style="color: #e5484d;">*</span>
            </label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color" id="color_picker" value="{{ old('color', $guest_category->color) }}"
                    style="width: 45px; height: 42px; border: 1px solid #e8edf5; border-radius: 10px; background: #fff; cursor: pointer; padding: 2px;"
                    oninput="document.getElementById('color_text').value = this.value">
                <input type="text" name="color" id="color_text" value="{{ old('color', $guest_category->color) }}"
                    placeholder="Contoh: #006B3F"
                    style="flex: 1; padding: 11px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #fff; color: #172033; box-sizing: border-box; font-family: inherit;"
                    oninput="document.getElementById('color_picker').value = this.value">
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div style="display: flex; gap: 10px;">
            <button type="submit"
                style="background: #006B3F; color: #fff; padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 8px 20px rgba(0,107,63,.2);">
                Update Data
            </button>
            <a href="{{ route('guest-categories.index') }}"
                style="background: #f1f4f9; color: #778195; padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; border: none; display: inline-block;">
                Batal
            </a>
        </div>
    </form>

</div>

@endsection