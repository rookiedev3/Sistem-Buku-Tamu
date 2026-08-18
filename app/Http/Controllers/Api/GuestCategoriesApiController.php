<?php

namespace App\Http\Controllers\Api;

use App\Models\guest_categories;
use App\Models\visit_purposes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuestCategoriesApiController extends BaseApiController
{
    public function index()
    {
        $guest_category = guest_categories::all();
        return $this->responseHasil(200, true, $guest_category);
    }

    public function show($id)
    {
        $guest_category = guest_categories::find($id);

        if (!$guest_category) {
            return $this->responseHasil(404, false, "Guest Categories tidak ditemukan");
        }

        return $this->responseHasil(200, true, $guest_category);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'    => 'required',
                // 'color'   => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $guest_category = guest_categories::create([
            'name'      => $validated['name'],
            'color'     => $validated['color'] ?? null,
        ]);

        return $this->responseHasil(200, true, $guest_category);
    }

    public function update(Request $request, $id)
    {
        $guest_category = guest_categories::find($id);

        if (!$guest_category) {
            return $this->responseHasil(404, false, "Guest Categories tidak ditemukan");
        }

        try {
            $validated = $request->validate([
                'name'    => 'required',
                // 'color'   => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $guest_category->update([
            'name'      => $validated['name'],
            'color'     => $validated['color'] ?? null,
        ]);

        return $this->responseHasil(200, true, $guest_category);
    }

    public function destroy($id)
    {
        $guest_category = guest_categories::find($id);

        if (!$guest_category) {
            return $this->responseHasil(404, false, "Guest Category tidak ditemukan");
        }

        $guest_category->delete();
        return $this->responseHasil(200, true, "Guest Category berhasil dihapus");
    }
}