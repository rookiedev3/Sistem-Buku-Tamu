<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }

public function login(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ], [
        'email.required' => 'Email tidak boleh kosong',
        'password.required' => 'Password tidak boleh kosong',
    ]);

    $kredensial = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($kredensial, $remember)) {
        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();

            // Bedakan pesan: akun baru daftar & belum pernah disetujui (activated_at masih null)
            // vs akun yang sudah pernah aktif tapi kemudian dinonaktifkan admin.
            $message = is_null($user->activated_at)
                ? 'Akun Anda masih menunggu persetujuan admin.'
                : 'Akun Anda telah dinonaktifkan oleh admin. Silakan hubungi admin jika ini adalah kesalahan.';

            return back()->withErrors([
                'email' => $message,
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah',
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Silahkan login kembali.');
    }
    
    public function register(Request $request)
    {
    $request->validate([
        'name'     => 'required|string|max:150',
        'email'    => 'required|email|unique:users,email',
        'phone'    => 'required|string|max:25',
        'branch_id'=> 'required|exists:branches,id',
        'password' => 'required|min:6|confirmed',
    ], [
        'name.required'      => 'Nama wajib diisi.',
        'email.required'     => 'Email wajib diisi.',
        'email.email'        => 'Format email tidak valid.',
        'email.unique'       => 'Email sudah digunakan.',
        'phone.required'     => 'Nomor telepon wajib diisi.',
        'branch_id.required' => 'Cabang wajib dipilih.',
        'branch_id.exists'   => 'Cabang tidak valid.',
        'password.required'  => 'Password tidak boleh kosong.',
        'password.min'       => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    try{
    User::create([
        'name'      => $request->name,
        'email'     => $request->email,
        'phone'     => $request->phone,
        'branch_id' => $request->branch_id,
        'password'  => Hash::make($request->password),
        'role'      => null,
        'is_active' => false,
        'activated_at' => null,
    ]);

    return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Tunggu persetujuan admin untuk dapat login.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.']);
    }
    } 


    public function showRegister()
    {
        $branches = \App\Models\Branches::active()->get();
        return view('auth.register', compact('branches'));
    }

    public function showForgotPasswordForm()
{
    return view('auth.forgot-password');
}

public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ], [
        'email.required' => 'Email tidak boleh kosong',
        'email.email'    => 'Format email tidak valid',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Link reset password telah dikirim ke email Anda.')
        : back()->withErrors(['email' => __($status)]);
}

public function showResetForm(Request $request, $token)
{
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email,
    ]);
}

// public function resetPassword(Request $request)
// {
//     $request->validate([
//         'token'    => 'required',
//         'email'    => 'required|email',
//         'password' => 'required|min:6|confirmed',
//     ], [
//         'password.required'  => 'Password tidak boleh kosong',
//         'password.min'       => 'Password minimal 6 karakter',
//         'password.confirmed' => 'Konfirmasi password tidak cocok',
//     ]);

//     $status = Password::reset(
//         $request->only('email', 'password', 'password_confirmation', 'token'),
//         function ($user, $password) {
//             $user->forceFill([
//                 'password' => Hash::make($password),
//             ])->setRememberToken(Str::random(60));

//             $user->save();

//             event(new PasswordReset($user));
//         }
//     );

//     return $status === Password::PASSWORD_RESET
//         ? redirect()->route('login')->with('success', 'Password berhasil diubah, silakan login.')
//         : back()->withErrors(['email' => __($status)]);
// }
public function resetPassword(Request $request)
{
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed',
    ], [
        'password.required'  => 'Password tidak boleh kosong',
        'password.min'       => 'Password minimal 6 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('password.reset.success') // ganti dari route('login')
        : back()->withErrors(['email' => __($status)]);
}


}