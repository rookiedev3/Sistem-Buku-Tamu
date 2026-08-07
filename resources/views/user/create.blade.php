@extends('layouts.frontoffice') {{-- Mengikuti layout frontoffice yang konsisten --}}

@section('content')
<div class="container my-4">
    <div class="card shadow-sm col-md-8 mx-auto" style="border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.08) !important; overflow: hidden;">
        
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: #006B3F; padding: 18px 24px; border-bottom: none;">
            <h5 class="mb-0" style="font-weight: 800; font-size: 16px;">Tambah User Baru</h5>
            <a href="{{ route('user.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; font-weight: 700; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); padding: 5px 12px; font-size: 12px; text-decoration: none;">Kembali</a>
        </div>

        <div class="card-body" style="padding: 28px;">
            
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

            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Masukkan nama" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; border: 1px solid #e8edf5;">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="contoh@gmail.com" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; border: 1px solid #e8edf5;">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Nomor Telepon/HP</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08123456789" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; border: 1px solid #e8edf5;">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; border: 1px solid #e8edf5;">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required style="border-radius: 10px; padding: 10px 14px; font-size: 13px; border: 1px solid #e8edf5;">
                            <option value="">-- Pilih Role --</option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pic" {{ old('role') == 'pic' ? 'selected' : '' }}>PIC / Sales</option>
                            <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="tamu" {{ old('role') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 800; color: #172033;">Cabang (Branch)</label>
                        <select name="branch_id" class="form-select" style="border-radius: 10px; padding: 10px 14px; font-size: 13px; border: 1px solid #e8edf5;">
                            <option value="">-- Pilih Cabang (Opsional) --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name ?? 'Cabang #' . $branch->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked style="cursor: pointer;">
                    <label class="form-check-label" for="is_active" style="font-size: 13px; font-weight: 700; color: #172033; cursor: pointer;">User Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('user.index') }}" class="btn" style="background: #fff; color: #64748b; border: 1px solid #e8edf5; border-radius: 10px; padding: 10px 20px; font-weight: 800; font-size: 13px;">Batal</a>
                    <button type="submit" class="btn text-white" style="background: #006B3F; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 800; font-size: 13px; box-shadow: 0 4px 15px rgba(0,107,63,.2);" onmouseover="this.style.background='#004d2e'" onmouseout="this.style.background='#006B3F'">Simpan User</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection