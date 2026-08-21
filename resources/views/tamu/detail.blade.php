@extends('layouts.app')

@section('content')

{{-- Tombol Kembali (Diubah menjadi Button warna #0284c7) --}}
<div style="margin-bottom: 20px;">
    <a href="{{ route('owner.databaseTamu') }}" 
       style="display: inline-block; background-color: #0284c7; color: #ffffff; text-decoration: none; font-size: 12px; font-weight: 700; padding: 8px 16px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.2s ease;">
        ← Kembali ke Database Tamu
    </a>
</div>

{{-- Header Profile Tamu --}}
<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; padding: 24px; box-shadow: 0 18px 50px rgba(31,53,97,.12); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
            <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0;">
                {{ $guest->name }}
            </h1>
            
            {{-- Minat Produk / Kategori --}}
            <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">
                Minat: {{ $guest->category->name ?? $guest->product_interest ?? '-' }}
            </span>

            {{-- Status VIP (Disamakan menjadi lencana kuning profesional) --}}
            @if($guest->is_vip)
               <span style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700;">VIP</span>          

            @endif
        </div>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            {{ $guest->company_name ?? '-' }} • {{ $guest->position ?? '-' }} • WhatsApp: {{ $guest->phone }}
        </p>
    </div>

    {{-- Total Kunjungan --}}
    <div style="background: #f8fafc; padding: 12px 18px; border-radius: 12px; border: 1px solid #e8edf5; text-align: right;">
        <div style="font-size: 11px; color: #778195; font-weight: 700;">Total Kunjungan</div>
        <div style="font-size: 18px; font-weight: 800; color: #1463ff;">
            {{ $guest->visits_count ?? $guest->visits->count() }} Kali
        </div>
    </div>
</div>

{{-- Tabel Timeline Riwayat Kunjungan --}}
<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Timeline Riwayat Kunjungan</h3>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800;">Tanggal & Waktu</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Bertemu Dengan (PIC)</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Keperluan / Catatan Pertemuan</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Status</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse ($guest->visits as $visit)
                    <tr style="border-bottom: 1px solid #f1f4f9;">
                        {{-- Tanggal & Waktu --}}
                        <td style="padding: 16px 20px; font-weight: 700;">
                            {{ \Carbon\Carbon::parse($visit->created_at)->translatedFormat('l, d M Y') }}
                            <br>
                            <span style="font-size: 11px; color: #778195; font-weight: normal;">
                                {{ \Carbon\Carbon::parse($visit->created_at)->format('H:i') }} WIB
                            </span>
                        </td>

                        {{-- Bertemu Dengan (PIC) --}}
                        <td style="padding: 16px 20px;">
                            {{ $visit->assignedUser->name ?? '-' }}
                        </td>

                        {{-- Keperluan / Catatan Pertemuan --}}
                        <td style="padding: 16px 20px;">
                            {{ $visit->notes ?? '-' }}
                        </td>

                        {{-- Status Badge --}}
                        <td style="padding: 16px 20px;">
                            @php
                                $status = strtolower($visit->status ?? 'selesai');
                            @endphp

                            @if(str_contains($status, 'deal') || str_contains($status, 'selesai'))
                                <span style="background: #e6fcf5; color: #0ca678; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">
                                    {{ $visit->status ?? 'Selesai' }}
                                </span>
                            @elseif(str_contains($status, 'follow up'))
                                <span style="background: #eef4ff; color: #1463ff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">
                                    {{ $visit->status ?? 'Follow Up' }}
                                </span>
                            @else
                                <span style="background: #f8fafc; color: #5c6678; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 800;">
                                    {{ $visit->status ?? 'Pertama Datang' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 24px; text-align: center; color: #778195;">
                            Belum ada riwayat kunjungan tercatat untuk tamu ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection