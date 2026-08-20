<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SecurityApiController extends Controller
{
    /**
     * GET /api/security/dashboard
     */
    public function dashboard(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 10);
        $selectedDate = $request->get('date', Carbon::today()->toDateString());

        if (Carbon::parse($selectedDate)->isAfter(Carbon::today())) {
            $selectedDate = Carbon::today()->toDateString();
        }

        $visits = visits::with(['guest:id,name,company_name,is_vip', 'assignedUser:id,name'])
            ->select('id', 'visit_code', 'guest_id', 'assigned_to', 'scheduled_at', 'check_in_at', 'check_out_at', 'status')
            ->where(function ($q) use ($selectedDate) {
                $q->whereDate('scheduled_at', $selectedDate)
                    ->orWhereDate('check_in_at', $selectedDate);
            })
            ->orderBy('scheduled_at', 'asc')
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json([
            'success'       => true,
            'selected_date' => $selectedDate,
            'total_today'   => (int) $visits->total(),
            'data'          => $visits->items(),
            'pagination'    => [
                'current_page' => (int) $visits->currentPage(),
                'last_page'    => (int) $visits->lastPage(),
                'per_page'     => (int) $visits->perPage(),
                'total'        => (int) $visits->total(),
            ],
        ]);
    }


    public function checkIn($id): JsonResponse
    {
        $visit = visits::findOrFail($id);

        $visit->update([
            'status'      => 'confirmed',
            'check_in_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tamu berhasil dicatat masuk area.',
            'data'    => $visit->fresh(['guest:id,name,company_name,is_vip', 'assignedUser:id,name']),
        ]);
    }

    public function checkOut($id): JsonResponse
    {
        $visit = visits::findOrFail($id);

        $visit->update([
            'check_out_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tamu berhasil dicatat keluar area.',
            'data'    => $visit->fresh(['guest:id,name,company_name,is_vip', 'assignedUser:id,name']),
        ]);
    }
}