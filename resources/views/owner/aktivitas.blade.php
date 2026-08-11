@extends('layouts.app')

@section('content')
<style>
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;   /* <-- tambahan ini */
        color: #ffff;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        background: #0284c7;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        margin-bottom: 20px;
    }
    /* .back-btn:hover {
        background: #f9fafb;
        color: #111827;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            color: #000000;
    } */
</style>

<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- TOMBOL KEMBALI SEPERTI HALAMAN LAPORAN PRODUK -->
    <a href="{{ route('owner.dashboard') }}" class="back-btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>

    <div>
        <h2 style="font-size:20px; font-weight:800; color:#172033; margin-bottom:4px;">Aktivitas Terbaru ⚡</h2>
        <p style="font-size:13px; color:#778195; margin:0;">Seluruh riwayat perubahan status kunjungan.</p>
    </div>

    <div class="card border-0 rounded-4" style="background:#fff; border:1px solid #e8edf5 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.02); padding:24px;">

        <form method="GET" action="{{ route('owner.activity-log') }}" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; margin-bottom:16px;">
            <div style="flex:1; min-width:220px;">
                <label style="font-size:11px; font-weight:700; color:#5c6678; display:block; margin-bottom:6px; text-transform:uppercase;">Cari Nama / Instansi</label>
                <input type="text" name="keyword" value="{{ $keyword }}"
                       autocomplete="off" placeholder="Cari nama/instansi..."
                       style="width:100%; padding:10px 14px; border:1px solid #e8edf5; border-radius:10px; font-size:13px; color:#172033; outline:none; box-sizing:border-box; background:#fbfcfe;">
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" style="background:#013220; color:#fff; border:none; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; height:41px;">
                    Cari
                </button>
                @if($keyword)
                    <a href="{{ route('owner.activity-log') }}" style="background:#f1f5f9; color:#475569; text-decoration:none; padding:10px 16px; border-radius:10px; font-size:13px; font-weight:700; display:inline-flex; align-items:center; height:41px; box-sizing:border-box;">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div style="font-size:12px; color:#778195; margin-bottom:16px;">
            @if($activities->total() > 0)
                Menampilkan {{ $activities->firstItem() }}–{{ $activities->lastItem() }} dari {{ $activities->total() }} aktivitas
                @if($keyword) untuk "<strong>{{ $keyword }}</strong>" @endif
            @else
                Tidak ada aktivitas ditemukan.
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            @forelse($activities as $activity)
                <div class="d-flex align-items-center gap-3 p-2.5 rounded-3" style="background: #f8fafc; padding:14px;">
                    <div style="width: 38px; height: 38px; background: #e8f8f1; color: #21a86b; border-radius: 10px; display: grid; place-items: center; font-weight: bold; flex-shrink: 0; font-size: 12px;">
                        {{ strtoupper(substr($activity->guest_name ?? '-', 0, 2)) }}
                    </div>
                    <div class="flex-grow-1" style="overflow: hidden;">
                        <h6 class="m-0 text-dark fw-bold" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $activity->guest_name ?? 'Tanpa nama' }}@if($activity->company_name) ({{ $activity->company_name }})@endif
                        </h6>
                        <span class="text-muted" style="font-size: 11px;">Status diubah: {{ $activity->new_status }}</span>
                    </div>
                    <span class="text-muted" style="font-size: 11px; white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($activity->changed_at)->translatedFormat('d M Y, H:i') }}
                    </span>
                </div>
            @empty
                <p class="text-muted mb-0" style="font-size: 13px; text-align:center; padding:20px;">Belum ada aktivitas.</p>
            @endforelse
        </div>

        <div style="margin-top: 14px;">
            @include('partials.pagination', ['paginator' => $activities])
        </div>
    </div>

</div>
@endsection