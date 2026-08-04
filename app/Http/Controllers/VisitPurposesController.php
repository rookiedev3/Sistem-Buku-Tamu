<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\visit_purposes;
use Illuminate\Http\Request;

class VisitPurposesController extends Controller
{
    public function index()
    {
        $visit_purposes = visit_purposes::all();
        return view('visit_purposes.index', compact('visit_purposes'));
    }

    public function create()
    {
        return view('visit_purposes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required'
        ]);

        try {
            $visit_purpose = new visit_purposes();
            $visit_purpose->name       = $request->name;
            $visit_purpose->is_active  = $request->boolean('is_active'); // checkbox: default false kalau gak dicentang
            $visit_purpose->save();

            return redirect()->route('visit-purposes.index')->with('success', 'Visit Purpose berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan Visit Purpose. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $visit_purposes = visit_purposes::findOrFail($id);
        return view('visit_purposes.edit', compact('visit_purposes'));
    }

    public function update(Request $request, $id)
    {
        $visit_purposes = visit_purposes::findOrFail($id);

        $request->validate([
            'name'    => 'required',
        ]);

        try {
            $visit_purposes->name      = $request->name;
            $visit_purposes->is_active = $request->boolean('is_active');
            $visit_purposes->save();

            return redirect()->route('visit-purposes.index')->with('success', 'Visit Purpose berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui Visit Purpose. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $visit_purposes = visit_purposes::findOrFail($id);
            $visit_purposes->delete();
            return redirect()->route('visit-purposes.index')->with('success', 'Visit Purpose berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus Visit Purpose. Silakan coba lagi.']);
        }
    }
}