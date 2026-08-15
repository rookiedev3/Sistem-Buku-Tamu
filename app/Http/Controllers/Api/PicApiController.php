<?php

namespace App\Http\Controllers\Api;

use App\Models\follow_ups;
use App\Models\leads;
use App\Models\notifications;
use App\Models\User;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PicApiController extends BaseApiController
{
    /**
     * Status kunjungan yang dianggap "final" (dipakai untuk riwayat).
     */
    private const FINAL_STATUSES = [
        'completed', 'cancelled', 'Selesai', 'Ditolak',
        'Meeting Selesai', 'Dibatalkan', 'dibatalkan',
    ];

    /**
     * Status yang masih dianggap "berjalan" (dipakai untuk dashboard).
     */
    private const ACTIVE_EXCLUDED_STATUSES = [
        'completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan',
    ];

    /**
     * GET /api/v1/pic/dashboard
     *
     * Kunjungan yang sedang berlangsung, menunggu, atau pending follow-up
     * milik PIC yang sedang login.
     */
    public function dashboard(Request $request)
    {
        $filter    = $request->input('filter', 'all');
        $vipFilter = $request->input('vip_status', 'all');
        $keyword   = trim((string) $request->input('keyword', ''));
        $perPage   = (int) $request->input('per_page', 10);
        $today     = Carbon::today();
        $ownerId   = auth()->id();

        $query = visits::with(['guest.category', 'purpose', 'branch'])
            ->where('assigned_to', $ownerId)
            ->whereNotIn('status', self::ACTIVE_EXCLUDED_STATUSES);

        if ($filter === 'today') {
            $query->where(function (Builder $q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            });
        } elseif ($filter === 'upcoming') {
            $query->whereDate('scheduled_at', '>', $today);
        }

        $this->applyKeywordFilter($query, $keyword ?: null);
        $this->applyVipFilter($query, $vipFilter);

        if (Schema::hasColumn('guests', 'is_vip')) {
            $vipCount     = (clone $query)->whereHas('guest', fn ($q) => $q->where('is_vip', true))->count();
            $regularCount = (clone $query)->whereHas('guest', fn ($q) => $q->where('is_vip', false)->orWhereNull('is_vip'))->count();

            // Urutan: tanggal kunjungan terdekat dulu, baru VIP diprioritaskan
            // kalau tanggalnya sama.
            $query->leftJoin('guests', 'visits.guest_id', '=', 'guests.id')
                ->select('visits.*')
                ->orderByRaw('COALESCE(visits.check_in_at, visits.scheduled_at) ASC')
                ->orderByRaw('CASE WHEN guests.is_vip = 1 THEN 0 ELSE 1 END ASC')
                ->orderBy('visits.created_at', 'asc');
        } else {
            $vipCount     = 0;
            $regularCount = (clone $query)->count();

            $query->orderByRaw('COALESCE(check_in_at, scheduled_at) ASC')
                ->orderBy('created_at', 'asc');
        }

        $paginated = $query->paginate($perPage)->appends($request->query());

        $countToday = visits::where('assigned_to', $ownerId)
            ->whereNotIn('status', self::ACTIVE_EXCLUDED_STATUSES)
            ->where(function (Builder $q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            })->count();

        $countUpcoming = visits::where('assigned_to', $ownerId)
            ->whereNotIn('status', self::ACTIVE_EXCLUDED_STATUSES)
            ->whereDate('scheduled_at', '>', $today)
            ->count();

        return $this->responseHasil(200, true, [
            'data'         => collect($paginated->items())->map(fn ($v) => $this->mapVisit($v)),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'total'        => $paginated->total(),
            'vip_count'      => $vipCount,
            'regular_count'  => $regularCount,
            'count_today'    => $countToday,
            'count_upcoming' => $countUpcoming,
            'filter'         => $filter,
            'vip_status'     => $vipFilter,
        ]);
    }

    /**
     * GET /api/v1/pic/followup
     *
     * Pipeline follow-up aktif (new/contacted/negotiation) milik PIC.
     */
    public function followupIndex(Request $request)
    {
        $today   = Carbon::today();
        $filter  = $request->input('filter', 'all');
        $ownerId = auth()->id();

        $query = leads::with(['guest', 'followUps' => fn ($q) => $q->orderBy('created_at', 'desc')])
            ->where('owner_id', $ownerId)
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

        $paginated = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate((int) $request->input('per_page', 10))
            ->appends($request->query());

        $baseQuery = fn () => leads::where('owner_id', $ownerId);

        return $this->responseHasil(200, true, [
            'data'         => collect($paginated->items())->map(fn ($l) => $this->mapLead($l)),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'total'        => $paginated->total(),
            'filter'       => $filter,
            'counts'       => [
                'total_leads'  => $baseQuery()->count(),
                'total_deal'   => $baseQuery()->where('status', 'deal')->count(),
                'overdue'      => $baseQuery()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count(),
                'today'        => $baseQuery()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count(),
                'all_active'   => $baseQuery()->whereNotIn('status', ['deal', 'lost'])->count(),
                'upcoming'     => $baseQuery()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count(),
            ],
        ]);
    }

    /**
     * GET /api/v1/pic/riwayat
     *
     * Riwayat kunjungan PIC yang sudah final.
     */

    /**
     * GET /pic/riwayat  (route name: pic.riwayat)
     *
     * Halaman web (Blade) — beda dari PicApiController::riwayat() yang
     * dipakai app Flutter. Blade ini mengharapkan $visits berisi model
     * Eloquent asli (relasi langsung: guest, purpose, branch, lead,
     * lead.followUps), BUKAN hasil mapVisit() yang sudah diserialisasi.
     */
public function riwayat(Request $request)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ], [
        'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
    ]);
 
    $perPage   = (int) $request->input('per_page', 10);
    $vipFilter = $request->input('vip_status', 'all');
    $keyword   = $request->input('keyword');
 
    $query = Visits::with(['guest', 'purpose', 'branch', 'lead.followUps'])
        ->where('assigned_to', auth()->id())
        ->whereIn('status', self::FINAL_STATUSES);
 
    if ($keyword) {
        $query->whereHas('guest', function (Builder $q) use ($keyword) {
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
            $query->whereHas('guest', fn ($q) => $q->where('is_vip', true));
        } elseif ($vipFilter === 'reguler') {
            $query->whereHas('guest', fn ($q) => $q->where('is_vip', false)->orWhereNull('is_vip'));
        }
    }
 
    $visits = $query->orderBy('check_in_at', 'desc')
        ->paginate($perPage)
        ->appends($request->query());
 
    // ==== Kalau request minta JSON (dipanggil dari Flutter) ====
    if ($request->wantsJson() || $request->expectsJson()) {
        return response()->json([
            'success' => true,
            'data'    => $visits->getCollection()->map(function ($visit) {
                $lastStage = optional($visit->lead?->followUps?->last());
 
                return [
                    'id'                => $visit->id,
                    'token'             => $visit->token ?? ('TRX-' . str_pad($visit->id, 4, '0', STR_PAD_LEFT)),
                    'nama'              => $visit->guest->name ?? '-',
                    'jabatan'           => $visit->guest->position ?? '-',
                    'instansi'          => $visit->guest->company_name ?? '-',
                    'kategori'          => ($visit->guest->is_vip ?? false) ? 'VIP' : 'Reguler',
                    'waktu'             => optional($visit->check_in_at)->translatedFormat('d M Y, H:i \W\I\B'),
                    'tanggal'           => optional($visit->check_in_at)->format('Y-m-d'),
                    'keperluan'         => $visit->purpose->name ?? '-',
                    'tahapPipeline'     => $lastStage->stage ?? ($visit->lead->pipeline_stage ?? '-'),
                    'keteranganStatus'  => $visit->status_label ?? $visit->status,
                    'catatanAwal'       => $visit->lead->notes ?? $visit->notes ?? '-',
                    'riwayatPipeline'   => $visit->lead?->followUps->map(function ($f) {
                        return [
                            'tanggal' => optional($f->created_at)->format('Y-m-d'),
                            'tahap'   => $f->stage,
                            'catatan' => $f->notes,
                        ];
                    })->values() ?? [],
                    'statusAkhir'       => $visit->status,
                ];
            })->values(),
            'meta' => [
                'current_page' => $visits->currentPage(),
                'last_page'    => $visits->lastPage(),
                'per_page'     => $visits->perPage(),
                'total'        => $visits->total(),
            ],
        ]);
    }
 
    // ==== Fallback: request biasa dari browser tetap dapat view Blade ====
    return view('pic.riwayat', [
        'visits'    => $visits,
        'vipFilter' => $vipFilter,
    ]);
}
 
    /**
     * PUT/POST /api/v1/pic/visits/{id}/status
     *
     * Update status kehadiran/kunjungan (konfirmasi / batalkan).
     */
    public function updateStatus(Request $request, $id)
    {
        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->first();

        if (! $visit) {
            return $this->responseHasil(404, false, [], 'Kunjungan tidak ditemukan.');
        }

        $oldStatus = trim($visit->status ?? '');

        $isConfirmed = in_array($request->status, ['confirmed', 'Dikonfirmasi']);
        $newStatus   = $isConfirmed ? 'Dikonfirmasi' : 'Dibatalkan';

        $terminalStatuses = ['meeting selesai', 'selesai', 'dibatalkan', 'completed', 'cancelled'];
        if (in_array(strtolower($oldStatus), $terminalStatuses)) {
            return $this->responseHasil(422, false, [], 'Status sudah akhir dan tidak dapat diubah lagi.');
        }

        if (strtolower($oldStatus) === strtolower($newStatus)) {
            return $this->responseHasil(200, true, ['visit' => $this->mapVisit($visit)], 'Status sudah sesuai, tidak ada perubahan.');
        }

        $visit->status     = $newStatus;
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

        return $this->responseHasil(200, true, ['visit' => $this->mapVisit($visit->fresh(['guest.category', 'purpose', 'branch']))], 'Status berhasil diperbarui.');
    }

    /**
     * POST /api/v1/pic/visits/{id}/complete-meeting
     *
     * Selesaikan pertemuan & catat hasil diskusi. Sekali dicatat, hasil
     * pertemuan tidak bisa diubah lagi (mencegah PIC ganti potential_level
     * setelah lead terbentuk). Kirim notifikasi DB & WhatsApp (Fonnte) ke
     * manager/owner/admin saat lead baru pertama kali terbentuk.
     */
    public function completeMeeting(Request $request, $id)
    {
        $visit = visits::with(['guest', 'lead'])
            ->where('id', $id)
            ->where('assigned_to', auth()->id())
            ->first();

        if (! $visit) {
            return $this->responseHasil(404, false, [], 'Kunjungan tidak ditemukan.');
        }

        $oldStatus = trim($visit->status ?? '');

        if (strtolower($oldStatus) === 'meeting selesai') {
            return $this->responseHasil(422, false, [], 'Hasil pertemuan sudah pernah dicatat dan tidak bisa diubah lagi.');
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
        $finalEstValue    = $request->filled('estimated_value') ? $request->estimated_value : $existingEstValue;

        if ($request->potential_level === 'deal' && (float) $finalEstValue <= 0) {
            return $this->responseHasil(422, false, [], 'Estimasi Nilai Deal wajib diisi (lebih dari Rp 0) sebelum bisa ditandai Deal.');
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
                $this->notifyNewLead($visit, $request->potential_level, $finalEstValue);
            }
        }

        return $this->responseHasil(200, true, [
            'visit' => $this->mapVisit($visit->fresh(['guest.category', 'purpose', 'branch', 'lead.followUps'])),
        ], 'Catatan hasil pertemuan dan data lead berhasil disimpan!');
    }

    /**
     * Kirim notifikasi DB & WhatsApp (Fonnte) ke manager/owner/admin saat
     * lead baru pertama kali terbentuk.
     */
    private function notifyNewLead(visits $visit, string $potentialLevel, $finalEstValue): void
    {
        $managers = User::whereIn('role', ['manager', 'owner', 'admin'])->get();

        $guestName      = $visit->guest->name ?? 'Tamu';
        $companyName    = $visit->guest->company_name ?? 'Instansi';
        $formattedValue = 'Rp ' . number_format($finalEstValue, 0, ',', '.');
        $picName        = auth()->user()->name ?? 'PIC';
        $potensiText    = strtoupper($potentialLevel);

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

        $targetPhones = $managers->pluck('phone')->filter()->unique();

        if (! $targetPhones->isEmpty()) {
            $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
            $waMessage = "*{$title}*\n\n" . $message;

            foreach ($targetPhones as $phone) {
                try {
                    Http::withoutVerifying()
                        ->withHeaders(['Authorization' => $token])
                        ->post('https://api.fonnte.com/send', [
                            'target'  => $phone,
                            'message' => $waMessage,
                        ]);
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim WA ke {$phone}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * POST/PUT /api/v1/pic/leads/{leadId}/follow-up
     *
     * Update tahap pipeline lead (dari modal daftar follow-up).
     */
    public function updateFollowUp(Request $request, $leadId)
    {
        $request->validate([
            'status'          => 'required|in:new,contacted,negotiation,deal,lost',
            'result'          => 'required|string',
            'due_at'          => 'nullable|date',
            'estimated_value' => 'nullable|numeric|min:0',
        ]);

        $lead = leads::where('id', $leadId)
            ->where('owner_id', auth()->id())
            ->first();

        if (! $lead) {
            return $this->responseHasil(404, false, [], 'Lead tidak ditemukan.');
        }

        if ($lead->status === 'deal') {
            return $this->responseHasil(422, false, [], 'Lead ini sudah Deal dan tidak bisa diubah lagi.');
        }

        $finalEstimatedValue = $request->filled('estimated_value')
            ? $request->estimated_value
            : $lead->estimated_value;

        // Pakai perbandingan numerik, BUKAN empty()/truthy check — kalau
        // kolomnya di-cast 'decimal' di model, nilai 0 akan berbentuk string
        // "0.00" yang truthy di PHP dan lolos dari empty().
        if ($request->status === 'deal' && (float) $finalEstimatedValue <= 0) {
            return $this->responseHasil(422, false, [], 'Estimasi Nilai Deal wajib diisi (lebih dari Rp 0) sebelum lead bisa ditandai Deal.');
        }

        $lead->status          = $request->status;
        $lead->follow_up_at    = in_array($request->status, ['deal', 'lost']) ? null : $request->due_at;
        $lead->estimated_value = $finalEstimatedValue;
        $lead->save();

        follow_ups::create([
            'lead_id'         => $lead->id,
            'visit_id'        => $lead->visit_id,
            'assigned_to'     => auth()->id(),
            'due_at'          => $request->due_at ?? now(),
            'result'          => $request->result,
            'status'          => $request->status,
            'estimated_value' => $lead->estimated_value,
        ]);

        return $this->responseHasil(200, true, [
            'lead' => $this->mapLead($lead->fresh(['guest', 'followUps'])),
        ], 'Status pipeline lead berhasil diperbarui!');
    }

    /**
     * GET /api/v1/pic/leads
     *
     * Daftar klien yang sudah Deal (dan lead lain milik PIC).
     */
   public function leadsIndex(Request $request)
{
    $perPage   = (int) $request->input('per_page', 10);
    $today     = Carbon::today();
    $filter    = $request->input('filter', 'active');
    $vipFilter = $request->input('vip_status', 'all');
    $ownerId   = auth()->id();

    $query = leads::with(['guest', 'visit', 'followUps'])
        ->where('owner_id', $ownerId)
        ->where('status', '!=', 'lost');

    switch ($filter) {
        case 'active':
            $query->whereNotIn('status', ['deal', 'lost']);
            break;
        case 'overdue':
            $query->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today);
            break;
        case 'today':
            $query->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today);
            break;
        case 'upcoming':
            $query->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today);
            break;
        case 'deal':
            $query->where('status', 'deal');
            break;
        // 'all' -> tanpa filter tambahan
    }

    if (Schema::hasColumn('guests', 'is_vip')) {
        if ($vipFilter === 'vip') {
            $query->whereHas('guest', fn ($q) => $q->where('is_vip', true));
        } elseif ($vipFilter === 'reguler') {
            $query->whereHas('guest', fn ($q) => $q->where('is_vip', false)->orWhereNull('is_vip'));
        }
    }

    $leads = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
        ->paginate($perPage)
        ->appends($request->query());

    $baseCount = function () use ($ownerId, $vipFilter) {
        $q = leads::where('owner_id', $ownerId)->where('status', '!=', 'lost');
        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $q->whereHas('guest', fn ($gq) => $gq->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $q->whereHas('guest', fn ($gq) => $gq->where('is_vip', false)->orWhereNull('is_vip'));
            }
        }
        return $q;
    };

    $counts = [
        'all'      => $baseCount()->count(),
        'active'   => $baseCount()->whereNotIn('status', ['deal', 'lost'])->count(),
        'overdue'  => $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count(),
        'today'    => $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count(),
        'upcoming' => $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count(),
        'deal'     => $baseCount()->where('status', 'deal')->count(),
    ];

return $this->responseHasil(200, true, [
    'data'         => $leads->getCollection()->map(fn ($lead) => $this->mapLead($lead))->values(),
    'current_page' => $leads->currentPage(),
    'last_page'    => $leads->lastPage(),
    'total'        => $leads->total(),
    'filter'       => $filter,
    'vip_status'   => $vipFilter,
    'counts'       => $counts,
]);
}

// POST /api/pic/leads/{id}/follow-up
public function storeLeadFollowUp(Request $request, $id)
{
    $ownerId = auth()->id();
    $lead = leads::where('owner_id', $ownerId)->findOrFail($id);

    $validated = $request->validate([
        'status'          => 'required|in:baru,dihubungi,negosiasi,deal,lost',
        'result'          => 'nullable|string',
        'estimated_value' => 'nullable|numeric',
        'due_at'          => 'nullable|date',
    ]);

    $lead->status = $validated['status'];
    if (array_key_exists('estimated_value', $validated) && $validated['estimated_value'] !== null) {
        $lead->estimated_value = $validated['estimated_value'];
    }
    if (array_key_exists('due_at', $validated) && $validated['due_at'] !== null) {
        $lead->follow_up_at = $validated['due_at'];
    }
    $lead->save();

    if (!empty($validated['result'])) {
        $lead->followUps()->create([
            'result'          => $validated['result'],
            'status'          => $validated['status'],
            'due_at'          => $validated['due_at'] ?? null,
            'estimated_value' => $validated['estimated_value'] ?? null,
        ]);
    }

return $this->responseHasil(200, true, [
    'lead' => $this->mapLead($lead->fresh(['guest', 'visit', 'followUps'])),
], 'Tahap pipeline berhasil diperbarui');
}
    /**
     * POST /api/v1/pic/visits/{id}/start-meeting
     */
    public function startMeeting($id)
    {
        $visit = visits::find($id);

        if (! $visit) {
            return $this->responseHasil(404, false, [], 'Kunjungan tidak ditemukan.');
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
            'status'            => $newStatus,
            'meeting_start_at'  => $visit->meeting_start_at ?? now(),
            'updated_by'        => auth()->id(),
        ]);

        return $this->responseHasil(200, true, [
            'visit' => $this->mapVisit($visit->fresh(['guest.category', 'purpose', 'branch'])),
        ], 'Pertemuan dimulai. Silakan lakukan diskusi dengan tamu.');
    }

    /**
     * Terapkan filter status VIP ('vip' | 'reguler' | 'all') ke query guest.
     * No-op jika kolom is_vip belum ada di tabel guests.
     */
    private function applyVipFilter(Builder $query, string $vipFilter): void
    {
        if (! Schema::hasColumn('guests', 'is_vip')) {
            return;
        }

        if ($vipFilter === 'vip') {
            $query->whereHas('guest', fn ($q) => $q->where('is_vip', true));
        } elseif ($vipFilter === 'reguler') {
            $query->whereHas('guest', fn ($q) => $q->where('is_vip', false)->orWhereNull('is_vip'));
        }
    }

    /**
     * Terapkan filter keyword pada nama/perusahaan tamu.
     */
    private function applyKeywordFilter(Builder $query, ?string $keyword): void
    {
        if (! $keyword) {
            return;
        }

        $query->whereHas('guest', function (Builder $q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('company_name', 'like', "%{$keyword}%");
        });
    }

    /**
     * Bentuk payload JSON untuk satu kunjungan (visit), konsisten dengan
     * format yang dipakai ManagerApiController::mapVisit().
     */
    private function mapVisit(visits $v): array
    {
        return [
            'id'              => $v->id,
            'visit_code'      => $v->visit_code ?? ('VST-' . str_pad($v->id, 4, '0', STR_PAD_LEFT)),
            'guest_name'      => optional($v->guest)->name,
            'guest_position'  => optional($v->guest)->position,
            'company_name'    => optional($v->guest)->company_name,
            'is_vip'          => (bool) optional($v->guest)->is_vip,
            'category_name'   => optional(optional($v->guest)->category)->name,
            'category_color'  => optional(optional($v->guest)->category)->color,
            'purpose_name'    => optional($v->purpose)->name,
            'branch_id'       => $v->branch_id,
            'branch_name'     => optional($v->branch)->name,
            'status'          => $v->status,
            'scheduled_at'    => $v->scheduled_at,
            'check_in_at'     => $v->check_in_at,
            'check_out_at'    => $v->check_out_at,
            'meeting_start_at' => $v->meeting_start_at,
            'notes'           => $v->notes,
            'meeting_result'  => $v->meeting_result,
            'potential_level' => $v->potential_level ?? null,
            'lead_status'     => optional($v->lead)->status,
            'estimated_value' => optional($v->lead)->estimated_value,
            'follow_up_at'    => optional($v->lead)->follow_up_at ?? $v->follow_up_at,
            'follow_ups'      => $v->relationLoaded('lead') && $v->lead
                ? $v->lead->followUps->sortByDesc('created_at')->values()->map(fn ($f) => [
                    'id'              => $f->id,
                    'result'          => $f->result ?? null,
                    'status'          => $f->status ?? null,
                    'due_at'          => $f->due_at ?? null,
                    'estimated_value' => $f->estimated_value ?? null,
                    'created_at'      => $f->created_at,
                ])
                : [],
        ];
    }

    /**
     * Bentuk payload JSON untuk satu lead, konsisten dengan format yang
     * dipakai ManagerApiController::mapLead().
     */
    private function mapLead(leads $l): array
    {
        return [
            'id'              => $l->id,
            'visit_code'      => optional($l->visit)->visit_code
                ?? ('VST-' . str_pad(optional($l->visit)->id ?? $l->id, 4, '0', STR_PAD_LEFT)),
            'guest_name'      => optional($l->guest)->name,
            'guest_position'  => optional($l->guest)->position,
            'company_name'    => optional($l->guest)->company_name,
            'is_vip'          => (bool) optional($l->guest)->is_vip,
            'status'          => $l->status,
            'potential_level' => $l->potential_level ?? null,
            'estimated_value' => $l->estimated_value ?? null,
            'follow_up_at'    => $l->follow_up_at,
            'notes'           => optional($l->visit)->notes,
            'meeting_result'  => optional($l->visit)->meeting_result,
            'follow_ups'      => $l->followUps->map(fn ($f) => [
                'id'              => $f->id,
                'result'          => $f->result ?? null,
                'status'          => $f->status ?? null,
                'due_at'          => $f->due_at ?? null,
                'estimated_value' => $f->estimated_value ?? null,
                'created_at'      => $f->created_at,
            ]),
        ];
    }
}