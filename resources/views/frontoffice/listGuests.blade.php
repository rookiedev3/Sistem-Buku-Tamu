@extends('layouts.frontoffice')

@php
use App\Helpers\DateHelper;
@endphp

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Daftar Master Tamu
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Kelola seluruh data direktori tamu terdaftar dan atur status prioritas (VIP).
        </p>
    </div>

    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: nowrap;">
        <button onclick="openCreateGuestModal()" style="background: #013220; color: #ffffff; border: none; padding: 10px 18px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,107,63,0.15); white-space: nowrap;">
            + Tambah Tamu Baru
        </button>

        <form action="{{ route('frontoffice.guest') }}" method="GET" id="vipFilterForm" style="display: flex; gap: 8px; align-items: center; background: #ffffff; padding: 8px 14px; border-radius: 12px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 12px; font-weight: 600; color: #64748b; white-space: nowrap;">Kategori VIP:</span>
            <select name="vip" onchange="document.getElementById('vipFilterForm').submit()" style="padding: 6px 10px; border: 1px solid #e8edf5; border-radius: 8px; font-size: 12px; outline: none; color: #172033; background: #fff;">
                <option value="">Semua Tamu</option>
                <option value="1" {{ request('vip') === '1' ? 'selected' : '' }}>Hanya VIP</option>
                <option value="0" {{ request('vip') === '0' ? 'selected' : '' }}>Reguler</option>
            </select>
            @if(request()->has('vip') && request('vip') !== null && request('vip') !== '')
            <a href="{{ route('frontoffice.guest') }}" style="font-size: 11px; color: #dc2626; text-decoration: none; font-weight: 600; margin-left: 2px; white-space: nowrap;">Reset</a>
            @endif
        </form>
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">

    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Direktori Data Tamu</h3>

        <div>
            {{-- Updated Placeholder --}}
            <input type="text" id="searchGuests" placeholder="Cari nama / instansi..." onkeyup="filterGuestTable()"
                style="padding: 8px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; outline: none; background: #ffffff; width: 260px;">
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="guestTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 850px;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 700;">No</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Profil Tamu</th>
                    <th style="padding: 14px 20px; font-weight: 700;">Instansi & Jabatan</th>
                    <th style="padding: 14px 20px; font-weight: 700;">No. WhatsApp</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Status VIP</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Total Kunjungan</th>
                    <th style="padding: 14px 20px; font-weight: 700; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse($guests as $index => $guest)
                <tr class="guest-row" style="border-bottom: 1px solid #e8edf5;">
                    {{-- Penomoran Dinamis --}}
                    <td class="row-number" style="padding: 16px 20px; font-weight: 600; color: #64748b;">
                        {{ method_exists($guests, 'firstItem') && $guests->firstItem() ? $guests->firstItem() + $index : $index + 1 }}
                    </td>

                    <td style="padding: 16px 20px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($guest->photo_path)
                            <img src="{{ asset('storage/' . $guest->photo_path) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #e8edf5;" alt="Foto">
                            @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #006B3F; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                                {{ strtoupper(substr($guest->name ?? 'G', 0, 1)) }}
                            </div>
                            @endif
                            <div>
                                <div style="font-weight: 700; color: #172033;">{{ $guest->name }}</div>
                                <div style="font-size: 11px; color: #778195;">Terdaftar: {{ DateHelper::tanggalSingkat($guest->created_at) }}</div>
                            </div>
                        </div>
                    </td>

                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 600;">{{ $guest->company_name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #778195;">{{ $guest->position ?? '-' }}</div>
                    </td>

                    <td style="padding: 16px 20px; font-weight: 600; color: #0369a1;">
                        {{ $guest->phone ?? '-' }}
                    </td>

                    <td style="padding: 16px 20px; text-align: center;">
                        <select onchange="updateVipStatus({{ $guest->id }}, this)"
                            id="vip-select-{{ $guest->id }}"
                            data-previous-value="{{ $guest->is_vip ? '1' : '0' }}"
                            style="padding: 6px 10px; border-radius: 12px; font-size: 11px; font-weight: 800; outline: none; cursor: pointer; transition: all 0.2s; {{ $guest->is_vip ? 'background: #fef3c7; color: #b45309; border: 1px solid #fcd34d;' : 'background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1;' }}">
                            <option value="0" {{ !$guest->is_vip ? 'selected' : '' }} style="background: #ffffff; color: #172033;">REGULER</option>
                            <option value="1" {{ $guest->is_vip ? 'selected' : '' }} style="background: #ffffff; color: #172033;">VIP</option>
                        </select>
                    </td>

                    <td style="padding: 16px 20px; text-align: center;">
                        <span style="background: #f1f5f9; padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 12px;">
                            {{ $guest->visits_count ?? 0 }} Kali
                        </span>
                    </td>

                    <td style="padding: 16px 20px; text-align: center;">
                        <button onclick="openGuestModal(
                            '{{ addslashes($guest->name) }}',
                            '{{ addslashes($guest->company_name ?? '-') }}',
                            '{{ addslashes($guest->position ?? '-') }}',
                            '{{ $guest->phone ?? '-' }}',
                            '{{ $guest->photo_path ? asset('storage/' . $guest->photo_path) : '' }}',
                            '{{ $guest->is_vip ? '1' : '0' }}',
                            '{{ $guest->visits_count ?? 0 }}'
                        )"
                            style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 30px; text-align: center; color: #64748b;">Belum ada data tamu terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer Pagination --}}
        <div style="padding: 14px 24px; border-top: 1px solid #e8edf5; background: #fbfcfe; display: flex; justify-content: space-between; align-items: center; color: #778195; font-size: 12px; flex-wrap: wrap; gap: 8px; margin-top: 0px;">
            <div style="margin-top: 0px; width: 100%;">
                @include('partials.pagination', ['paginator' => $guests])
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL TAMU --}}
<div id="guestModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden; box-sizing: border-box;">

        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Profil Tamu 👤</h3>
            <button onclick="closeGuestModal()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <div style="padding: 24px; font-size: 13px; color: #172033; display: flex; flex-direction: column; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 16px; background: #f8fafc; padding: 14px; border-radius: 14px; border: 1px solid #e8edf5;">
                <div id="modalPhotoContainer" style="display: flex; align-items: center; justify-content: center; flex-shrink: 0;"></div>
                <div>
                    <h4 id="modalGuestName" style="font-size: 15px; font-weight: 800; margin: 0 0 4px 0; color: #172033;">-</h4>
                    <span id="modalVipBadge" style="padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">-</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Instansi:</span>
                <span id="modalCompany" style="font-weight: 700; text-align: right; max-width: 60%;"> -</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">Jabatan:</span>
                <span id="modalPosition" style="font-weight: 600; text-align: right; max-width: 60%;"> -</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e8edf5; padding-bottom: 10px;">
                <span style="color: #778195;">No. WhatsApp:</span>
                <span id="modalPhone" style="font-weight: 600; color: #0369a1;">-</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #778195;">Frekuensi Kunjungan:</span>
                <span id="modalVisits" style="font-weight: 700; color: #006B3F;">-</span>
            </div>
        </div>

        <div style="padding: 16px 24px; background: #fbfcfe; border-top: 1px solid #e8edf5; display: flex; justify-content: flex-end;">
            <button onclick="closeGuestModal()" style="background: #172033; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TAMU BARU --}}
