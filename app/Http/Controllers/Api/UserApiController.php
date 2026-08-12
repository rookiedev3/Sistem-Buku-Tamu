<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends BaseApiController
{
    // Cuma owner & admin yang boleh kelola user
    private function authorizeAdmin(Request $request)
    {
        if (!in_array($request->user()->role, ['owner', 'admin'])) {
            return $this->responseHasil(403, false, "Anda tidak punya akses ke fitur ini");
        }
        return null;
    }

    // Daftar semua user. Tambah ?status=pending buat lihat yang nunggu approval
    public function index(Request $request)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $query = User::with('branch')->latest();

        if ($request->query('status') === 'pending') {
            // Menunggu Persetujuan: baru daftar, belum pernah di-approve sama sekali
            $query->whereNull('role');
        } elseif ($request->query('status') === 'inactive') {
            // Nonaktif: sudah pernah aktif (punya role), tapi sekarang dimatikan admin
            $query->whereNotNull('role')->where('is_active', false);
        }

        return $this->responseHasil(200, true, $query->get());
    }

    // Approve user baru daftar: aktifkan + kasih role + catat activated_at
    public function approve(Request $request, $id)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $user = User::find($id);
        if (!$user) {
            return $this->responseHasil(404, false, "User tidak ditemukan");
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:owner,manager,admin,pic,security,tamu',
        ]);
        if ($validator->fails()) {
            return $this->responseHasil(422, false, $validator->errors());
        }

        $user->update([
            'role'         => $request->role,
            'is_active'    => true,
            'activated_at' => $user->activated_at ?? now(), // isi cuma kalau belum pernah aktif
        ]);

        return $this->responseHasil(200, true, $user);
    }

    // Nonaktifkan user (tanpa hapus data)
    public function deactivate(Request $request, $id)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $user = User::find($id);
        if (!$user) {
            return $this->responseHasil(404, false, "User tidak ditemukan");
        }

        $user->update(['is_active' => false]);
        return $this->responseHasil(200, true, "User dinonaktifkan");
    }

    public function destroy(Request $request, $id)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        if ((int) $id === $request->user()->id) {
            return $this->responseHasil(400, false, "Tidak bisa menghapus akun sendiri");
        }

        $user = User::find($id);
        if (!$user) {
            return $this->responseHasil(404, false, "User tidak ditemukan");
        }

        $user->delete();
        return $this->responseHasil(200, true, "User berhasil dihapus");
    }
}