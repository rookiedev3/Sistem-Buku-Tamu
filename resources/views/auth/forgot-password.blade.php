@extends('layouts.frontoffice') {{-- sesuaikan kalau login pakai layout lain, atau full HTML kayak login.blade.php --}}

@section('content')
<div class="login-wrapper mx-auto" style="max-width: 500px; margin-top: 60px;">
    <div class="login-form-side" style="padding: 40px;">
        <h3 class="fw-bold mb-1" style="color: #172033; font-size: 22px;">Lupa Password?</h3>
        <p class="text-secondary mb-4" style="font-size: 13px;">
            Masukkan email Anda, kami akan kirimkan link untuk reset password.
        </p>

        @if (session('success'))
            <div style="background-color: #e6f4ea; color: #15803d; padding: 12px 14px; border-radius: 10px; font-size: 12.5px; margin-bottom: 20px; border: 1px solid #c8e6d3; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 10px; font-size: 12.5px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 600;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" style="font-size: 12.5px; font-weight: 700; color: #172033;">Alamat Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="nama@perusahaan.com">
            </div>

            <button type="submit" class="btn btn-custom-login w-100 shadow-sm">Kirim Link Reset</button>
        </form>

        <div class="text-center mt-4" style="font-size: 13px; color: #64748b;">
            <a href="{{ route('login') }}" style="color: #006B3F; text-decoration: none; font-weight: 700;">Kembali ke Login</a>
        </div>
    </div>
</div>
@endsection