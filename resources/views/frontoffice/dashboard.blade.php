@extends('layouts.frontoffice')

@section('content')

<!-- Styling Scrollbar Kustom Ramping -->
<style>
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
        margin: 16px 0;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #10b981; color: #15803d; padding: 12px 20px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; background: #006B3F; color: #fff; padding: 6px 14px; border-radius: 20px;">
            FRONT OFFICE SYSTEM
        </span>
        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 10px 0 4px 0;">
            Dashboard Front Office
        </h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">
            Kelola antrian tamu aktif, ubah status kunjungan, dan input data tamu manual (walk-in).
        </p>
    </div>

    <div style="display: flex; gap: 12px;">
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Total Tamu Hari Ini</span>
            <span style="font-size: 18px; font-weight: 800; color: #006B3F;">{{ $totalToday }} Orang</span>
        </div>
        <div style="background: #ffffff; padding: 12px 20px; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 4px 12px rgba(31,53,97,0.03);">
            <span style="font-size: 11px; color: #778195; font-weight: 600; display: block;">Sedang Menunggu</span>
            <span style="font-size: 18px; font-weight: 800; color: #f59e0b;">{{ $waitingToday }} Orang</span>
        </div>
    </div>
</div>

<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e8edf5; box-shadow: 0 10px 30px rgba(31,53,97,0.03); overflow: hidden;">

    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe;">
        <h3 style="font-size: 15px; font-weight: 700; color: #172033; margin: 0;">Daftar Kunjungan & Antrian Tamu</h3>

        <div style="display: flex; gap: 10px;">
            <input type="text" id="searchGuest" placeholder="Cari nama tamu / instansi..." onkeyup="filterTable()"
                style="padding: 9px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #ffffff; width: 240px; transition: all 0.2s ease;">

            <button onclick="openManualModal()" style="background: #006B3F; color: #fff; border: none; padding: 9px 18px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,107,63,0.15);">
                + Input Tamu Manual
            </button>
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
                            {{-- 1. Status TERJADWAL / SCHEDULED --}}
                            @if(in_array(strtolower($visit->status), ['terjadwal', 'scheduled']))
                            <form action="{{ route('frontoffice.checkin', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #006B3F; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Check-in
                                </button>
                            </form>

                            <form id="cancel-form-{{ $visit->id }}" action="{{ route('frontoffice.cancel', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="button" onclick="confirmCancel('{{ $visit->id }}', '{{ addslashes($visit->guest->name ?? 'Tamu') }}')" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Batalkan
                                </button>
                            </form>

                            {{-- 2. Status MENUNGGU / WAITING --}}
                            @elseif(in_array(strtolower($visit->status), ['menunggu', 'waiting']))
                            <span style="font-size: 11px; color: #d97706; font-weight: 600;">Menunggu</span>

                            {{-- 3. Status SIAP CHECK-OUT (Sedang Bertemu / Meeting Selesai) --}}
                            @elseif(in_array(strtolower($visit->status), ['sedang bertemu', 'confirmed', 'meeting selesai', 'meeting_selesai', 'dikonfirmasi']))
                            <form action="{{ route('frontoffice.checkout', $visit->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    Check-out
                                </button>
                            </form>

                            {{-- 4. Status DIBATALKAN --}}
                            @elseif(in_array(strtolower($visit->status), ['dibatalkan', 'cancelled']))
                            <span style="font-size: 11px; color: #dc2626; font-weight: 600;">Dibatalkan</span>

                            {{-- 5. Status DEFAULT / SELESAI --}}
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

