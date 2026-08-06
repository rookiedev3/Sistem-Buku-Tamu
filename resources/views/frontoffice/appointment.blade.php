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
            Kelola Janji Temu (Appointment) 📅
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
        <table id="appTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">Kode / Jadwal Temu</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tamu & Instansi</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Keperluan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Tujuan PIC</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Status</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi Konfirmasi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">

                @forelse($appointments as $apt)
                <tr id="row-apt-{{ $apt->id }}" style="border-bottom: 1px solid #e8edf5;">
                    <td style="padding: 16px 20px;">
                        <span style="font-weight: 800; color: #006B3F; display: block;">{{ $apt->visit_code }}</span>
                        <span style="font-size: 11px; color: #778195;">{{ $apt->scheduled_at ? $apt->scheduled_at->format('d M Y, H:i') : '-' }} WIB</span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700;">{{ optional($apt->guest)->name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #778195;">
                            {{ optional($apt->guest)->company_name ?? '-' }}
                            @if(optional($apt->guest)->phone)
                            <br><span style="color:#94a3b8; font-size:11px;">WA: {{ optional($apt->guest)->phone }}</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">{{ optional($apt->purpose)->name ?? '-' }}</td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;">
                            {{ optional($apt->assignedUser)->name ?? '-' }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px;" class="status-cell">
                        @if(in_array($apt->status, ['waiting', 'Menunggu']))
                        <span class="badge-status" style="background: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Menunggu Konfirmasi
                        </span>
                        @elseif(in_array($apt->status, ['confirmed', 'Disetujui']))
                        <span class="badge-status" style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Disetujui
                        </span>
                        @elseif(in_array($apt->status, ['cancelled', 'Ditolak']))
                        <span class="badge-status" style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Ditolak
                        </span>
                        @else
                        <span class="badge-status" style="background: #e2e8f0; color: #475569; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            {{ $apt->status }}
                        </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: center;" class="action-cell">
                        @if(in_array($apt->status, ['waiting', 'Menunggu']))
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <button onclick="updateStatus({{ $apt->id }}, 'confirmed')" style="background: #006B3F; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Setujui
                            </button>
                            <button onclick="updateStatus({{ $apt->id }}, 'cancelled')" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                Tolak
                            </button>
                        </div>
                        @elseif(in_array($apt->status, ['confirmed', 'Disetujui']))
                        <span style="font-size: 11px; color: #15803d; font-weight: 600;">Disetujui</span>
                        @elseif(in_array($apt->status, ['cancelled', 'Ditolak']))
                        <span style="font-size: 11px; color: #b91c1c; font-weight: 600;">Ditolak</span>
                        @else
                        <span style="font-size: 11px; color: #64748b; font-weight: 600;">Terkonfirmasi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 24px; text-align: center; color: #778195;">
                        Tidak ada data janji temu.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px;">
        <span>Menampilkan jadwal janji temu aktif</span>
        <span>Total: {{ $appointments->count() }} Janji Temu</span>
    </div>

</div>


<div id="appointmentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 500px; max-width: 90%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">

        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Form Buat Janji Temu 📅</h3>
            <button onclick="closeAppointmentModal()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('frontoffice.appointment.store') }}" method="POST">
            @csrf
            <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 12px; max-height: 70vh; overflow-y: auto;">
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nama Lengkap Tamu *</label>
                    <input type="text" name="name" required placeholder="Masukkan nama tamu" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Asal Instansi / Perusahaan *</label>
                    <input type="text" name="company_name" required placeholder="Masukkan nama perusahaan" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Nomor Telepon / WhatsApp *</label>
                    <input type="tel" name="phone" required placeholder="Contoh: 08123456789" style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Rencana Tanggal & Waktu *</label>
                    <input type="datetime-local" name="scheduled_at" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Keperluan Kunjungan *</label>
                    <select name="purpose_id" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        @foreach($purposes as $purp)
                        <option value="{{ $purp->id }}">{{ $purp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih PIC Tujuan *</label>
                    <select name="assigned_to" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        @foreach($pics as $pic)
                        <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; color: #172033; display: block; margin-bottom: 4px;">Pilih Cabang *</label>
                    <select name="branch_id" required style="width: 100%; padding: 10px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; box-sizing: border-box; background: #fff;">
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeAppointmentModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer;">Simpan Jadwal</button>
            </div>
        </form>

    </div>
</div>

<script>
    // Fungsi Pencarian Tabel
    function filterAppTable() {
        const input = document.getElementById('searchApp');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('appTable');
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

    // Fungsi Modal Tambah Janji Temu
    function openAppointmentModal() {
        document.getElementById('appointmentModal').style.display = 'flex';
    }

    // Reset dan Tutup Modal
    function closeAppointmentModal() {
        document.getElementById('appointmentModal').style.display = 'none';
    }

    // Fungsi Interaktif Setuju / Tolak
    function updateStatus(id, action) {
        const row = document.getElementById('row-apt-' + id);
        const statusCell = row.querySelector('.status-cell');
        const actionCell = row.querySelector('.action-cell');

        fetch(`/frontoffice/appointment/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: action
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (action === 'confirmed') {
                        statusCell.innerHTML = `
                        <span class="badge-status" style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Disetujui
                        </span>
                    `;
                        actionCell.innerHTML = `<span style="font-size: 11px; color: #15803d; font-weight: 600;">Disetujui</span>`;
                    } else if (action === 'cancelled') {
                        statusCell.innerHTML = `
                        <span class="badge-status" style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                            Ditolak
                        </span>
                    `;
                        actionCell.innerHTML = `<span style="font-size: 11px; color: #b91c1c; font-weight: 600;">Ditolak</span>`;
                    }
                } else {
                    alert('Gagal memperbarui status.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            });
    }
</script>

@endsection