@extends('layouts.frontoffice')

@section('content')

@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 20px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            FRONT OFFICE SYSTEM
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Kelola Janji Temu (Appointment)
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Daftar jadwal kunjungan terjadwal yang diajukan oleh tamu sebelum datang ke kantor.
        </p>
    </div>

    <button onclick="openAppointmentModal()" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
        + Buat Janji Temu
    </button>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">

    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Daftar Jadwal Tamu Terjadwal</h3>

        <div>
            <input type="text" id="searchApp" placeholder="Cari nama tamu / PIC..." onkeyup="filterAppTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 240px;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="guestTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">Token / Waktu</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Jenis Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($visits as $visit)
                <tr style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">{{ $visit->visit_code ?? ('ANT-' . sprintf('%03d', $visit->queue_number)) }}</span>
                        <span style="font-size: 11px; color: #778195;">{{ $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->format('H:i') . ' WIB' : '-' }}</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">{{ $visit->guest->name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $visit->guest->company_name ?? '-' }} ({{ $visit->guest->position ?? '-' }})</div>
                    </td>
                    <td style="padding: 16px 20px;">{{ $visit->purpose->name ?? '-' }}</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            {{ $visit->assignedUser->name ?? '-' }}
                        </span>
                    </td>

                    {{-- TABEL STATUS --}}
                    <td style="padding: 16px 20px;">
                        @if(in_array($visit->status, ['Terjadwal', 'scheduled']))
                        <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Terjadwal
                        </span>
                        @elseif(in_array($visit->status, ['Menunggu', 'waiting']))
                        <span style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Menunggu
                        </span>
                        @elseif(in_array($visit->status, ['Sedang Bertemu', 'confirmed']))
                        <span style="background: #f1eaff; color: #6741b5; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Sedang Bertemu
                        </span>
                        @elseif(in_array($visit->status, ['Selesai', 'completed']))
                        <span style="background: #e6f7ee; color: #137a48; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Selesai
                        </span>
                        @elseif(in_array($visit->status, ['Dibatalkan', 'cancelled']))
                        <span style="background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Dibatalkan
                        </span>
                        @else
                        <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            {{ $visit->status }}
                        </span>
                        @endif
                    </td>

                    {{-- TABEL AKSI --}}
                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                            @if(in_array($visit->status, ['Terjadwal', 'scheduled']))
                            <form action="{{ route('frontoffice.checkin', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Check-in
                                </button>
                            </form>

                            <!-- Form Pembatalan (Dipemicu Modal Custom) -->
                            <form id="cancel-form-{{ $visit->id }}" action="{{ route('frontoffice.cancel', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="button" onclick="confirmCancel('{{ $visit->id }}', '{{ addslashes($visit->guest->name ?? 'Tamu') }}')" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Batalkan
                                </button>
                            </form>

                            @elseif(in_array($visit->status, ['Menunggu', 'waiting']))
                            <span style="font-size: 11px; color: #d97706; font-weight: 600;">Menunggu</span>

                            @elseif(in_array($visit->status, ['Sedang Bertemu', 'confirmed', 'meeting selesai']))
                            <form action="{{ route('frontoffice.checkout', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Check-out
                                </button>
                            </form>

                            @elseif(in_array($visit->status, ['Dibatalkan', 'cancelled']))
                            <span style="font-size: 11px; color: #dc2626; font-weight: 600;">Dibatalkan</span>

                            @else
                            <span style="font-size: 11px; color: #64748b; font-weight: 600;">Selesai</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b;">Belum ada antrian kunjungan hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan data kunjungan hari ini</span>
        <span>Total: {{ $totalToday }}</span>
    </div>

</div>

<!-- Modal Input Tamu Manual -->
<div id="manualModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 999;">
    <div style="background: #ffffff; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; padding: 30px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); box-sizing: border-box;">
        <h3 style="font-size: 18px; font-weight: 800; color: #172033; margin-top: 0; margin-bottom: 16px;">Form Input Tamu Manual (Front Office)</h3>

        <form action="{{ route('frontoffice.storeManual') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
            @csrf
            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Masukkan nama tamu" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Asal Instansi / Perusahaan *</label>
                <input type="text" name="company_name" required placeholder="Masukkan instansi" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Jabatan di Perusahaan *</label>
                <input type="text" name="position" required placeholder="Contoh: Staff, Manager, Direktur" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nomor WhatsApp (Aktif) *</label>
                <input type="tel" name="phone" required placeholder="Contoh: 081234567890" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih PIC Tujuan *</label>
                <select name="assigned_to" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;">
                    @foreach($pics as $pic)
                    <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih Cabang *</label>
                <select name="branch_id" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;">
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih Keperluan Kunjungan *</label>
                <select name="purpose_id" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;">
                    @foreach($purposes as $purpose)
                    <option value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="closeManualModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="flex: 2; background: #006B3F; color: #fff; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">Simpan & Buat Antrian</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI PEMBATALAN CUSTOM -->
<div id="cancelConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; padding: 28px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); text-align: center; box-sizing: border-box;">

        <div style="width: 52px; height: 52px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #dc2626; font-size: 22px;">
            ⚠️
        </div>

        <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0 0 8px 0;">Batalkan Kunjungan?</h3>
        <p style="font-size: 13px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">
            Apakah Anda yakin ingin membatalkan jadwal kunjungan untuk <strong id="cancelGuestName" style="color: #172033;">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="closeCancelModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>
            <button type="button" id="confirmCancelSubmitBtn" style="flex: 1; background: #dc2626; color: #ffffff; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>

<script>
    let activeCancelVisitId = null;

    function confirmCancel(visitId, guestName) {
        activeCancelVisitId = visitId;
        document.getElementById('cancelGuestName').innerText = guestName;
        document.getElementById('cancelConfirmModal').style.display = 'flex';
    }

    function closeCancelModal() {
        activeCancelVisitId = null;
        document.getElementById('cancelConfirmModal').style.display = 'none';
    }

    document.getElementById('confirmCancelSubmitBtn').addEventListener('click', function() {
        if (activeCancelVisitId) {
            document.getElementById('cancel-form-' + activeCancelVisitId).submit();
        }
    });

    function openManualModal() {
        document.getElementById('manualModal').style.display = 'flex';
    }

    function closeManualModal() {
        document.getElementById('manualModal').style.display = 'none';
    }

    function filterTable() {
        const input = document.getElementById('searchGuest');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('guestTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let tdName = tr[i].getElementsByTagName('td')[1];
            if (tdName) {
                let txtValue = tdName.textContent || tdName.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

@endsection