<!-- MODAL INPUT TAMU MANUAL 3-STEP -->
<div id="manualModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 999;">

    <!-- Outer Card dengan Overflow Hidden agar Scrollbar Rapi -->
    <div style="background: #ffffff; width: 100%; max-width: 520px; max-height: 90vh; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); box-sizing: border-box; overflow: hidden; display: flex; flex-direction: column;">

        <!-- Header & Progress Stepper -->
        <div style="padding: 24px 32px 16px 32px; border-bottom: 1px solid #e8edf5; background: #fbfcfe;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0;">Input Tamu Manual</h3>
                <span id="stepIndicatorText" style="font-size: 11px; font-weight: 700; color: #006B3F; background: #e6f4ed; padding: 4px 10px; border-radius: 20px;">Langkah 1 dari 3</span>
            </div>

            <!-- Visual Stepper Progress Bar -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <div id="bar-step-1" style="flex: 1; height: 6px; background: #006B3F; border-radius: 10px; transition: all 0.3s ease;"></div>
                <div id="bar-step-2" style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 10px; transition: all 0.3s ease;"></div>
                <div id="bar-step-3" style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 10px; transition: all 0.3s ease;"></div>
            </div>
        </div>

        <!-- Inner Scroll Form Area -->
        <div class="custom-scroll" style="padding: 28px 32px; overflow-y: auto; max-height: 70vh; box-sizing: border-box;">
            <form id="multiStepForm" action="{{ route('frontoffice.storeManual') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf

                <!-- ================= STEP 1: IDENTITAS TAMU ================= -->
                <div id="step-1-content">
                    <!-- Nama Lengkap -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Nama Lengkap <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="input_name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <!-- Asal Instansi / Perusahaan -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Asal Instansi / Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="input_company" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT / Universitas / Pribadi" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <!-- Alamat Instansi -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Alamat Instansi / Perusahaan
                        </label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Contoh: Jl. Sudirman No. 123, Jakarta"
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <!-- Jabatan -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Jabatan di Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="position" value="{{ old('position') }}" placeholder="Contoh: Staff, Manager, Direktur" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Nomor WhatsApp (Aktif) <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" pattern="^(\+62|62|0)8[1-9][0-9]{7,11}$" placeholder="Contoh: 081234567890" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Email <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: nama@email.com" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                    </div>

                    <!-- Kategori Pengunjung -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Kategori Pengunjung <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="guest_category_id" required
                            style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13.5px; outline: none; background: #ffffff; color: #172033; cursor: pointer; box-sizing: border-box; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($guestCategories as $categories)
                            <option value="{{ $categories->id }}">
                                {{ $categories->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Foto Tamu -->
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #172033; margin-bottom: 6px;">
                            Foto Tamu <span style="font-weight: 500; color: #778195;">(Opsional)</span>
                        </label>
                        <input type="file" id="photoInput" name="photo_path" accept="image/*" onchange="validateFileSize(this)"
                            style="width: 100%; padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; outline: none; background: #ffffff; color: #172033; box-sizing: border-box; cursor: pointer; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#006B3F'" onblur="this.style.borderColor='#e8edf5'">

                        <span style="font-size: 11px; color: #778195; display: block; margin-top: 6px;">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                        <small id="fileError" style="color: #dc2626; display: none; margin-top: 4px; font-size: 12px;"></small>
                    </div>
                </div>

                <!-- ================= STEP 2: DETAIL TUJUAN ================= -->
                <div id="step-2-content" style="display: none;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 14px 0;">
                        2. Tujuan & Keperluan Kunjungan
                    </h4>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih PIC Tujuan *</label>
                        <select id="select_pic" name="assigned_to" required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="" disabled selected>-- Pilih PIC --</option>
                            @foreach($pics as $pic)
                            <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih Cabang *</label>
                        <select name="branch_id" id="select_branch" required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="" disabled selected>-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih Keperluan Kunjungan *</label>
                        <select name="purpose_id" id="select_purpose" required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="" disabled selected>-- Pilih Keperluan --</option>
                            @foreach($purposes as $purpose)
                            <option value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Pilih Produk / Layanan yang Diminati</label>
                        <select name="product_interest" id="select_product" style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="">-- Pilih Produk / Layanan --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->code }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Tanggal & Jam Kunjungan *</label>
                        <input type="text" id="input_scheduled_at" name="scheduled_at" readonly required style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none; cursor: pointer;" onchange="updateSummary()">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Sumber Mengetahui IT Solution</label>
                        <select name="source_id" id="select_source" style="width: 100%; padding: 11px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none;" onchange="updateSummary()">
                            <option value="">-- Pilih Sumber Informasi --</option>
                            @foreach($leadSources as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12px; font-weight: 700; color: #172033; display: block; margin-bottom: 6px;">Detail Keperluan Kunjungan *</label>
                        <textarea name="notes" id="input_notes" rows="2" placeholder="Tuliskan ringkasan keperluan..." required style="width: 100%; padding: 10px 16px; border: 1px solid #e8edf5; border-radius: 12px; font-size: 13px; box-sizing: border-box; background: #fff; outline: none; resize: vertical;" onkeyup="updateSummary()"></textarea>
                    </div>
                </div>

                <!-- ================= STEP 3: JADWAL & KONFIRMASI ================= -->
                <div id="step-3-content" style="display: none;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 14px 0;">
                        3. Konfirmasi Data Check-In
                    </h4>

                    <!-- Box Ringkasan -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; margin-bottom: 12px; max-height: 280px; overflow-y: auto;">

                        <!-- Penampung Pratinjau Foto Tamu di Step 3 -->
                        <div id="sum_photo_container" style="display: none; justify-content: center; margin-bottom: 16px;">
                            <div style="text-align: center;">
                                <img id="sum_photo_preview" src="" alt="Pratinjau Foto Tamu" style="width: 80px; height: 80px; object-fit: cover; border-radius: 14px; border: 2px solid #006B3F; box-shadow: 0 4px 12px rgba(0,107,63,0.15);">
                                <span style="display: block; font-size: 11px; color: #778195; margin-top: 4px; font-weight: 700;">Foto Tamu</span>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Nama Tamu:</span>
                                <strong id="sum_name" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Instansi:</span>
                                <strong id="sum_company" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Tujuan PIC:</span>
                                <strong id="sum_pic" style="color: #006B3F;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Cabang:</span>
                                <strong id="sum_branch" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Jenis Kunjungan:</span>
                                <strong id="sum_purpose" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Produk Minat:</span>
                                <strong id="sum_product" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Sumber Info:</span>
                                <strong id="sum_source" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Jadwal:</span>
                                <strong id="sum_schedule" style="color: #172033;">-</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b; font-weight: 600;">Keperluan:</span>
                                <strong id="sum_notes" style="color: #172033; text-align: right; max-width: 200px; word-break: break-all;">-</strong>
                            </div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                        <input type="checkbox" name="privacy_consent" id="manual_privacy_consent" value="1" required
                            style="width: 18px; height: 18px; accent-color: #006B3F; cursor: pointer; margin-top: 2px;">
                        <label for="manual_privacy_consent" style="font-size: 12px; color: #475569; line-height: 1.5; cursor: pointer;">
                            Saya menyetujui penggunaan data ini untuk keperluan pencatatan kunjungan dan tindak lanjut layanan IT Solution.
                        </label>
                    </div>

                    <p style="font-size: 12px; color: #94a3b8; margin: 0; text-align: center;">
                        Pastikan seluruh data di atas sudah benar sebelum menyimpan antrian.
                    </p>
                </div>
            </form>
        </div>

        <!-- Action Footer Controls -->
        <div style="padding: 16px 32px 24px 32px; border-top: 1px solid #e8edf5; background: #ffffff; display: flex; gap: 10px;">
            <button type="button" id="btnBatalModal" onclick="closeManualModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>

            <button type="button" id="btnPrevStep" onclick="changeStep(-1)" style="flex: 1; display: none; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                ← Kembali
            </button>

            <button type="button" id="btnNextStep" onclick="changeStep(1)" style="flex: 2; background: #006B3F; color: #fff; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
                Lanjut →
            </button>

            <button type="button" id="btnSubmitForm" onclick="submitMultiStepForm()" style="flex: 2; display: none; background: #006B3F; color: #fff; border: none; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,107,63,0.2);">
                Simpan & Buat Antrian ✓
            </button>
        </div>

    </div>
</div>

<!-- MODAL KONFIRMASI PEMBATALAN CUSTOM -->
<div id="cancelConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; padding: 28px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); text-align: center; box-sizing: border-box;">

        <div style="width: 52px; height: 52px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #dc2626; font-size: 22px;">
            ⚠️
        </div>

        <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0 0 8px 0;">Batalkan Kunjungan?</h3>
        <p style="font-size: 13px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">
            Apakah Anda yakin ingin membatalkan jadwal kunjungan untuk <strong id="cancelGuestName" style="color: #172033;">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="closeCancelModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 11px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>
            <button type="button" id="confirmCancelSubmitBtn" style="flex: 1; background: #dc2626; color: #ffffff; border: none; padding: 11px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>

<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
    let activeCancelVisitId = null;
    let currentStep = 1;

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#input_scheduled_at", {
            locale: "id",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            minTime: "08:00",
            maxTime: "17:00",
            minuteIncrement: 15,
            disableMobile: "true",
            defaultDate: new Date(),
            disable: [
                function(date) {
                    return (date.getDay() === 0);
                }
            ],
            onChange: function() {
                updateSummary();
            }
        });
    });

    function validateFileSize(input) {
        const file = input.files[0];
        const errorElement = document.getElementById('fileError');
        const maxSizeBytes = 2 * 1024 * 1024; // 2 MB
        const previewImg = document.getElementById('sum_photo_preview');
        const previewContainer = document.getElementById('sum_photo_container');

        if (file) {
            if (file.size > maxSizeBytes) {
                errorElement.textContent = 'Ukuran file terlalu besar! Maksimal 2 MB.';
                errorElement.style.display = 'block';
                input.value = ''; // Reset input file
                if (previewImg) previewImg.src = '';
                if (previewContainer) previewContainer.style.display = 'none';
            } else {
                errorElement.style.display = 'none';

                // Gunakan FileReader untuk membaca dan memuat foto ke penampung di Step 3
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        } else {
            errorElement.style.display = 'none';
            if (previewImg) previewImg.src = '';
            if (previewContainer) previewContainer.style.display = 'none';
        }
    }

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
        currentStep = 1;
        updateStepUI();
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

    function changeStep(direction) {
        // Validasi input wajib sebelum pindah ke step selanjutnya
        if (direction === 1 && !validateCurrentStep(currentStep)) {
            return;
        }

        currentStep += direction;

        if (currentStep < 1) currentStep = 1;
        if (currentStep > 3) currentStep = 3;

        updateStepUI();
    }

    function validateCurrentStep(step) {
        const currentContainer = document.getElementById(`step-${step}-content`);
        const inputs = currentContainer.querySelectorAll('input[required], select[required], textarea[required]');

        for (let input of inputs) {
            if (!input.value.trim()) {
                input.focus();
                input.style.borderColor = '#ef4444'; // Tandai border merah
                return false;
            } else {
                input.style.borderColor = '#e8edf5';
            }
        }
        return true;
    }

    function updateStepUI() {
        // Sembunyikan semua konten step
        document.getElementById('step-1-content').style.display = 'none';
        document.getElementById('step-2-content').style.display = 'none';
        document.getElementById('step-3-content').style.display = 'none';

        // Tampilkan step aktif
        document.getElementById(`step-${currentStep}-content`).style.display = 'block';

        // Perbarui teks indikator & bar progress
        document.getElementById('stepIndicatorText').innerText = `Langkah ${currentStep} dari 3`;

        document.getElementById('bar-step-1').style.background = currentStep >= 1 ? '#006B3F' : '#e2e8f0';
        document.getElementById('bar-step-2').style.background = currentStep >= 2 ? '#006B3F' : '#e2e8f0';
        document.getElementById('bar-step-3').style.background = currentStep >= 3 ? '#006B3F' : '#e2e8f0';

        // Perbarui visibilitas tombol navigasi
        document.getElementById('btnBatalModal').style.display = currentStep === 1 ? 'block' : 'none';
        document.getElementById('btnPrevStep').style.display = currentStep > 1 ? 'block' : 'none';
        document.getElementById('btnNextStep').style.display = currentStep < 3 ? 'block' : 'none';
        document.getElementById('btnSubmitForm').style.display = currentStep === 3 ? 'block' : 'none';

        // Perbarui teks ringkasan jika menuju ke Step 3
        if (currentStep === 3) {
            updateSummary();
        }
    }

    function updateSummary() {
        document.getElementById('sum_name').innerText = document.getElementById('input_name').value || '-';
        document.getElementById('sum_company').innerText = document.getElementById('input_company').value || '-';

        const picSelect = document.getElementById('select_pic');
        document.getElementById('sum_pic').innerText = picSelect.options[picSelect.selectedIndex]?.text || '-';

        const branchSelect = document.getElementById('select_branch');
        document.getElementById('sum_branch').innerText = branchSelect.options[branchSelect.selectedIndex]?.text || '-';

        const purposeSelect = document.getElementById('select_purpose');
        document.getElementById('sum_purpose').innerText = purposeSelect.options[purposeSelect.selectedIndex]?.text || '-';

        const productSelect = document.getElementById('select_product');
        document.getElementById('sum_product').innerText = productSelect.options[productSelect.selectedIndex]?.text || '-';

        const sourceSelect = document.getElementById('select_source');
        document.getElementById('sum_source').innerText = sourceSelect.options[sourceSelect.selectedIndex]?.text || '-';

        document.getElementById('sum_schedule').innerText = document.getElementById('input_scheduled_at').value || '-';
        document.getElementById('sum_notes').innerText = document.getElementById('input_notes').value || '-';
    }

    function submitMultiStepForm() {
        const consent = document.getElementById('manual_privacy_consent');
        if (!consent.checked) {
            consent.focus();
            alert('Anda harus menyetujui penggunaan data untuk melanjutkan.');
            return;
        }
        document.getElementById('multiStepForm').submit();
    }
</script>

@endsection