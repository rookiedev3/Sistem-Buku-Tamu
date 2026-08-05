<?php

namespace App\Http\Controllers;

use App\Models\guest_categories;
use Illuminate\Http\Request;

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
}