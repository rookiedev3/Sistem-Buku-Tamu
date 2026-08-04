<?php

namespace App\Http\Controllers\Api;

use App\Models\visit_purposes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VisitPurposesApiController extends BaseApiController
{
    public function index()
    {
        $visit_purposes = visit_purposes::all();
        return $this->responseHasil(200, true, $visit_purposes);
    }

    public function show($id)
    {
        $visit_purposes = visit_purposes::find($id);

        if (!$visit_purposes) {
            return $this->responseHasil(404, false, "Visit Purpose tidak ditemukan");
        }

        return $this->responseHasil(200, true, $visit_purposes);
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

        $visit_purposes = visit_purposes::create([
            'name'      => $validated['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return $this->responseHasil(200, true, $visit_purposes);
    }

    public function update(Request $request, $id)
    {
        $visit_purposes = visit_purposes::find($id);

        if (!$visit_purposes) {
            return $this->responseHasil(404, false, "Visit Purpose tidak ditemukan");
        }

        try {
            $validated = $request->validate([
                'name'    => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $visit_purposes->update([
            'name'      => $validated['name'],
            'is_active' => $request->boolean('is_active', $visit_purposes->is_active),
        ]);

        return $this->responseHasil(200, true, $visit_purposes);
    }

    public function destroy($id)
    {
        $visit_purposes = visit_purposes::find($id);

        if (!$visit_purposes) {
            return $this->responseHasil(404, false, "Visit Purpose tidak ditemukan");
        }

        $visit_purposes->delete();
        return $this->responseHasil(200, true, "Visit Purpose berhasil dihapus");
    }
}