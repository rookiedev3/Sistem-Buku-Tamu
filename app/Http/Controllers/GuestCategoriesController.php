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

public function laporan(Request $request)
{
    $month = (int) $request->input('month', now()->month);
    $year  = (int) $request->input('year', now()->year);

    $baseQuery = DB::table('guests')
        ->join('guest_categories', 'guest_categories.id', '=', 'guests.guest_category_id')
        ->join('leads', 'leads.guest_id', '=', 'guests.id')
        ->join('visits', 'visits.id', '=', 'leads.visit_id')
        ->where('leads.status', 'deal')
        ->whereMonth('visits.check_in_at', $month)
        ->whereYear('visits.check_in_at', $year);

    // Total keseluruhan tamu (deal) pada bulan terpilih (untuk basis persentase)
    $totalGuests = (clone $baseQuery)->count();

    // Data statistik kategori, tanpa pagination
    $categoryStats = (clone $baseQuery)
        ->select('guest_categories.id', 'guest_categories.name', DB::raw('count(*) as total'))
        ->groupBy('guest_categories.id', 'guest_categories.name')
        ->orderByDesc('total')
        ->get();

    $chartColors = ['#013220', '#1463ff', '#ca8a04', '#7c3aed', '#0284c7', '#c2410c', '#21a86b', '#dc2626'];

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    return view('guest_categories.laporan', compact('categoryStats', 'totalGuests', 'chartColors', 'month', 'year', 'months'));
}
}