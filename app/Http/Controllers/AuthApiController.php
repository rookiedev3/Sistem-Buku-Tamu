<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends BaseApiController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseHasil(422, false, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->responseHasil(401, false, "Email atau password salah");
        }

        if (!$user->is_active) {
            return $this->responseHasil(403, false, "Akun belum aktif / dinonaktifkan");
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseHasil(200, true, "Logout berhasil");
    }

    public function me(Request $request)
    {
        return $this->responseHasil(200, true, $request->user());
    }
}