<div id="createGuestModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden; box-sizing: border-box; max-height: 90vh; display: flex; flex-direction: column;">

        <div style="padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <h3 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0;">Tambah Tamu Baru 👤</h3>
            <button onclick="closeCreateGuestModal()" style="background: none; border: none; font-size: 20px; font-weight: bold; color: #778195; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('frontoffice.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px; display: flex; flex-direction: column; gap: 14px; overflow-y: auto;">
            @csrf

            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="name" required placeholder="Masukkan nama tamu" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 180px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Instansi / Perusahaan</label>
                    <input type="text" name="company_name" placeholder="Nama instansi" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 180px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Jabatan</label>
                    <input type="text" name="position" placeholder="Jabatan tamu" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; box-sizing: border-box;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">No. WhatsApp / Telepon</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Status Tamu</label>
                <select name="is_vip" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; background: #fff; box-sizing: border-box;">
                    <option value="0">Reguler</option>
                    <option value="1">VIP</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Foto Profil (Opsional)</label>
                <input type="file" name="photo" accept="image/*" style="font-size: 12px; width: 100%;">
            </div>

            <div style="padding-top: 10px; display: flex; justify-content: flex-end; gap: 10px; flex-shrink: 0;">
                <button type="button" onclick="closeCreateGuestModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: #013220; color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterGuestTable() {
        const input = document.getElementById('searchGuests');
        const filter = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.guest-row');
        let visibleIndex = 1;

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            
            // Mengambil hanya teks Profil Tamu (Nama) & Instansi/Jabatan
            // cells[1] = Profil Tamu, cells[2] = Instansi & Jabatan
            const guestProfile = cells[1] ? (cells[1].textContent || cells[1].innerText) : '';
            const companyPosition = cells[2] ? (cells[2].textContent || cells[2].innerText) : '';
            
            const targetText = (guestProfile + ' ' + companyPosition).toLowerCase();
            const tdNum = row.querySelector('.row-number');

            if (targetText.indexOf(filter) > -1) {
                row.style.display = "";
                if (tdNum) {
                    tdNum.textContent = visibleIndex;
                    visibleIndex++;
                }
            } else {
                row.style.display = "none";
            }
        });
    }

    function updateVipStatus(guestId, selectElem) {
        const nextStatus = selectElem.value === '1';
        const prevValue = selectElem.getAttribute('data-previous-value');
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/frontoffice/guests/${guestId}/toggle-vip`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    is_vip: nextStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.is_vip) {
                        selectElem.style.cssText = 'padding: 6px 10px; border-radius: 12px; font-size: 11px; font-weight: 800; outline: none; cursor: pointer; transition: all 0.2s; background: #fef3c7; color: #b45309; border: 1px solid #fcd34d;';
                        selectElem.setAttribute('data-previous-value', '1');
                    } else {
                        selectElem.style.cssText = 'padding: 6px 10px; border-radius: 12px; font-size: 11px; font-weight: 800; outline: none; cursor: pointer; transition: all 0.2s; background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1;';
                        selectElem.setAttribute('data-previous-value', '0');
                    }
                } else {
                    selectElem.value = prevValue;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                selectElem.value = prevValue;
            });
    }

    function openGuestModal(name, company, position, phone, photoUrl, isVip, visitsCount) {
        document.getElementById('modalGuestName').innerText = name;
        document.getElementById('modalCompany').innerText = company;
        document.getElementById('modalPosition').innerText = position;
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalVisits').innerText = visitsCount + ' Kali Kunjungan';

        const vipBadge = document.getElementById('modalVipBadge');
        if (isVip === '1') {
            vipBadge.innerText = '⭐ VIP Member';
            vipBadge.style.cssText = 'background: #fef3c7; color: #b45309; border: 1px solid #fcd34d; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;';
        } else {
            vipBadge.innerText = 'Reguler';
            vipBadge.style.cssText = 'background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;';
        }

        const photoContainer = document.getElementById('modalPhotoContainer');
        if (photoUrl) {
            photoContainer.innerHTML = `<img src="${photoUrl}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" alt="Foto">`;
        } else {
            const initial = name ? name.charAt(0).toUpperCase() : 'G';
            photoContainer.innerHTML = `<div style="width: 50px; height: 50px; border-radius: 50%; background: #006B3F; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px;">${initial}</div>`;
        }

        document.getElementById('guestModal').style.display = 'flex';
    }

    function closeGuestModal() {
        document.getElementById('guestModal').style.display = 'none';
    }

    function openCreateGuestModal() {
        document.getElementById('createGuestModal').style.display = 'flex';
    }

    function closeCreateGuestModal() {
        document.getElementById('createGuestModal').style.display = 'none';
    }
</script>

@endsection