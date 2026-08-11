@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Database Tamu</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Arsip lengkap seluruh riwayat kunjungan dan data instansi tamu.</p>
    </div>

</div>

{{-- KONTAINER UTAMA TABEL --}}
<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">

    {{-- Header Tabel & Form Pencarian (Tanpa Selection Produk) --}}
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <form action="{{ url()->current() }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin: 0;">
            {{-- Pertahankan per_page jika ada --}}
            @if(request()->has('per_page'))
            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, instansi, atau nomor WA..."
                style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 300px; outline: none; background: #fff; color: #172033;">

            <button type="submit" style="background: #013220; color: #ffffff; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Cari
            </button>

            @if(request()->filled('search'))
            <a href="{{ url()->current() }}"
                style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box; transition: all 0.2s ease;"
                onmouseover="this.style.background='#fee2e2'"
                onmouseout="this.style.background='#fef2f2'">
                Reset
            </a>
            @endif
        </form>

        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Arsip Tamu: <strong style="color: #172033; font-weight: 800;">{{ method_exists($guests, 'total') ? $guests->total() : count($guests) }} Orang</strong>
        </div>
    </div>

    {{-- Pembungkus Tabel --}}
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 800px;">
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
                @forelse ($guests as $index => $guest)
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    {{-- Penomoran Dinamis Antar Halaman --}}
                    <td style="padding: 16px 20px; font-weight: 700; color: #64748b;">
                        {{ method_exists($guests, 'firstItem') && $guests->firstItem() ? $guests->firstItem() + $index : $index + 1 }}
                    </td>

                    {{-- Nama & Phone --}}
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 800;">{{ $guest->name }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $guest->phone ?? '-' }}</div>
                    </td>

                    {{-- Perusahaan & Jabatan --}}
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">{{ $guest->company_name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $guest->position ?? '-' }}</div>
                    </td>

                    {{-- Minat Produk --}}
                    <td style="padding: 16px 20px;">
                        <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800; display: inline-block;">
                            {{ $guest->category->name ?? $guest->product_interest ?? '-' }}
                        </span>
                    </td>

                    {{-- Total Kunjungan --}}
                    <td style="padding: 16px 20px; font-weight: 700;">
                        {{ $guest->visits_count ?? 1 }} Kali
                    </td>

                    {{-- Terakhir Berkunjung --}}
                    <td style="padding: 16px 20px; color: #778195; font-size: 12px;">
                        {{ $guest->updated_at ? \Carbon\Carbon::parse($guest->updated_at)->translatedFormat('l, d M Y') : '-' }}
                    </td>

                    {{-- Aksi --}}
                    <td style="padding: 16px 20px; text-align: center;">
                        <a href="{{ route('owner.databaseTamuDetail', $guest->id) }}" style="color: #006B3F; text-decoration: none; font-weight: 800;">
                            Lihat Riwayat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 30px; text-align: center; color: #778195;">
                        Data tamu belum tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- FOOTER KOTAK (PAGINATION LINK & PER_PAGE SELECTOR) --}}
    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe;">
        @include('partials.pagination', ['paginator' => $guests])
    </div>

</div>

@endsection