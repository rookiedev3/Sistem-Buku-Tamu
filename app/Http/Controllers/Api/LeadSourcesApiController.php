<?php

namespace App\Http\Controllers\Api;

use App\Models\lead_sources;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeadSourcesApiController extends BaseApiController
{
    public function index()
    {
        $lead_sources = lead_sources::all();
        return $this->responseHasil(200, true, $lead_sources);
    }

    public function show($id)
    {
        $lead_sources = lead_sources::find($id);

        if (!$lead_sources) {
            return $this->responseHasil(404, false, "Lead Source tidak ditemukan");
        }

        return $this->responseHasil(200, true, $lead_sources);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'    => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $lead_sources = lead_sources::create([
            'name'      => $validated['name'],
        ]);

        return $this->responseHasil(200, true, $lead_sources);
    }

    public function update(Request $request, $id)
    {
        $lead_sources = lead_sources::find($id);

        if (!$lead_sources) {
            return $this->responseHasil(404, false, "Lead Source tidak ditemukan");
        }

        try {
            $validated = $request->validate([
                'name'    => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $lead_sources->update([
            'name'      => $validated['name'],
        ]);

        return $this->responseHasil(200, true, $lead_sources);
    }

    public function destroy($id)
    {
        $lead_sources = lead_sources::find($id);

        if (!$lead_sources) {
            return $this->responseHasil(404, false, "Lead Source tidak ditemukan");
        }

        $lead_sources->delete();
        return $this->responseHasil(200, true, "Lead Source berhasil dihapus");
    }
}