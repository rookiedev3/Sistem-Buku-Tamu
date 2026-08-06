@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header & Filter -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 4px;">Riwayat Kunjungan Tamu 📋</h2>
                <p style="font-size: 13px; color: #778195; margin: 0;">Arsip lengkap semua tamu yang pernah Anda tangani beserta hasil meeting dan status prospeknya.</p>
            </div>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('pic.riwayat') }}" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Cari Nama / Instansi</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Contoh: Budi atau PT..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="width: 180px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="width: 180px;">
                <label style="font-size: 11px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px; text-transform: uppercase;">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; height: 41px;">
                    Filter
                </button>
                @if(request()->hasAny(['keyword', 'start_date', 'end_date']))
                    <a href="{{ route('pic.riwayat') }}" style="background: #f1f5f9; color: #475569; text-decoration: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; height: 41px;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Arsip Riwayat Kunjungan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin-bottom: 16px;">Daftar Arsip Kunjungan</h3>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Tanggal & Waktu</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Keperluan</th>
                        <th style="padding: 14px; text-align: center;">Catatan Pertemuan</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Status Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $index => $v)
                    @php 
                        // Normalisasi status ke huruf kecil untuk antisipasi bahasa Inggris/Indonesia
                        $statusLower = strtolower(trim($v->status ?? '')); 
                        $level = strtolower(trim($v->potential_level ?? ''));
                        
                        // Cek apakah status menunjukkan selesai/completed
                        $isCompleted = in_array($statusLower, ['completed', 'selesai', 'meeting selesai']);
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">{{ $visits->firstItem() + $index }}</td>
                        <td style="padding: 14px; color: #778195; font-weight: 600;">
                            {{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->translatedFormat('d F Y') : '-' }}<br>
                            <span style="font-size: 11px;">{{ $v->check_in_at ? \Carbon\Carbon::parse($v->check_in_at)->format('H:i') . ' WIB' : '' }}</span>
                        </td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">{{ $v->guest->name ?? '-' }}</strong>
                            <span style="font-size: 11px; color: #778195;">{{ $v->guest->company_name ?? '-' }}</span>
                        </td>
                        <td style="padding: 14px; color: #475569;">
                            {{ $v->purpose->name ?? $v->purpose }}
                        </td>
                        
                        <!-- Kolom Tombol Modal Catatan -->
                        <td style="padding: 14px; text-align: center;">
                            @if($isCompleted)
                                <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $v->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    📝 Lihat Catatan
                                </button>
                            @else
                                <span style="font-style: italic; color: #94a3b8; font-size: 12px;">Dibatalkan / Belum Selesai</span>
                            @endif
                        </td>
<!-- Kolom Status Akhir -->
<td style="padding: 14px; text-align: center;">
    @if(in_array($statusLower, ['cancelled', 'dibatalkan']))
        <span style="background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
            Dibatalkan
        </span>
    @elseif($isCompleted)
        @if($level == 'cold')
            <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                Kunjungan Biasa
            </span>
        @elseif($level == 'warm')
            <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                Warm Lead
            </span>
        @elseif($level == 'hot')
            <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                Hot Lead 🔥
            </span>
        @elseif($level == 'deal')
            <span style="background: #dcfce7; color: #15803d; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                Berhasil (Deal) 🎉
            </span>
        @elseif($level == 'drop')
            <span style="background: #fee2e2; color: #b91c1c; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                Drop
            </span>
        @else
            <span style="background: #e6f4ed; color: #006B3F; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                Selesai
            </span>
        @endif
    @else
        <span style="background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
            Dibatalkan
        </span>
    @endif
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Belum ada riwayat kunjungan yang ditangani.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $visits->links() }}
        </div>
    </div>

</div><!-- ============================================== -->
<!-- KUMPULAN MODAL CATATAN DI LUAR TABEL           -->
<!-- ============================================== -->
@foreach($visits as $v)
    @php    
        $statusLowerModal = strtolower(trim($v->status ?? ''));
        $isCompletedModal = in_array($statusLowerModal, ['completed', 'selesai', 'meeting selesai', 'sedang bertemu']);
        
        // Menentukan teks keterangan jadwal aktif yang dinamis berdasarkan potential_level
        $levelModal = strtolower(trim($v->potential_level ?? ''));
        if ($levelModal == 'deal') {
            $scheduleText = 'Sudah Deal 🎉';
        } elseif ($levelModal == 'drop') {
            $scheduleText = 'Proses Dibatalkan / Drop';
        } elseif ($levelModal == 'cold') {
            $scheduleText = 'Kunjungan Biasa (Tanpa Follow-Up)';
        } else {
            $scheduleText = $v->follow_up_at ? \Carbon\Carbon::parse($v->follow_up_at)->translatedFormat('d F Y') : 'Tidak ada jadwal lanjutan';
        }
    @endphp

    @if($isCompletedModal)
        <div class="modal fade" id="noteModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                        <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033;">
                            Riwayat & Hasil Pertemuan - {{ $v->guest->name ?? 'Tamu' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6; max-height: 70vh; overflow-y: auto;">
                        
                        <!-- Status & Jadwal Terkini -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; gap: 20px;">
                            <div>
                                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Status Prospek Terakhir:</div>
                                <div style="font-weight: 800; color: #172033; text-transform: capitalize;">
                                    {{ $v->potential_level ?? 'Belum ada status' }}
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal / Keterangan Status:</div>
                                <div style="font-weight: 700; color: #006B3F;">
                                    {{ $scheduleText }}
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Awal Pertemuan -->
                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">
                                📌 Catatan Pertemuan Awal:
                            </label>
                            <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; color: #1e293b;">
                                {{ $v->meeting_result ?? 'Tidak ada catatan awal yang ditinggalkan.' }}
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 12px 24px;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection