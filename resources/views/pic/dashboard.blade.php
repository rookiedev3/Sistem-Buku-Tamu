@extends('layouts.pic')

@section('content')

@php
    // 1. Amankan $visits: Jika tidak dikirim Controller, buat Collection kosong
    $visits = $visits ?? collect();

    // 2. Amankan $vipCount & $regularCount
    $vipCount = $vipCount ?? $visits->filter(function($v) {
        return optional($v->guest)->is_vip == true;
    })->count();

    $regularCount = $regularCount ?? ($visits->count() - $vipCount);
@endphp


<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Alert Sukses -->
    {{-- @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif --}}


    @if(session('success'))
    <div class="alert alert-success" style="border-radius: 12px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="border-radius: 12px; font-weight: 600;">
        <ul style="margin:0; padding-left: 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <!-- Bagian Sambutan & Statistik Ringkas -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Dashboard PIC / Sales 👋</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar tamu berdasarkan kategori VIP & Reguler, konfirmasi kehadiran, catat hasil pertemuan, dan pantau konversi lead.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu VIP Menunggu</span>
            <strong style="font-size: 24px; font-weight: 900; color: #d97706; margin-top: 4px;">{{ $vipCount }} Orang</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Tamu Reguler</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">{{ $regularCount }} Orang</strong>
        </div>
    </div>

    <!-- Tabel Daftar Tamu Ditugaskan -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Tamu Masuk & Kategori Pelanggan</h3>
            <span style="font-size: 12px; color: #778195; font-weight: 600;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Tamu & Instansi</th>
                        <th style="padding: 14px;">Kategori</th>
                        <th style="padding: 14px;">Keperluan</th>
                        <th style="padding: 14px;">Waktu / Jadwal</th>
                        <th style="padding: 14px; text-align: center;">Konfirmasi Kehadiran</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $index => $visit)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px; font-weight: 600;">{{ $index + 1 }}</td>
                            
                            <td style="padding: 14px;">
                                <strong style="display: block; color: #172033; font-weight: 800;">{{ $visit->guest->name ?? '-' }}</strong>
                                <span style="font-size: 11px; color: #778195;">{{ $visit->guest->company_name ?? '-' }}</span>
                            </td>

                            <td style="padding: 14px;">
                                @if(isset($visit->guest) && $visit->guest->is_vip)
                                    <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; border: 1px solid #fde68a;">⭐ VIP</span>
                                @else
                                    <span style="background: #e6f4ed; color: #006B3F; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800;">Reguler</span>
                                @endif
                            </td>

                            <td style="padding: 14px; color: #475569;">{{ $visit->purpose->name ?? $visit->purpose }}</td>
                            
                            <!-- Menampilkan Check-in jika ada, jika null ambil dari Scheduled At -->
                            <td style="padding: 14px; color: #778195; font-weight: 600;">
                                @if($visit->check_in_at)
                                    {{ \Carbon\Carbon::parse($visit->check_in_at)->format('H:i') }} WIB
                                @elseif($visit->scheduled_at)
                                    {{ \Carbon\Carbon::parse($visit->scheduled_at)->format('H:i') }} WIB <span style="font-size: 10px; color: #d97706;">(Jadwal)</span>
                                @else
                                    -
                                @endif
                            </td>
                            
                            <!-- Kolom Tombol Centang (✔) dan Silang (❌) -->
<!-- Kolom Konfirmasi Kehadiran -->
<td style="padding: 14px; text-align: center;">
    @php $statusLower = strtolower($visit->status); @endphp
    
    @if(in_array($statusLower, ['pending', 'waiting', 'menunggu']))
        <div style="display: flex; justify-content: center; gap: 6px;">
            <!-- Tombol Centang: Konfirmasi Bertemu -->
            <form action="{{ route('pic.updateStatus', $visit->id) }}" method="POST" style="margin:0;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Dikonfirmasi">
                <button type="submit" title="Konfirmasi Benar Bertemu" style="background: #e6f4ed; color: #006B3F; border: 1px solid #bbf7d0; width: 34px; height: 34px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">✓</button>
            </form>

            <!-- Tombol Silang: Batalkan -->
            <form action="{{ route('pic.updateStatus', $visit->id) }}" method="POST" style="margin:0;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Dibatalkan">
                <button type="submit" title="Tolak / Salah Tujuan" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; width: 34px; height: 34px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </form>
        </div>
    @elseif(in_array($statusLower, ['confirmed', 'dikonfirmasi']))
        <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Dikonfirmasi ✓</span>
    @elseif(in_array($statusLower, ['cancelled', 'dibatalkan']))
        <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Dibatalkan ✕</span>
    @elseif(in_array($statusLower, ['meeting', 'sedang bertemu']))
        <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Sedang Bertemu</span>
    @elseif(in_array($statusLower, ['meeting selesai']) || !empty($visit->meeting_result))
        <span style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Meeting Selesai</span>
    @elseif(in_array($statusLower, ['completed', 'selesai']))
        <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Selesai</span>
    @else
        <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Dikonfirmasi ✓</span>
    @endif
</td>

<!-- Kolom Aksi Mulai Pertemuan / Catat Hasil -->
<td style="padding: 14px; text-align: center;">
    @php $statusLower = strtolower($visit->status); @endphp
    
    @if(in_array($statusLower, ['confirmed', 'dikonfirmasi']))
        <form action="{{ route('pic.startMeeting', $visit->id) }}" method="POST" style="margin:0;">
            @csrf
            @method('PATCH')
            <button type="submit" style="background: #006B3F; color: white; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">
                Mulai Pertemuan
            </button>
        </form>
    @elseif(in_array($statusLower, ['meeting', 'sedang bertemu', 'meeting selesai']))
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalCatatPertemuan-{{ $visit->id }}" style="background: {{ $visit->meeting_result ? '#0d9488' : '#d97706' }}; color: white; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">
            {{ $visit->meeting_result ? '📝 Edit Catatan Pertemuan' : '📝 Catat Hasil Pertemuan' }}
        </button>
    @elseif(in_array($statusLower, ['completed', 'selesai']))
        <span style="color: #006B3F; font-size: 12px; font-weight: 700;">✔ Selesai (Checked Out)</span>
    @else
        <button type="button" disabled style="background: #cbd5e1; color: #64748b; border: none; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 700;">
            Mulai Pertemuan
        </button>
    @endif
</td>
                        </tr>

                        <!-- MODAL CATAT PERTEMUAN -->
                        <div class="modal fade" id="modalCatatPertemuan-{{ $visit->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                                    
                                    <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                                        <h5 class="modal-title" style="font-size: 16px; font-weight: 800; color: #172033;">
                                            📝 Catat Hasil Pertemuan & Lead
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body" style="padding: 24px;">
                                        <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                                            <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Tamu yang Ditemui:</span>
                                            <strong style="color: #172033; font-size: 14px;">{{ $visit->guest->name ?? '-' }} ({{ $visit->guest->company_name ?? '-' }})</strong>
                                        </div>

                                        <form action="{{ route('pic.completeMeeting', $visit->id) }}" method="POST">
                                            @csrf
                                            <div style="margin-bottom: 16px;">
                                                <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Catatan / Ringkasan Diskusi</label>
                                                <textarea name="meeting_result" rows="3" required placeholder="Tuliskan hasil obrolan atau permintaan khusus klien di sini..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">{{ $visit->meeting_result }}</textarea>
                                            </div>

                                            <div style="margin-bottom: 16px;">
                                                <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Status Konversi Lead</label>
                                                <select name="potential_level" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                                                    <option value="warm">Warm Lead (Perlu Follow-Up via WhatsApp)</option>
                                                    <option value="hot">Hot Lead (Prospek Tinggi / Minta Penawaran)</option>
                                                    <option value="deal">Deal / Berhasil (Resmi Order)</option>
                                                    <option value="cold">Cold / Selesai Kunjungan Biasa</option>
                                                </select>
                                            </div>

                                            <div style="margin-bottom: 20px;">
                                                <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Jadwal Follow-Up Berikutnya</label>
                                                <input type="date" name="follow_up_at" value="{{ $visit->follow_up_at ? \Carbon\Carbon::parse($visit->follow_up_at)->format('Y-m-d') : '' }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
                                            </div>

                                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                                <button type="button" data-bs-dismiss="modal" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                                    Batal
                                                </button>
                                                <button type="submit" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                                                    Simpan & Selesaikan
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- END MODAL -->

                    @empty
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #778195; font-weight: 600;">
                                Belum ada kunjungan tamu hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection