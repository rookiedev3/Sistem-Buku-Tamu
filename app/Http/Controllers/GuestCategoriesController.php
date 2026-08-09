<?php

namespace App\Http\Controllers;

use App\Models\guest_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestCategoriesController extends Controller
{
    public function index()
    {
        $guest_category = guest_categories::all();
        return view('guest_categories.index', compact('guest_category'));
    }

    public function create()
    {
        return view('guest_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'color'  => 'required'
        ]);

        try {
            $guest_category = new guest_categories();
            $guest_category->name       = $request->name;
            $guest_category->color      = $request->color;
            $guest_category->save();

            return redirect()->route('guest-categories.index')->with('success', 'Guest Category berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan Guest Category. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $guest_category = guest_categories::findOrFail($id);
        return view('guest_categories.edit', compact('guest_category'));
    }

    public function update(Request $request, $id)
    {
        $guest_category = guest_categories::findOrFail($id);

        $request->validate([
            'name'    => 'required',
            'color'  => 'required'
        ]);

        try {
            $guest_category->name      = $request->name;
            $guest_category->color     = $request->color;
            $guest_category->save();

            return redirect()->route('guest-categories.index')->with('success', 'Guest Category berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui Guest Category. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $guest_category = guest_categories::findOrFail($id);
            $guest_category->delete();
            return redirect()->route('guest-categories.index')->with('success', 'Guest Category berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus Guest Category. Silakan coba lagi.']);
        }
    }

public function laporan(Request $request) // Pastikan memasukkan Request
{
    // Mengambil nilai per_page dari URL, default 10
    $perPage = (int) $request->input('per_page', 10);

    // Hitung total KESELURUHAN tamu (agar kalkulasi persen tidak hanya dari halaman aktif)
    $totalGuests = DB::table('guests')
        ->join('guest_categories', 'guest_categories.id', '=', 'guests.guest_category_id')
        ->count();

    // Mengambil data dengan paginate
    $categoryStats = DB::table('guests')
        ->join('guest_categories', 'guest_categories.id', '=', 'guests.guest_category_id')
        ->select('guest_categories.id', 'guest_categories.name', DB::raw('count(*) as total'))
        ->groupBy('guest_categories.id', 'guest_categories.name')
        ->orderByDesc('total')
        ->paginate($perPage)
        ->appends($request->query());

    $chartColors = ['#013220', '#1463ff', '#ca8a04', '#7c3aed', '#0284c7', '#c2410c', '#21a86b', '#dc2626'];

    return view('guest_categories.laporan', compact('categoryStats', 'totalGuests', 'chartColors'));
}
}