@extends('layouts.frontoffice') {{-- Mengikuti layout frontoffice yang konsisten --}}

@section('content')
<div class="container my-4" style="padding: 0 16px; box-sizing: border-box;">
    <div class="card shadow-sm mx-auto" style="width: 100%; max-width: 900px; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.08) !important; overflow: hidden; box-sizing: border-box;">
        
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: #013220; padding: 22px 32px; border-bottom: none; box-sizing: border-box;">
            <h5 class="mb-0" style="font-weight: 800; font-size: 17px;">Tambah User Baru</h5>
            <a href="{{ route('user.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; font-weight: 700; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); padding: 5px 14px; font-size: 12px; text-decoration: none; white-space: nowrap;">Kembali</a>
        </div>

        <div class="card-body" style="padding: 36px; box-sizing: border-box;">
            
            {{-- Pesan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px; font-size: 13px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.store') }}" method="POST" id="formTambahUser" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required minlength="3" maxlength="100" placeholder="Masukkan nama" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                    <div class="invalid-feedback-custom" style="display:none; color:#dc2626; font-size:11.5px; font-weight:700; margin-top:4px;">Nama minimal 3 karakter.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="contoh@gmail.com" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                    <div class="invalid-feedback-custom" style="display:none; color:#dc2626; font-size:11.5px; font-weight:700; margin-top:4px;">Format email tidak valid. Contoh: nama@domain.com</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Nomor Telepon/HP <span class="text-danger">*</span></label>
                    {{-- DIUBAH: nomor HP sekarang wajib diawali "+62" atau "08", disamakan
                         dengan validasi di UserApiController & app Flutter. --}}
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required inputmode="tel" pattern="^(\+62|08)[0-9]{7,13}$" placeholder="Contoh: 08123456789 atau +628123456789" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                    <small style="color: #778195; font-size: 11.5px; margin-top: 4px; display: block;">Harus diawali +62 atau 08.</small>
                    <div class="invalid-feedback-custom" style="display:none; color:#dc2626; font-size:11.5px; font-weight:700; margin-top:4px;">No. HP harus diawali +62 atau 08, diikuti 7–13 digit.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                    <div class="invalid-feedback-custom" style="display:none; color:#dc2626; font-size:11.5px; font-weight:700; margin-top:4px;">Password minimal 6 karakter.</div>
                </div>

                <div class="row" style="margin-left: -8px; margin-right: -8px;">
                    <div class="col-md-6 mb-3" style="padding-left: 8px; padding-right: 8px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                            <option value="">-- Pilih Role --</option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pic" {{ old('role') == 'pic' ? 'selected' : '' }}>PIC / Sales</option>
                            <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="tamu" {{ old('role') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3" style="padding-left: 8px; padding-right: 8px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Cabang (Branch)</label>
                        <select name="branch_id" class="form-select" required style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                            <option value="">-- Pilih Cabang (Opsional) --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name ?? 'Cabang #' . $branch->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4 form-check" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked style="cursor: pointer; margin-top: 0;">
                    <label class="form-check-label" for="is_active" style="font-size: 13px; font-weight: 700; color: #172033; cursor: pointer;">User Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2" style="flex-wrap: wrap;">
                    <a href="{{ route('user.index') }}" class="btn" style="background: #fff; color: #64748b; border: 1px solid #e8edf5; border-radius: 10px; padding: 11px 22px; font-weight: 800; font-size: 13px; text-decoration: none; text-align: center;">Batal</a>
                    <button type="submit" class="btn text-white" style="background: #013220; border: none; border-radius: 10px; padding: 11px 22px; font-weight: 800; font-size: 13px; box-shadow: 0 4px 15px rgba(0,107,63,.2);" onmouseover="this.style.background='#004d2e'" onmouseout="this.style.background='#013220'">Simpan User</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('formTambahUser');
    const phoneInput = form.querySelector('input[name="phone"]');

    // DIUBAH: dulu field ini menolak SEMUA karakter non-digit termasuk '+',
    // padahal sekarang format yang wajib adalah "+62..." atau "08...".
    // Helper ini menyaring nilai jadi "opsional satu '+' di depan + digit saja",
    // sama seperti yang dipakai di form Edit, supaya '+' tidak lagi diblokir
    // total tapi tetap tidak bisa muncul di tengah atau lebih dari sekali.
    function sanitizePhoneValue(value) {
        const hasLeadingPlus = value.startsWith('+');
        const digitsOnly = value.replace(/[^0-9]/g, '');
        return hasLeadingPlus ? '+' + digitsOnly : digitsOnly;
    }

    // 1) Blokir dari level keydown -> huruf/simbol nggak akan pernah muncul di kolom
    //    (browser desktop). DIUBAH: '+' sekarang diizinkan HANYA kalau kursor ada
    //    di posisi paling depan (index 0) DAN field belum punya '+' sama sekali.
    phoneInput.addEventListener('keydown', function (e) {
        const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
            'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
        const isCtrlCombo = e.ctrlKey || e.metaKey; // izinkan Ctrl+C / Ctrl+V / Ctrl+A dll

        if (allowedKeys.includes(e.key) || isCtrlCombo) return;

        if (e.key === '+') {
            const atStart = this.selectionStart === 0;
            const alreadyHasPlus = this.value.startsWith('+');
            if (atStart && !alreadyHasPlus) return; // izinkan
            e.preventDefault();
            return;
        }

        // Cuma izinkan digit 0-9 (baik dari keyboard utama maupun numpad)
        if (!/^[0-9]$/.test(e.key)) {
            e.preventDefault();
        }
    });

    // 1b) Blokir juga lewat beforeinput -> ini yang paling reliable di HP/keyboard virtual,
    // karena keydown sering ngirim "Unidentified" di Android/Gboard sehingga filter di atas kebobolan.
    // DIUBAH: '+' diizinkan lewat, sisanya tetap dibersihkan di listener 'input' di bawah.
    phoneInput.addEventListener('beforeinput', function (e) {
        if (e.data && /[^0-9+]/.test(e.data)) {
            e.preventDefault();
        }
    });

    // 2) Fallback: bersihin karakter non-digit/'+' yang lolos (misal dari autofill),
    //    dan pastikan '+' cuma boleh satu, di posisi paling depan.
    phoneInput.addEventListener('input', function () {
        const cleaned = sanitizePhoneValue(this.value);
        if (cleaned !== this.value) {
            const cursorFromEnd = this.value.length - this.selectionStart;
            this.value = cleaned;
            const newPos = Math.max(0, this.value.length - cursorFromEnd);
            this.setSelectionRange(newPos, newPos);
        }
    });

    // 3) Bersihin juga saat paste (copy-paste teks yang ada hurufnya),
    //    tetap mempertahankan '+' kalau ada di paling depan hasil paste.
    phoneInput.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const start = this.selectionStart;
        const end = this.selectionEnd;
        const merged = this.value.slice(0, start) + pasted + this.value.slice(end);
        this.value = sanitizePhoneValue(merged);
        this.dispatchEvent(new Event('input'));
    });

    // Tampilkan pesan error custom di bawah tiap field saat submit
    form.addEventListener('submit', function (e) {
        let valid = true;

        form.querySelectorAll('input[required], select[required]').forEach(function (field) {
            const feedback = field.parentElement.querySelector('.invalid-feedback-custom');
            const isValid = field.checkValidity();

            field.style.borderColor = isValid ? '#e8edf5' : '#dc2626';
            if (feedback) feedback.style.display = isValid ? 'none' : 'block';

            if (!isValid) valid = false;
        });

        if (!valid) {
            e.preventDefault();
        }
    });
})();
</script>
@endsection