@extends('layouts.frontoffice') {{-- Menggunakan layout frontoffice yang konsisten --}}

@section('content')
<div class="container my-4" style="padding: 0 16px; box-sizing: border-box;">
    <div class="card shadow-sm mx-auto" style="width: 100%; max-width: 900px; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.08) !important; overflow: hidden; box-sizing: border-box;">
        
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: #013220; padding: 22px 32px; border-bottom: none; box-sizing: border-box;">
            <h5 class="mb-0" style="font-weight: 800; font-size: 17px;">Edit Data User</h5>
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

            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Nomor Telepon/HP</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                    <small style="color: #778195; font-size: 11.5px; margin-top: 4px; display: block;">Biarkan kosong jika password tidak diubah.</small>
                </div>

                <div class="row" style="margin-left: -8px; margin-right: -8px;">
                    <div class="col-md-6 mb-3" style="padding-left: 8px; padding-right: 8px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                            <option value="" disabled {{ old('role', $user->role) ? '' : 'selected' }}>-- Pilih Role --</option>
                            <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pic" {{ old('role', $user->role) == 'pic' ? 'selected' : '' }}>PIC / Sales</option>
                            <option value="security" {{ old('role', $user->role) == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="tamu" {{ old('role', $user->role) == 'tamu' ? 'selected' : '' }}>Tamu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3" style="padding-left: 8px; padding-right: 8px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Cabang (Branch)</label>
                        <select name="branch_id" class="form-select" style="border-radius: 10px; padding: 11px 16px; font-size: 13px; border: 1px solid #e8edf5; width: 100%; box-sizing: border-box;">
                            <option value="">-- Pilih Cabang (Opsional) --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name ?? 'Cabang #' . $branch->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4 form-check" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="cursor: pointer; margin-top: 0;">
                    <label class="form-check-label" for="is_active" style="font-size: 13px; font-weight: 700; color: #172033; cursor: pointer;">User Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2" style="flex-wrap: wrap;">
                    <a href="{{ route('user.index') }}" class="btn" style="background: #fff; color: #64748b; border: 1px solid #e8edf5; border-radius: 10px; padding: 11px 22px; font-weight: 800; font-size: 13px; text-decoration: none; text-align: center;">Batal</a>
                    <button type="submit" class="btn text-white" style="background: #013220; border: none; border-radius: 10px; padding: 11px 22px; font-weight: 800; font-size: 13px; box-shadow: 0 4px 15px rgba(0,107,63,.2);" onmouseover="this.style.background='#004d2e'" onmouseout="this.style.background='#006B3F'">Update User</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection