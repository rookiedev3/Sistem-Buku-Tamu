@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header Statistik Lead -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Data Leads & Klien Deal 🎉</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar prospek klien dan pantau daftar perusahaan yang sudah berhasil closing.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Prospek Aktif</span>
            <strong style="font-size: 24px; font-weight: 900; color: #172033; margin-top: 4px;">{{ $totalLeads ?? 0 }} Klien</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Berhasil (Deal)</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">{{ $totalDeal ?? 0 }} Klien</strong>
        </div>
    </div>

    <!-- Tabel Daftar Leads / Deal -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Klien Konversi & Deal</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">Kontak (WhatsApp)</th>
                        <th style="padding: 14px;">Catatan Pertemuan / Hasil</th>
                        <th style="padding: 14px;">Status Prospek</th>
                        <th style="padding: 14px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $index => $lead)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px; font-weight: 600;">{{ $leads->firstItem() + $index }}</td>
                        <td style="padding: 14px;">
                            <strong style="display: block; color: #172033; font-weight: 800;">{{ $lead->guest->name ?? '-' }}</strong>
                            <span style="font-size: 11px; color: #778195;">{{ $lead->guest->company_name ?? '-' }}</span>
                        </td>
                        <td style="padding: 14px; color: #475569; font-weight: 600;">
                            {{ $lead->guest->phone ?? '-' }}
                        </td>
                        <td style="padding: 14px; color: #475569;">
                            @php
                                $latestFollowUp = $lead->followUps->first();
                                $note = $latestFollowUp->result ?? $lead->meeting_result;
                            @endphp
                            {{ Str::limit($note ?? 'Belum ada catatan.', 50) }}
                        </td>
                        <td style="padding: 14px;">
                            @if($lead->potential_level == 'warm')
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Warm Lead</span>
                            @elseif($lead->potential_level == 'hot')
                                <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Hot Lead 🔥</span>
                            @elseif($lead->potential_level == 'deal')
                                <span style="background: #dcfce7; color: #15803d; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Deal</span>
                            @else
                                <span style="background: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">{{ ucfirst($lead->potential_level ?? 'New') }}</span>
                            @endif
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <!-- Tombol Modal Catatan -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#noteModal{{ $lead->id }}" style="background: transparent; color: #006B3F; border: 1px solid #006B3F; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                📝 Lihat Catatan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Belum ada data klien yang masuk dalam daftar leads atau deal.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $leads->links() }}
        </div>
    </div>

</div>

<!-- ============================================== -->
<!-- KUMPULAN MODAL CATATAN DI LUAR TABEL           -->
<!-- ============================================== -->
@foreach($leads as $lead)
    @php
        $latestFollowUp = $lead->followUps->first();
        $note = $latestFollowUp->result ?? $lead->meeting_result;
        $followUpDate = $latestFollowUp->follow_up_at ?? $lead->follow_up_at;
    @endphp
    <div class="modal fade" id="noteModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
                    <h5 class="modal-title" style="font-size: 15px; font-weight: 800; color: #172033;">
                        Hasil Pertemuan - {{ $lead->guest->name ?? 'Klien' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; color: #334155; font-size: 13px; line-height: 1.6;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-bottom: 16px;">
                        <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Status Prospek Terakhir:</div>
                        <div style="font-weight: 800; color: #172033; text-transform: capitalize; margin-bottom: 8px;">
                            {{ $lead->potential_level ?? 'Belum ada status' }}
                        </div>

                        <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Jadwal Follow-Up:</div>
                        <div style="font-weight: 700; color: #006B3F;">
                            {{ $followUpDate ? \Carbon\Carbon::parse($followUpDate)->translatedFormat('d F Y') : 'Tidak ada jadwal / Sudah Deal' }}
                        </div>
                    </div>

                    <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">
                        Rangkuman / Catatan Diskusi:
                    </label>
                    <div style="white-space: pre-line; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; min-height: 100px;">
                        {{ $note ?? 'Tidak ada catatan yang ditinggalkan.' }}
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 12px 24px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection