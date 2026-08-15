<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Hash;

class AuthApiController extends BaseApiController
{
public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required',
        'remember' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        return $this->responseHasil(422, false, $validator->errors());
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return $this->responseHasil(401, false, "Email atau password salah");
    }

    if (is_null($user->role)) {
        return $this->responseHasil(403, false, "Akun masih menunggu persetujuan admin");
    }

    if (!$user->is_active) {
        return $this->responseHasil(403, false, "Akun Anda telah dinonaktifkan. Hubungi admin.");
    }

    $remember = $request->boolean('remember');

    // Kalau "Ingat Saya" dicentang → token berlaku 30 hari
    // Kalau tidak → token berlaku 1 hari saja
    $expiresAt = $remember ? now()->addDays(30) : now()->addDay();

    $token = $user->createToken('mobile-app', ['*'], $expiresAt)->plainTextToken;

    return $this->responseHasil(200, true, [
        'token' => $token,
        'user'  => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ],
    ]);
}
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'      => 'required|string|max:150',
        'email'     => 'required|email|unique:users,email',
        'phone'     => 'required|string|max:25',
        'branch_id' => 'required|exists:branches,id',
        'password'  => 'required|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return $this->responseHasil(422, false, $validator->errors());
    }

    $user = User::create([
        'name'         => $request->name,
        'email'        => $request->email,
        'phone'        => $this->normalizePhone($request->phone), // ← DIGANTI
        'branch_id'    => $request->branch_id,
        'password'     => Hash::make($request->password),
        'role'         => null,
        'is_active'    => false,
        'activated_at' => null,
    ]);

    return $this->responseHasil(200, true, "Pendaftaran berhasil! Tunggu persetujuan admin untuk dapat login.");
}

// ← TAMBAHAN: method baru, sama persis logic-nya kayak yang dipakai UserController di web
private function normalizePhone($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (str_starts_with($phone, '0')) {
        $phone = '62' . substr($phone, 1);
    }
    if (!str_starts_with($phone, '+')) {
        $phone = '+' . $phone;
    }
    return $phone;
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseHasil(200, true, "Logout berhasil");
    }

    public function me(Request $request)
    {
        return $this->responseHasil(200, true, $request->user());
    }

    public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        return $this->responseHasil(422, false, $validator->errors());
    }

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? $this->responseHasil(200, true, "Link reset password telah dikirim ke email Anda.")
        : $this->responseHasil(422, false, __($status));
}
}