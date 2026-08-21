<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends BaseApiController
{
    // TAMBAHAN: pesan error khusus untuk aturan nomor HP, dipakai berulang
    // di store() dan update() supaya konsisten.
    private const PHONE_REGEX_RULE = 'regex:/^(\+62|08)[0-9]+$/';
    private const PHONE_REGEX_MESSAGE = 'No. HP harus diawali +62 atau 08.';

    private function authorizeAdmin(Request $request)
    {
        if (!in_array($request->user()->role, ['owner', 'admin'])) {
            return $this->responseHasil(403, false, "Anda tidak punya akses ke fitur ini");
        }
        return null;
    }

    public function index(Request $request)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $query = User::with('branch')->latest();

        if ($request->query('status') === 'pending') {
            $query->whereNull('role');
        } elseif ($request->query('status') === 'inactive') {
            $query->whereNotNull('role')->where('is_active', false);
        }

        return $this->responseHasil(200, true, $query->get());
    }

    // ← BARU: Admin bikin user langsung, tanpa lewat alur daftar-sendiri + approval
    public function store(Request $request)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            // DIUBAH: nomor HP sekarang wajib diawali "+62" atau "08",
            // disamakan dengan validasi di app Flutter (_validatePhone).
            'phone'     => ['nullable', 'string', 'max:20', self::PHONE_REGEX_RULE],
            'password'  => 'required|min:6',
            'role'      => 'required|in:owner,manager,admin,pic,security,tamu',
            'branch_id' => 'nullable|exists:branches,id',
        ], [
            'phone.regex' => self::PHONE_REGEX_MESSAGE,
        ]);

        if ($validator->fails()) {
            return $this->responseHasil(422, false, $validator->errors());
        }

        $isActive = $request->boolean('is_active', true); // admin bikin user, default langsung aktif

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $this->normalizePhone($request->phone),
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'branch_id'    => $request->branch_id,
            'is_active'    => $isActive,
            'activated_at' => $isActive ? now() : null,
        ]);

        return $this->responseHasil(200, true, $user->load('branch'));
    }

    // ← BARU: Admin edit data user manapun secara lengkap
    public function update(Request $request, $id)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $user = User::find($id);
        if (!$user) {
            return $this->responseHasil(404, false, "User tidak ditemukan");
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            // DIUBAH: sama seperti store(), nomor HP wajib diawali "+62" atau "08".
            'phone'     => ['nullable', 'string', 'max:20', self::PHONE_REGEX_RULE],
            'role'      => 'required|in:owner,manager,admin,pic,security,tamu',
            'branch_id' => 'nullable|exists:branches,id',
            'password'  => 'nullable|min:6', // opsional: cuma diisi kalau mau ganti password
        ], [
            'phone.regex' => self::PHONE_REGEX_MESSAGE,
        ]);

        if ($validator->fails()) {
            return $this->responseHasil(422, false, $validator->errors());
        }

        $isActive = $request->boolean('is_active', $user->is_active);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $this->normalizePhone($request->phone),
            'role'      => $request->role,
            'branch_id' => $request->branch_id,
            'is_active' => $isActive,
        ];

        // Sama seperti approve(): catat activated_at cuma sekali, saat pertama kali diaktifkan
        if ($isActive && is_null($user->activated_at)) {
            $data['activated_at'] = now();
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return $this->responseHasil(200, true, $user->load('branch'));
    }

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
            'activated_at' => $user->activated_at ?? now(),
        ]);

        return $this->responseHasil(200, true, $user);
    }

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

    // ← BARU: disalin persis dari UserController versi web, biar format nomor konsisten
    private function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }

        if (!str_starts_with($clean, '+')) {
            $clean = '+' . $clean;
        }

        return $clean;
    }
}