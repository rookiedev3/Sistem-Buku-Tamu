<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\Visits;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VisitApiController extends Controller
{
    public function index(): JsonResponse
    {
        $visits = Visits::with(['guest', 'branch', 'purpose', 'assignedUser'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar data kunjungan berhasil diambil.',
            'data'    => $visits,
        ], 200);
    }

    public function store(StoreVisitRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['created_by'] = Auth::id() ?? $request->input('created_by');

        $visit = Visits::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kunjungan berhasil dibuat.',
            'data'    => $visit,
        ], 201);
    }

    public function show(Visits $visit): JsonResponse
    {
        $visit->load(['guest', 'branch', 'purpose', 'source', 'assignedUser', 'creator']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail data kunjungan berhasil diambil.',
            'data'    => $visit,
        ], 200);
    }

    public function update(UpdateVisitRequest $request, Visits $visit): JsonResponse
    {
        $validated = $request->validated();

        $validated['updated_by'] = Auth::id() ?? $request->input('updated_by');

        $visit->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kunjungan berhasil diperbarui.',
            'data'    => $visit,
        ], 200);
    }

    public function destroy(Visits $visit): JsonResponse
    {
        $visit->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kunjungan berhasil dihapus.',
        ], 200);
    }
}