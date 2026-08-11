<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\branches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Tampil Daftar User
    public function index(Request $request)
    {
        // 1. Ambil nilai per_page dinamis (Default 10)
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        // 2. Query Data User beserta Relasi Branch
        $query = User::with('branch');

        // Filter Pencarian Keyword Server-Side (Nama / Email / Telepon / Role / Cabang)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('role', 'like', "%{$keyword}%")
                    ->orWhereHas('branch', function ($b) use ($keyword) {
                        $b->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        // 3. Eksekusi Pagination dengan Mempertahankan Query String
        $users = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

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

        $isActive = $request->has('is_active') ? 1 : 0;

        user::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $this->normalizePhone($request->phone),
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'branch_id'    => $request->branch_id,
            'is_active'    => $isActive,
            // Kalau admin langsung mencentang aktif saat membuat user, catat waktu aktivasinya.
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

        $isActive = $request->has('is_active') ? 1 : 0;

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $this->normalizePhone($request->phone),
            'role'      => $request->role,
            'branch_id' => $request->branch_id,
            'is_active' => $isActive,
        ];

        // Catat waktu aktivasi HANYA saat pertama kali user diaktifkan (activated_at masih null).
        // Kalau admin menonaktifkan lagi setelah ini, activated_at TIDAK dihapus/direset,
        // supaya sistem tetap tahu bahwa user ini "pernah aktif" (bukan pending baru daftar).
        if ($isActive && is_null($user->activated_at)) {
            $data['activated_at'] = now();
        }

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
            $clean = '62' . substr($clean, 1);
        }

        if (! str_starts_with($clean, '+')) {
            $clean = '+' . $clean;
        }

        return $clean;
    }
}