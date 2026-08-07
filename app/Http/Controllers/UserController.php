<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\branches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Tampil Daftar User
    public function index()
    {
        $users = User::with('branch')->latest()->get();
        return view('user.index', compact('users'));
    }

    // 2. Form Tambah User
    public function create()
    {
        $branches = branches::all();
        return view('user.create', compact('branches'));
    }

    // 3. Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|min:6',
            'role'      => 'required|in:owner,manager,admin,pic,security,tamu',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $this->normalizePhone($request->phone),
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'branch_id' => $request->branch_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // 4. Form Edit User
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $branches = branches::all();
        return view('user.edit', compact('user', 'branches'));
    }

    // 5. Update User
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            'phone'     => 'nullable|string|max:20',
            'role'      => 'required|in:owner,manager,admin,pic,security,tamu',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $this->normalizePhone($request->phone),
            'role'      => $request->role,
            'branch_id' => $request->branch_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // 6. Hapus User
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Samakan format nomor telepon dengan yang dipakai di VisitsController::storeStep1()
     * Contoh: "081234567890" atau "81234567890" -> "+6281234567890"
     */
    private function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        if (! str_starts_with($clean, '+')) {
            $clean = '+'.$clean;
        }

        return $clean;
    }
}