<?php

namespace App\Http\Controllers;

use App\Models\lead_sources;
use App\Models\products;
use App\Models\visit_purposes;
use Illuminate\Http\Request;

class LeadSourcesController extends Controller
{
    public function index()
    {
        $lead_sources = lead_sources::all();
        return view('lead_sources.index', compact('lead_sources'));
    }

    public function create()
    {
        return view('lead_sources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required'
        ]);

        try {
            $lead_source = new lead_sources();
            $lead_source->name       = $request->name;
            $lead_source->save();

            return redirect()->route('lead-sources.index')->with('success', 'Lead Source berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan Lead Source. Silakan coba lagi.']);
        }
    }

    public function edit($id)
    {
        $lead_source = lead_sources::findOrFail($id);
        return view('lead_sources.edit', compact('lead_source'));
    }

    public function update(Request $request, $id)
    {
        $lead_source = lead_sources::findOrFail($id);

        $request->validate([
            'name'    => 'required',
        ]);

        try {
            $lead_source->name      = $request->name;
            $lead_source->save();

            return redirect()->route('lead-sources.index')->with('success', 'Lead Source berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui Lead Source. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $lead_source = lead_sources::findOrFail($id);
            $lead_source->delete();
            return redirect()->route('lead-sources.index')->with('success', 'Lead Source berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus Lead Source. Silakan coba lagi.']);
        }
    }
}