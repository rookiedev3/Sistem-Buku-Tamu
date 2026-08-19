<?php

namespace App\Http\Controllers;

use App\Models\branches;
use Illuminate\Http\Request;

class BranchesController extends Controller
{
    public function index()
    {
        $branches = branches::all();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'    => 'required|unique:branches,code',
            'name'    => 'required',
            'address' => 'required',
            'phone'   => 'required',
        ]);

        try {
            $branch = new branches();
            $branch->code       = $request->code;
            $branch->name       = $request->name;
            $branch->address    = $request->address;
            $branch->phone      = $request->phone;
            $branch->is_active  = $request->boolean('is_active'); // checkbox: default false kalau gak dicentang
            $branch->save();

            return redirect()->route('branches.index')->with('success', 'Branch berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan Branch. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $branch = branches::findOrFail($id);
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = branches::findOrFail($id);

        $request->validate([
            'code'    => 'required|unique:branches,code,' . $id,
            'name'    => 'required',
            'address' => 'required',
            'phone'   => 'required',
        ]);

        try {
            $branch->code      = $request->code;
            $branch->name      = $request->name;
            $branch->address   = $request->address;
            $branch->phone     = $request->phone;
            $branch->is_active = $request->boolean('is_active');
            $branch->save();

            return redirect()->route('branches.index')->with('success', 'Branch berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui Branch. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $branch = branches::findOrFail($id);
            $branch->delete();
            return redirect()->route('branches.index')->with('success', 'Branch berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus Branch. Silakan coba lagi.']);
        }
    }
}