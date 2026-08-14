<?php

namespace App\Http\Controllers\Api;

use App\Models\leads;
use App\Models\visits;
// use App\Models\notifications;
use App\Models\branches;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ManagerApiController extends BaseApiController
{
    /**
     * Status kunjungan yang dianggap "final" (sudah selesai / dibatalkan).
     */
    private const FINAL_STATUSES = [
        'completed', 'Selesai', 'Meeting Selesai',
        'cancelled', 'Dibatalkan', 'Ditolak',
    ];

    /**
     * Versi lowercase dari FINAL_STATUSES, dipakai untuk perbandingan
     * case-insensitive (mis. di laporan()).
     */
    private const FINAL_STATUSES_LOWER = [
        'completed', 'selesai', 'meeting selesai',
        'cancelled', 'dibatalkan', 'ditolak',
    ];

    /**
     * GET /manager/dashboard
     *
     * Menampilkan kunjungan pada tanggal tertentu (default: hari ini),
     * beserta jumlah lead yang deal bulan berjalan.
     *
     * Catatan: sengaja TIDAK memakai mapVisit() di sini — data dikembalikan
     * sebagai Eloquent collection dengan relasi nested (guest.category,
     * assignedUser, purpose) agar frontend bisa mengakses relasi apa adanya.
     */
    public function dashboard(Request $request)
    {
        $selectedDate       = $request->query('date', Carbon::today()->format('Y-m-d'));
        $selectedDateCarbon = Carbon::parse($selectedDate);
        $vipFilter          = $request->input('vip_status', 'all');

        $query = visits::with(['guest.category', 'assignedUser', 'purpose'])
            ->where(function (Builder $q) use ($selectedDateCarbon) {
                $q->where(function (Builder $q2) use ($selectedDateCarbon) {
                    $q2->whereNotNull('scheduled_at')->whereDate('scheduled_at', $selectedDateCarbon);
                })->orWhere(function (Builder $q3) use ($selectedDateCarbon) {
                    $q3->whereNull('scheduled_at')->whereDate('check_in_at', $selectedDateCarbon);
                });
            });

        $this->applyVipFilter($query, $vipFilter);

        $visits = $query->orderBy('scheduled_at')->get();

        $leadDealsCount = leads::where('status', 'deal')
            ->whereHas('visit', fn ( $q) => $q->whereMonth('scheduled_at', now()->month)
                ->whereYear('scheduled_at', now()->year))
            ->count();

        // $notifications = notifications::where('user_id', auth()->id())
        //     ->latest()->limit(10)->get(['id', 'title', 'message', 'is_read', 'created_at']);

        return $this->responseHasil(200, true, [
            'visits'           => $visits,
            'total_today'      => $visits->count(),
            'lead_deals_count' => $leadDealsCount,
            // 'notifications'    => $notifications,
            'selected_date'    => $selectedDate,
            'vip_status'       => $vipFilter,
        ]);
    }

    /**
     * GET /manager/kunjungan
     *
     * Daftar kunjungan yang sudah final (selesai/dibatalkan), dengan filter
     * keyword, rentang tanggal check-in, dan status VIP.
     */
    public function kunjungan(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $vipFilter = $request->input('vip_status', 'all');

        $query = visits::with(['guest.category', 'assignedUser', 'purpose', 'lead.followUps'])
            ->whereIn('status', self::FINAL_STATUSES);

        $this->applyKeywordFilter($query, $request->input('keyword'));

        if ($request->filled('start_date')) {
            $query->whereDate('check_in_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_at', '<=', $request->end_date);
        }

        $this->applyVipFilter($query, $vipFilter);

        $paginated = $query->orderBy('check_in_at', 'desc')
            ->paginate((int) $request->input('per_page', 10));

        return $this->responseHasil(200, true, [
            'data'         => collect($paginated->items())->map(fn ($v) => $this->mapVisit($v)),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'total'        => $paginated->total(),
        ]);
    }

    /**
     * GET /manager/leads-pipeline
     *
     * Pipeline lead dengan filter status (active/overdue/today/upcoming/deal/lost),
     * keyword, dan status VIP, plus ringkasan jumlah per kategori.
     */
    public function leadsPipeline(Request $request)
    {
        $today     = Carbon::today();
        $filter    = $request->input('filter', 'active');
        $vipFilter = $request->input('vip_status', 'all');

        $query = leads::with([
            'guest',
            'visit',
            'owner',
            'followUps' => fn ($q) => $q->orderBy('created_at', 'desc'),
        ]);

        $this->applyLeadStatusFilter($query, $filter, $today);
        $this->applyKeywordFilter($query, $request->input('keyword'), ownerRelation: true);
        $this->applyVipFilter($query, $vipFilter);

        $paginated = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate((int) $request->input('per_page', 10));

        return $this->responseHasil(200, true, [
            'data'         => collect($paginated->items())->map(fn ($l) => $this->mapLead($l)),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'total'        => $paginated->total(),
            'counts'       => $this->leadStatusCounts($today),
        ]);
    }

    /**
     * GET /manager/laporan
     *
     * Laporan bulanan kunjungan final, dengan filter kategori (vip/reguler),
     * cabang, dan PIC, plus statistik ringkas (total, deal, VIP, conversion,
     * rata-rata durasi kunjungan).
     */
    public function laporan(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
            ->where(function (Builder $q) use ($month, $year) {
                $q->where(fn (Builder $q2) => $q2->whereMonth('check_in_at', $month)->whereYear('check_in_at', $year))
                  ->orWhere(fn (Builder $q2) => $q2->whereNull('check_in_at')
                      ->whereMonth('scheduled_at', $month)->whereYear('scheduled_at', $year));
            })
            ->whereIn(DB::raw('LOWER(TRIM(status))'), self::FINAL_STATUSES_LOWER);

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($category === 'vip') {
                $baseQuery->whereHas('guest', fn ( $q) => $q->where('is_vip', true));
            } elseif ($category === 'reguler') {
                $baseQuery->whereHas('guest', fn ( $q) => $q->where('is_vip', false)->orWhereNull('is_vip'));
            }
        }
        if ($branchId !== '') {
            $baseQuery->where('branch_id', $branchId);
        }
        if ($picId !== '') {
            $baseQuery->where('assigned_to', $picId);
        }

        $totalKunjungan = (clone $baseQuery)->count();
        $totalDeal      = (clone $baseQuery)->whereHas('lead', fn ( $q) => $q->where('status', 'deal'))->count();
        $totalVip       = Schema::hasColumn('guests', 'is_vip')
            ? (clone $baseQuery)->whereHas('guest', fn ( $q) => $q->where('is_vip', true))->count()
            : 0;
        $conversionRate = $totalKunjungan > 0 ? round(($totalDeal / $totalKunjungan) * 100, 1) : 0;

        $avgDuration = (clone $baseQuery)
            ->whereNotNull('check_in_at')->whereNotNull('check_out_at')
            ->get(['check_in_at', 'check_out_at'])
            ->avg(fn ($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->check_out_at))) ?? 0;

        $paginated = $baseQuery->orderBy('check_in_at', 'desc')
            ->paginate((int) $request->input('per_page', 15));

        return $this->responseHasil(200, true, [
            'data'         => collect($paginated->items())->map(fn ($v) => $this->mapVisit($v)),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'total'        => $paginated->total(),
            'stats'        => [
                'total_kunjungan' => $totalKunjungan,
                'total_deal'      => $totalDeal,
                'total_vip'       => $totalVip,
                'conversion_rate' => $conversionRate,
                'avg_duration'    => round($avgDuration),
            ],
            'branches'  => branches::orderBy('name')->get(['id', 'name']),
            'pic_users' => users::whereIn('id', visits::whereNotNull('assigned_to')->distinct()->pluck('assigned_to'))
                ->orderBy('name')->get(['id', 'name']),
        ]);
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
            $query->whereHas('guest', fn ( $q) => $q->where('is_vip', true));
        } elseif ($vipFilter === 'reguler') {
            $query->whereHas('guest', fn ( $q) => $q->where('is_vip', false)->orWhereNull('is_vip'));
        }
    }

    /**
     * Terapkan filter keyword pada nama/perusahaan tamu, dan nama
     * assignedUser (visits) atau owner (leads).
     */
    private function applyKeywordFilter(Builder $query, ?string $keyword, bool $ownerRelation = false): void
    {
        if (! $keyword) {
            return;
        }

        $relation = $ownerRelation ? 'owner' : 'assignedUser';

        $query->where(function (Builder $q) use ($keyword, $relation) {
            $q->whereHas('guest', fn ( $q2) => $q2->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%"))
              ->orWhereHas($relation, fn ( $q3) => $q3->where('name', 'like', "%{$keyword}%"));
        });
    }

    /**
     * Terapkan filter status pada query leads sesuai tab
     * (active/overdue/today/upcoming/deal/lost).
     */
    private function applyLeadStatusFilter(Builder $query, string $filter, Carbon $today): void
    {
        match ($filter) {
            'active'   => $query->whereNotIn('status', ['deal', 'lost']),
            'overdue'  => $query->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today),
            'today'    => $query->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today),
            'upcoming' => $query->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today),
            'deal'     => $query->where('status', 'deal'),
            'lost'     => $query->where('status', 'lost'),
            default    => null,
        };
    }

    /**
     * Ringkasan jumlah lead per kategori tab, dipakai sebagai badge counter.
     *
     * @return array<string, int>
     */
    private function leadStatusCounts(Carbon $today): array
    {
        return [
            'all'      => leads::count(),
            'active'   => leads::whereNotIn('status', ['deal', 'lost'])->count(),
            'overdue'  => leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count(),
            'today'    => leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count(),
            'upcoming' => leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count(),
            'deal'     => leads::where('status', 'deal')->count(),
            'lost'     => leads::where('status', 'lost')->count(),
        ];
    }

    

private function mapLead($l): array
{
    return [
        'id'              => $l->id,
        'visit_code'      => optional($l->visit)->visit_code
            ?? ('VST-' . str_pad(optional($l->visit)->id ?? $l->id, 4, '0', STR_PAD_LEFT)),
        'guest_name'      => optional($l->guest)->name,
        'guest_position'  => optional($l->guest)->position,
        'company_name'    => optional($l->guest)->company_name,
        'is_vip'          => (bool) optional($l->guest)->is_vip,
        'owner_id'        => optional($l->owner)->id,
        'owner_name'      => optional($l->owner)->name,
        'status'          => $l->status,
        'potential_level' => $l->potential_level ?? null,
        'estimated_value' => $l->estimated_value ?? null,
        'follow_up_at'    => $l->follow_up_at,
        'notes'           => optional($l->visit)->notes,        // ⬅️ BARU: catatan awal kunjungan
        'meeting_result'  => optional($l->visit)->meeting_result,
        'follow_ups'      => $l->followUps->map(fn ($f) => [
            'id'              => $f->id,
            'result'          => $f->result ?? null,
            'status'          => $f->status ?? null,
            'due_at'          => $f->due_at ?? null,
            'estimated_value' => $f->estimated_value ?? null,   // ⬅️ BARU: nilai estimasi per update
            'created_at'      => $f->created_at,
        ]),
    ];
}

