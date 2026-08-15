<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\follow_ups;
use App\Models\leads;
use App\Models\notifications;
use App\Models\User;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Versi API dari PicController (untuk dikonsumsi Flutter / mobile app).
 *
 * Perbedaan utama dari versi web:
 * - Semua return view(...) diganti response()->json(...)
 * - Semua back()->with('success'/'error', ...) diganti response()->json([...], statusCode)
 * - Auth memakai Bearer token (Laravel Sanctum) -> pasang middleware 'auth:sanctum'
 *   di route group (lihat contoh routes/api.php di bawah)
 * - Semua logic bisnis (query, validasi, penguncian status, dsb) TIDAK diubah
 */
class PicApiController extends Controller
{
    /**
     * GET /api/pic/dashboard
     */
    public function dashboardPic(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $vipFilter = $request->input('vip_status', 'all');
        $keyword = trim((string) $request->input('keyword', ''));
        $perPage = (int) $request->input('per_page', 10);
        $today = Carbon::today();

        $query = visits::with(['guest.category', 'purpose', 'branch'])
            ->where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan']);

        if ($filter === 'today') {
            $query->where(function ($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            });
        } elseif ($filter === 'upcoming') {
            $query->whereDate('scheduled_at', '>', $today);
        }

        if ($keyword !== '') {
            $query->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            $vipCount = (clone $query)->whereHas('guest', fn($q) => $q->where('is_vip', true))->count();
            $regularCount = (clone $query)->whereHas('guest', function ($q) {
                $q->where('is_vip', false)->orWhereNull('is_vip');
            })->count();

            $query->leftJoin('guests', 'visits.guest_id', '=', 'guests.id')
                ->select('visits.*')
                ->orderByRaw('COALESCE(visits.check_in_at, visits.scheduled_at) ASC')
                ->orderByRaw('CASE WHEN guests.is_vip = 1 THEN 0 ELSE 1 END ASC')
                ->orderBy('visits.created_at', 'asc');
        } else {
            $vipCount = 0;
            $regularCount = (clone $query)->count();

            $query->orderByRaw('COALESCE(check_in_at, scheduled_at) ASC')
                ->orderBy('created_at', 'asc');
        }

        $visits = $query->paginate($perPage)->appends($request->query());

        $countToday = visits::where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan'])
            ->where(function ($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            })->count();

        $countUpcoming = visits::where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan'])
            ->whereDate('scheduled_at', '>', $today)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'visits'        => $visits, // paginator otomatis ter-serialize (data, links, meta)
                'vip_count'     => $vipCount,
                'regular_count' => $regularCount,
                'filter'        => $filter,
                'vip_filter'    => $vipFilter,
                'count_today'   => $countToday,
                'count_upcoming'=> $countUpcoming,
            ],
        ]);
    }

    /**
     * GET /api/pic/followups
     */
    public function followupIndex(Request $request)
    {
        $today = Carbon::today();
        $filter = $request->input('filter', 'all');

        $query = leads::with(['guest', 'followUps' => fn($q) => $q->orderBy('created_at', 'desc')])
            ->where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost']);

        if ($filter === 'today') {
            $query->whereDate('follow_up_at', $today);
        } elseif ($filter === 'overdue') {
            $query->whereDate('follow_up_at', '<', $today);
        } elseif ($filter === 'upcoming') {
            $query->whereDate('follow_up_at', '>', $today);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('follow_up_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('follow_up_at', '<=', $request->end_date);
        }

        $leads = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate(10)
            ->appends($request->query());

        $totalLeads = leads::where('owner_id', auth()->id())->count();

        $totalDeal = leads::where('owner_id', auth()->id())
            ->where('status', 'deal')
            ->count();

        $countOverdue = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->whereDate('follow_up_at', '<', $today)
            ->count();

        $countToday = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->whereDate('follow_up_at', $today)
            ->count();

        $countAll = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->count();

        $countUpcoming = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->whereDate('follow_up_at', '>', $today)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'leads'          => $leads,
                'total_leads'    => $totalLeads,
                'total_deal'     => $totalDeal,
                'filter'         => $filter,
                'count_overdue'  => $countOverdue,
                'count_today'    => $countToday,
                'count_all'      => $countAll,
                'count_upcoming' => $countUpcoming,
            ],
        ]);
    }

    /**
     * GET /api/pic/riwayat
     */
    public function riwayatPic(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $vipFilter = $request->input('vip_status', 'all');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
        ]);

        $query = visits::with(['guest', 'purpose', 'branch', 'lead.followUps'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Meeting Selesai', 'Dibatalkan', 'dibatalkan']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('check_in_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_at', '<=', $request->end_date);
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        $visits = $query->orderBy('check_in_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => [
                'visits'     => $visits,
                'vip_filter' => $vipFilter,
            ],
        ]);
    }

    /**
     * PATCH /api/pic/visits/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->first();

        if (!$visit) {
            return response()->json(['success' => false, 'message' => 'Kunjungan tidak ditemukan.'], 404);
        }

        $oldStatus = trim($visit->status ?? '');

        $isConfirmed = in_array($request->status, ['confirmed', 'Dikonfirmasi']);
        $newStatus = $isConfirmed ? 'Dikonfirmasi' : 'Dibatalkan';

        $terminalStatuses = ['meeting selesai', 'selesai', 'dibatalkan', 'completed', 'cancelled'];
        if (in_array(strtolower($oldStatus), $terminalStatuses)) {
            return response()->json(['success' => false, 'message' => 'Status sudah akhir dan tidak dapat diubah lagi.'], 409);
        }

        if (strtolower($oldStatus) === strtolower($newStatus)) {
            return response()->json(['success' => true, 'message' => 'Status sudah sesuai, tidak ada perubahan.', 'data' => $visit]);
        }

        $visit->status = $newStatus;
        $visit->updated_by = auth()->id();

        if ($isConfirmed) {
            $visit->meeting_start_at = now();
        }

        $visit->save();

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
            'data' => $visit->fresh(),
        ]);
    }

    /**
     * POST /api/pic/visits/{id}/complete-meeting
     */
    public function completeMeeting(Request $request, $id)
    {
        $visit = visits::with(['guest', 'lead'])
            ->where('id', $id)
            ->where('assigned_to', auth()->id())
            ->first();

        if (!$visit) {
            return response()->json(['success' => false, 'message' => 'Kunjungan tidak ditemukan.'], 404);
        }

        $oldStatus = trim($visit->status ?? '');

        // 🔒 Sekali dicatat, hasil pertemuan tidak bisa diubah lagi
        if (strtolower($oldStatus) === 'meeting selesai') {
            return response()->json(['success' => false, 'message' => 'Hasil pertemuan sudah pernah dicatat dan tidak bisa diubah lagi.'], 409);
        }

        $request->validate([
            'meeting_result'  => 'required|string',
            'potential_level' => 'required|in:hot,warm,cold,non_lead,deal',
            'follow_up_at'    => 'nullable|date|required_unless:potential_level,warm,cold,non_lead,deal',
            'estimated_value' => 'nullable|numeric|min:0',
        ], [
            'follow_up_at.required_unless' => 'Tanggal follow-up wajib dipilih sebelum menyimpan.',
        ]);

        $existingEstValue = $visit->lead->estimated_value ?? 0;
        $finalEstValue = $request->filled('estimated_value') ? $request->estimated_value : $existingEstValue;

        // 🚫 Cegah Deal tanpa estimasi nilai yang valid (> 0)
        if ($request->potential_level === 'deal' && (float) $finalEstValue <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Estimasi Nilai Deal wajib diisi (lebih dari Rp 0) sebelum bisa ditandai Deal.',
            ], 422);
        }

        $newStatus = 'Meeting Selesai';

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->update([
            'status'               => $newStatus,
            'meeting_result'       => $request->meeting_result,
            'potential_level'      => $request->potential_level,
            'follow_up_at'         => $request->follow_up_at,
            'is_converted_to_lead' => in_array($request->potential_level, ['warm', 'hot', 'deal']),
            'updated_by'           => auth()->id(),
        ]);

        $lead = null;

        if (in_array($request->potential_level, ['warm', 'hot', 'deal'])) {
            $isDeal = $request->potential_level === 'deal';

            $lead = leads::updateOrCreate(
                ['visit_id' => $visit->id],
                [
                    'guest_id'        => $visit->guest_id,
                    'owner_id'        => auth()->id(),
                    'status'          => $isDeal ? 'deal' : 'new',
                    'estimated_value' => $finalEstValue,
                    'follow_up_at'    => $isDeal ? null : $request->follow_up_at,
                ]
            );

            if ($lead->wasRecentlyCreated) {
                $managers = User::whereIn('role', ['manager', 'owner', 'admin'])->get();

                $guestName      = $visit->guest->name ?? 'Tamu';
                $companyName    = $visit->guest->company_name ?? 'Instansi';
                $formattedValue = 'Rp ' . number_format($finalEstValue, 0, ',', '.');
                $picName        = auth()->user()->name ?? 'PIC';
                $potensiText    = strtoupper($request->potential_level);

                $title   = "Lead Baru Masuk: {$guestName}";
                $message = implode("\n", [
                    "Terdapat Lead baru dari {$guestName} ({$companyName})",
                    "• Potensi: {$potensiText}",
                    "• Est. Nilai: {$formattedValue}",
                    "• PIC: {$picName}",
                ]);

                foreach ($managers as $manager) {
                    notifications::send($manager->id, 'new_lead', $title, $message);
                }

                $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
                $waMessage = "*{$title}*\n\n" . $message;

                // Notifikasi WhatsApp Fonnte masih nonaktif — sama seperti versi web,
                // tinggal uncomment kalau sudah siap dipakai.
                //try {
                //    Http::withoutVerifying()
                //        ->withHeaders([
                //            'Authorization' => $token,
                //        ])->post('https://api.fonnte.com/send', [
                //            'target'  => '085926276649',
                //            'message' => $waMessage,
                //        ]);
                //} catch (\Exception $e) {
                //    \Log::error("Gagal kirim WA ke 085926276649: " . $e->getMessage());
                //}
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan hasil pertemuan dan data lead berhasil disimpan!',
            'data' => [
                'visit' => $visit->fresh(['guest', 'lead']),
                'lead'  => $lead,
            ],
        ], 201);
    }

    /**
     * POST /api/pic/leads/{leadId}/follow-up
     */
    public function updateFollowUp(Request $request, $leadId)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,negotiation,deal,lost',
            'result' => 'required|string',
            'due_at' => 'nullable|date',
            'estimated_value' => 'nullable|numeric|min:0',
        ]);

        $lead = leads::where('id', $leadId)
            ->where('owner_id', auth()->id())
            ->first();

        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'Lead tidak ditemukan.'], 404);
        }

        if ($lead->status === 'deal') {
            return response()->json(['success' => false, 'message' => 'Lead ini sudah Deal dan tidak bisa diubah lagi.'], 409);
        }

        $finalEstimatedValue = $request->filled('estimated_value')
            ? $request->estimated_value
            : $lead->estimated_value;

        // PENTING: pakai perbandingan numerik, BUKAN empty()/truthy check
        if ($request->status === 'deal' && (float) $finalEstimatedValue <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Estimasi Nilai Deal wajib diisi (lebih dari Rp 0) sebelum lead bisa ditandai Deal.',
            ], 422);
        }

        $lead->status = $request->status;
        $lead->follow_up_at = in_array($request->status, ['deal', 'lost']) ? null : $request->due_at;
        $lead->estimated_value = $finalEstimatedValue;
        $lead->save();

        $followUp = follow_ups::create([
            'lead_id' => $lead->id,
            'visit_id' => $lead->visit_id,
            'assigned_to' => auth()->id(),
            'due_at' => $request->due_at ?? now(),
            'result' => $request->result,
            'status' => $request->status,
            'estimated_value' => $lead->estimated_value,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pipeline lead berhasil diperbarui!',
            'data' => [
                'lead'      => $lead->fresh(),
                'follow_up' => $followUp,
            ],
        ], 201);
    }

    /**
     * GET /api/pic/leads
     */
    public function leadsIndex(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $today   = Carbon::today();
        $filter  = $request->input('filter', 'active');
        $vipFilter = $request->input('vip_status', 'all');
        $ownerId = auth()->id();

        $query = leads::with(['guest', 'visit', 'followUps'])
            ->where('owner_id', $ownerId)
            ->where('status', '!=', 'lost');

        switch ($filter) {
            case 'active':
                $query->whereNotIn('status', ['deal', 'lost']);
                break;
            case 'overdue':
                $query->whereNotIn('status', ['deal', 'lost'])
                    ->whereDate('follow_up_at', '<', $today);
                break;
            case 'today':
                $query->whereNotIn('status', ['deal', 'lost'])
                    ->whereDate('follow_up_at', $today);
                break;
            case 'upcoming':
                $query->whereNotIn('status', ['deal', 'lost'])
                    ->whereDate('follow_up_at', '>', $today);
                break;
            case 'deal':
                $query->where('status', 'deal');
                break;
        }

        if ($request->filled('start_date')) {
            $query->whereDate('follow_up_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('follow_up_at', '<=', $request->end_date);
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        $leads = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate($perPage)
            ->appends($request->query());

        $baseCount = function () use ($ownerId, $vipFilter) {
            $q = leads::where('owner_id', $ownerId)->where('status', '!=', 'lost');

            if (Schema::hasColumn('guests', 'is_vip')) {
                if ($vipFilter === 'vip') {
                    $q->whereHas('guest', fn($gq) => $gq->where('is_vip', true));
                } elseif ($vipFilter === 'reguler') {
                    $q->whereHas('guest', function ($gq) {
                        $gq->where('is_vip', false)->orWhereNull('is_vip');
                    });
                }
            }

            return $q;
        };

        $countAll      = $baseCount()->count();
        $countActive   = $baseCount()->whereNotIn('status', ['deal', 'lost'])->count();
        $countOverdue  = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
        $countToday    = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
        $countUpcoming = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
        $countDeal     = $baseCount()->where('status', 'deal')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'leads'          => $leads,
                'filter'         => $filter,
                'vip_filter'     => $vipFilter,
                'count_all'      => $countAll,
                'count_active'   => $countActive,
                'count_overdue'  => $countOverdue,
                'count_today'    => $countToday,
                'count_upcoming' => $countUpcoming,
                'count_deal'     => $countDeal,
            ],
        ]);
    }

    /**
     * POST /api/pic/visits/{id}/start-meeting
     */
    public function startMeeting($id)
    {
        $visit = visits::find($id);

        if (!$visit) {
            return response()->json(['success' => false, 'message' => 'Kunjungan tidak ditemukan.'], 404);
        }

        $oldStatus = trim($visit->status ?? '');
        $newStatus = 'Sedang Bertemu';

        if (strtolower($oldStatus) !== strtolower($newStatus)) {
            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);
        }

        $visit->update([
            'status' => $newStatus,
            'meeting_start_at' => $visit->meeting_start_at ?? now(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan dimulai. Silakan lakukan diskusi dengan tamu.',
            'data' => $visit->fresh(),
        ]);
    }
}