@extends('layouts.frontoffice')

@section('content')
<div class="login-wrapper mx-auto" style="max-width: 500px; margin-top: 60px;">
    <div class="login-form-side" style="padding: 40px;">
        <h3 class="fw-bold mb-1" style="color: #172033; font-size: 22px;">Reset Password</h3>
        <p class="text-secondary mb-4" style="font-size: 13px;">Masukkan password baru Anda.</p>

        @if ($errors->any())
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 10px; font-size: 12.5px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 600;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #172033;">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required readonly>
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #172033;">Password Baru</label>
                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #172033;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-custom-login w-100 shadow-sm">Simpan Password Baru</button>
        </form>
    </div>
</div>
@endsection