<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user) {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('auth.login');
        } else {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ]);
        }
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

    User::create([
        'name'      => $request->name,
        'email'     => $request->email,
        'phone'     => $request->phone,
        'branch_id' => $request->branch_id,
        'password'  => Hash::make($request->password),
        'role'      => 'tamu',
        'is_active' => true,
    ]);

    return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silahkan login.');
    }

    public function showRegister()
    {
        $branches = \App\Models\Branches::active()->get();
        return view('auth.register', compact('branches'));
    }
}
