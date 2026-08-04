<?php

namespace App\Http\Controllers\Api;

use App\Models\Branches;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BranchesApiController extends BaseApiController
{
    public function index()
    {
        $branches = Branches::all();
        return $this->responseHasil(200, true, $branches);
    }

    public function show($id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return $this->responseHasil(404, false, "Branch tidak ditemukan");
        }

        return $this->responseHasil(200, true, $branch);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code'    => 'required|unique:branches,code',
                'name'    => 'required',
                'address' => 'required',
                'phone'   => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $branch = Branches::create([
            'code'      => $validated['code'],
            'name'      => $validated['name'],
            'address'   => $validated['address'],
            'phone'     => $validated['phone'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return $this->responseHasil(200, true, $branch);
    }

    public function update(Request $request, $id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return $this->responseHasil(404, false, "Branch tidak ditemukan");
        }

        try {
            $validated = $request->validate([
                'code'    => 'required|unique:branches,code,' . $id,
                'name'    => 'required',
                'address' => 'required',
                'phone'   => 'required',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $branch->update([
            'code'      => $validated['code'],
            'name'      => $validated['name'],
            'address'   => $validated['address'],
            'phone'     => $validated['phone'],
            'is_active' => $request->boolean('is_active', $branch->is_active),
        ]);

        return $this->responseHasil(200, true, $branch);
    }

    public function destroy($id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return $this->responseHasil(404, false, "Branch tidak ditemukan");
        }

        $branch->delete();
        return $this->responseHasil(200, true, "Branch berhasil dihapus");
    }
}