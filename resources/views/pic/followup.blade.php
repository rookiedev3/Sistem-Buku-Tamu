@extends('layouts.pic')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Bagian Header Statistik Lead -->
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <h2 style="font-size: 18px; font-weight: 800; color: #172033; margin-bottom: 6px;">Lead & Follow-Up Penjualan 📈</h2>
            <p style="font-size: 13px; color: #778195; margin: 0;">Kelola daftar prospek klien hasil kunjungan, catat status konversi, dan jadwalkan tindak lanjut.</p>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Total Prospek Aktif</span>
            <strong style="font-size: 24px; font-weight: 900; color: #172033; margin-top: 4px;">{{ $totalLeads }} Klien</strong>
        </div>

        <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: center;">
            <span style="font-size: 11px; font-weight: 700; color: #778195; text-transform: uppercase;">Berhasil (Deal)</span>
            <strong style="font-size: 24px; font-weight: 900; color: #006B3F; margin-top: 4px;">{{ $totalDeal }} Klien</strong>
        </div>
    </div>

    <!-- Tabel Manajemen Lead -->
    <div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 16px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #172033; margin: 0;">Daftar Prospek & Status Konversi</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" style="font-size: 13px; color: #172033; margin: 0;">
                <thead style="background: #f8fafc; color: #5c6678; font-weight: 700;">
                    <tr>
                        <th style="padding: 14px; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">No</th>
                        <th style="padding: 14px;">Nama Klien & Instansi</th>
                        <th style="padding: 14px;">Kontak (WhatsApp)</th>
                        <th style="padding: 14px;">Catatan / Follow-Up Terakhir</th>
                        <th style="padding: 14px;">Tgl Follow-Up</th>
                        <th style="padding: 14px;">Status Lead</th>
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
                            {{ Str::limit($note ?? 'Belum ada catatan follow-up.', 50) }}
                        </td>
                        <td style="padding: 14px; color: #006B3F; font-weight: 700;">
                            {{ $lead->follow_up_at ? \Carbon\Carbon::parse($lead->follow_up_at)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td style="padding: 14px;">
                            @if($lead->potential_level == 'warm')
                                <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Warm Lead</span>
                            @elseif($lead->potential_level == 'hot')
                                <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Hot Lead 🔥</span>
                            @elseif($lead->potential_level == 'deal')
                                <span style="background: #dcfce7; color: #15803d; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Deal / Closing 🎉</span>
                            @else
                                <span style="background: #f1f5f9; color: #64748b; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">Drop</span>
                            @endif
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus{{ $lead->id }}" style="background: #f8fafc; color: #006B3F; border: 1px solid #006B3F; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                Update Status
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL UPDATE FOLLOW UP DINAMIS -->
                    <div class="modal fade" id="modalUpdateStatus{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                                <div class="modal-header" style="border-bottom: 1px solid #e8edf5; padding: 20px 24px;">
                                    <h5 class="modal-title" style="font-size: 16px; font-weight: 800; color: #172033;">
                                        🔄 Update Status & Follow-Up
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body" style="padding: 24px;">
                                    <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                                        <span style="color: #778195; display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;">Klien Prospek:</span>
                                        <strong style="color: #172033; font-size: 14px;">{{ $lead->guest->name ?? '-' }} ({{ $lead->guest->company_name ?? '-' }})</strong>
                                        <div style="color: #475569; font-size: 12px; margin-top: 2px;">WhatsApp: {{ $lead->guest->phone ?? '-' }}</div>
                                        
                                        @if(isset($latestFollowUp) && $latestFollowUp->result)
                                        <div style="margin-top: 8px; border-top: 1px dashed #cbd5e1; padding-top: 6px;">
                                            <span style="font-size: 10px; font-weight: 700; color: #006B3F; text-transform: uppercase;">Catatan Follow-Up Sebelumnya:</span>
                                            <p style="margin: 2px 0 0 0; color: #172033; font-style: italic;">"{{ $latestFollowUp->result }}"</p>
                                        </div>
                                        @endif
                                    </div>

                                    <form action="{{ route('pic.leads.updateFollowUp', $lead->id) }}" method="POST">
                                        @csrf
                                        
                                        <!-- Status Lead Terbaru -->
                                        <div style="margin-bottom: 16px;">
                                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Status Lead Terbaru</label>
                                            <select name="status" id="statusSelect{{ $lead->id }}" class="form-select status-dropdown" data-id="{{ $lead->id }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none; background: #fff;">
                                                <option value="warm" {{ $lead->potential_level == 'warm' ? 'selected' : '' }}>Warm Lead (Perlu Follow-Up Lanjutan)</option>
                                                <option value="hot" {{ $lead->potential_level == 'hot' ? 'selected' : '' }}>Hot Lead (Prospek Tinggi / Siap Deal)</option>
                                                <option value="deal" {{ $lead->potential_level == 'deal' ? 'selected' : '' }}>Deal / Berhasil (Resmi Order)</option>
                                                <option value="drop" {{ $lead->potential_level == 'drop' ? 'selected' : '' }}>Drop / Batal</option>
                                            </select>
                                        </div>

                                        <div style="margin-bottom: 16px;">
                                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Hasil Obrolan Follow-Up Hari Ini</label>
                                            <textarea name="result" rows="3" placeholder="Tuliskan respon klien dari WA / telepon..." style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;" required></textarea>
                                        </div>

                                        <!-- Jadwal Follow-Up Berikutnya -->
                                        <div style="margin-bottom: 20px;">
                                            <label style="font-size: 12px; font-weight: 700; color: #5c6678; display: block; margin-bottom: 6px;">Jadwal Follow-Up Berikutnya</label>
                                            <input type="date" name="due_at" id="dateInput{{ $lead->id }}" value="{{ $lead->follow_up_at ? \Carbon\Carbon::parse($lead->follow_up_at)->format('Y-m-d') : '' }}" style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; color: #172033; outline: none;">
                                            <small id="dateNote{{ $lead->id }}" style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;">*Tanggal otomatis dinonaktifkan jika status Deal atau Drop.</small>
                                        </div>

                                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                            <button type="button" data-bs-dismiss="modal" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">Batal</button>
                                            <button type="submit" style="background: #006B3F; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Script untuk mengatur akses tanggal berdasarkan status dropdown -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const selects = document.querySelectorAll(".status-dropdown");

                            selects.forEach(select => {
                                const leadId = select.getAttribute("data-id");
                                const dateInput = document.getElementById("dateInput" + leadId);

                                function handleDateAccess() {
                                    if (select.value === "deal" || select.value === "drop") {
                                        dateInput.value = ""; 
                                        dateInput.disabled = true; 
                                        dateInput.style.backgroundColor = "#f1f5f9";
                                    } else {
                                        dateInput.disabled = false; 
                                        dateInput.style.backgroundColor = "#fff";
                                    }
                                }

                                handleDateAccess();
                                select.addEventListener("change", handleDateAccess);
                            });
                        });
                    </script>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Belum ada prospek lead yang perlu di-follow up.
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
@endsection