private function mapVisit($v): array
{
    return [
        'id'             => $v->id,
        'visit_code'     => $v->visit_code ?? ('VST-' . str_pad($v->id, 4, '0', STR_PAD_LEFT)),
        'guest_name'     => optional($v->guest)->name,
        'guest_position' => optional($v->guest)->position,
        'company_name'   => optional($v->guest)->company_name,
        'is_vip'         => (bool) optional($v->guest)->is_vip,
        'category_name'  => optional(optional($v->guest)->category)->name,
        'assigned_to'    => optional($v->assignedUser)->id,
        'assigned_name'  => optional($v->assignedUser)->name,
        'purpose_name'   => optional($v->purpose)->name,
        'source_name'    => optional($v->source)->name,
        'branch_id'      => $v->branch_id,
        'branch_name'    => optional($v->branch)->name,
        'products'       => $v->relationLoaded('products')
            ? $v->products->pluck('name')
            : [],
        'status'         => $v->status,
        'scheduled_at'   => $v->scheduled_at,
        'check_in_at'    => $v->check_in_at,
        'check_out_at'   => $v->check_out_at,
        'lead_status'    => optional($v->lead)->status,
        'follow_ups'     => optional($v->lead)->relationLoaded('followUps')
            ? $v->lead->followUps->map(fn ($f) => [
                'id'         => $f->id,
                'result'     => $f->result ?? null,
                'status'     => $f->status ?? null,
                'due_at'     => $f->due_at ?? null,
                'created_at' => $f->created_at,
            ])
            : [],
    ];
}
}