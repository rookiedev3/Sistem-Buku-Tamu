<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Guest;
use App\Models\guests;
use App\Models\visits;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'perusahaan'   => 'nullable|string|max:255',
            'purpose_id'   => 'required|integer|exists:purposes,id',
            'assigned_to'  => 'nullable|integer|exists:users,id',
            'source_id'    => 'nullable|integer|exists:sources,id',
            'catatan'      => 'nullable|string',
            'produk'       => 'nullable|array',
            'produk.*'     => 'string',
            'branch_id'    => 'nullable|integer|exists:branches,id',
        ]);

        try {
            DB::beginTransaction();
            $guest = Guest::firstOrCreate(
                ['phone' => $validated['whatsapp']],
                [
                    'name'    => $validated['name'],
                    'company' => $validated['perusahaan'] ?? null,
                ]
            );
    }
}
