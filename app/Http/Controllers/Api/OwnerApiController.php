<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\activity_logs;
use App\Models\branches;
use App\Models\guests;
use App\Models\leads;
use App\Models\products;
use App\Models\users;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use URL;

class OwnerApiController extends Controller
{
    private const FINAL_STATUSES = [
        'completed',
        'Selesai',
        'Meeting Selesai',
        'cancelled',
        'Dibatalkan',
        'Ditolak',
    ];

    private const COMPLETED_STATUSES = ['completed', 'Selesai', 'Meeting Selesai'];

    /**
     * GET /api/v1/owner/dashboard
     * Query params: status, pic_id, keyword, lead_only
     */
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        $statusFilter = trim((string) $request->input('status', ''));
        $picFilter    = trim((string) $request->input('pic_id', ''));
        $keyword      = trim((string) $request->input('keyword', ''));
        $leadOnly     = $request->boolean('lead_only');

        $baseTodayQuery = fn() => visits::where(function ($q) use ($today) {
            $q->whereDate('scheduled_at', $today)
                ->orWhereDate('check_in_at', $today);
        });

        $visitsQuery = visits::with(['guest.category', 'purpose', 'assignedUser', 'lead.followUps'])
            ->where(function ($q) use ($today) {
                $q->whereDate('scheduled_at', $today)
                    ->orWhereDate('check_in_at', $today);
            });

        if ($leadOnly) {
            $visitsQuery->whereIn(DB::raw('LOWER(TRIM(potential_level))'), ['hot', 'warm']);
        }
        if ($statusFilter !== '') {
            $visitsQuery->whereRaw('LOWER(TRIM(status)) = ?', [strtolower($statusFilter)]);
        }
        if ($picFilter !== '') {
            $visitsQuery->where('assigned_to', $picFilter);
        }
        if ($keyword !== '') {
            $visitsQuery->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        $visits = $visitsQuery->orderBy('scheduled_at', 'desc')->get();

        $totalTamuHariIni = $baseTodayQuery()->count();

        $sedangMenunggu = $baseTodayQuery()
            ->whereIn(DB::raw('LOWER(TRIM(status))'), ['menunggu', 'waiting'])
            ->count();

        $sedangBertemu = $baseTodayQuery()
            ->whereIn(DB::raw('LOWER(TRIM(status))'), ['sedang bertemu', 'confirmed', 'dikonfirmasi'])
            ->count();

        $pertemuanSelesai = $baseTodayQuery()
            ->whereIn(DB::raw('LOWER(TRIM(status))'), ['completed', 'selesai', 'meeting selesai'])
            ->count();

        $menjadiLeadHariIni = $baseTodayQuery()
            ->whereIn(DB::raw('LOWER(TRIM(potential_level))'), ['hot', 'warm'])
            ->count();

        $topProduct = DB::table('visit_products')
            ->join('products', 'products.id', '=', 'visit_products.product_id')
            ->select('products.name', DB::raw('count(*) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->first();

        $terjadwalHariIni = $baseTodayQuery()
            ->whereRaw('LOWER(TRIM(status)) = ?', ['terjadwal'])
            ->count();

        $totalGuests = guests::count();

        $topCategory = DB::table('guests')
            ->join('guest_categories', 'guest_categories.id', '=', 'guests.guest_category_id')
            ->select('guest_categories.name', DB::raw('count(*) as total'))
            ->groupBy('guest_categories.id', 'guest_categories.name')
            ->orderByDesc('total')
            ->first();

        $topCategoryPercentage = ($topCategory && $totalGuests > 0)
            ? round(($topCategory->total / $totalGuests) * 100)
            : 0;

        $waitTimes = visits::whereNotNull('check_in_at')
            ->whereNotNull('meeting_start_at')
            ->get()
            ->map(fn($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->meeting_start_at)));

        $avgWaitMinutes = $waitTimes->count() > 0 ? round($waitTimes->avg()) : 0;

        $totalVisitsAll = visits::count();
        $completedVisits = visits::whereIn(DB::raw('LOWER(TRIM(status))'), ['completed', 'selesai', 'meeting selesai'])->count();
        $serviceRate = $totalVisitsAll > 0 ? round(($completedVisits / $totalVisitsAll) * 100) : 0;

        $totalLeadsAll = leads::count();
        $conversionRate = $totalVisitsAll > 0 ? round(($totalLeadsAll / $totalVisitsAll) * 100) : 0;

        $recentActivities = DB::table('visit_status_logs')
            ->join('visits', 'visits.id', '=', 'visit_status_logs.visit_id')
            ->join('guests', 'guests.id', '=', 'visits.guest_id')
            ->select(
                'guests.name as guest_name',
                'guests.company_name',
                'visit_status_logs.new_status',
                'visit_status_logs.changed_at'
            )
            ->orderByDesc('visit_status_logs.changed_at')
            ->take(5)
            ->get();

        $statusOptions = visits::whereNotNull('status')
            ->get()
            ->pluck('status')
            ->map(fn($s) => trim($s))
            ->filter(fn($s) => $s !== '')
            ->unique()
            ->sort()
            ->values();

        $picOptions = users::where('role', 'pic')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_tamu_hari_ini'  => $totalTamuHariIni,
                    'sedang_menunggu'      => $sedangMenunggu,
                    'sedang_bertemu'       => $sedangBertemu,
                    'pertemuan_selesai'    => $pertemuanSelesai,
                    'terjadwal_hari_ini'   => $terjadwalHariIni,
                    'menjadi_lead_hari_ini' => $menjadiLeadHariIni,
                    'avg_wait_minutes'     => $avgWaitMinutes,
                    'service_rate'         => $serviceRate,
                    'conversion_rate'      => $conversionRate,
                ],
                'top_product'  => $topProduct,
                'top_category' => [
                    'data'       => $topCategory,
                    'percentage' => $topCategoryPercentage,
                ],
                'recent_activities' => $recentActivities,
                'visits' => $visits->map(fn($v) => $this->mapVisitForOwnerDashboard($v)),
                'filters' => [
                    'status_options' => $statusOptions,
                    'pic_options'    => $picOptions,
                    'status'         => $statusFilter,
                    'pic_id'         => $picFilter,
                    'keyword'        => $keyword,
                    'lead_only'      => $leadOnly,
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/owner/activity-log
     * Query params: keyword, per_page
     */
    public function activityLog(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = DB::table('visit_status_logs')
            ->join('visits', 'visits.id', '=', 'visit_status_logs.visit_id')
            ->join('guests', 'guests.id', '=', 'visits.guest_id')
            ->select(
                'guests.name as guest_name',
                'guests.company_name',
                'visit_status_logs.new_status',
                'visit_status_logs.changed_at'
            );

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('guests.name', 'like', "%{$keyword}%")
                    ->orWhere('guests.company_name', 'like', "%{$keyword}%");
            });
        }

        $activities = $query->orderByDesc('visit_status_logs.changed_at')
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json([
            'success' => true,
            'data'    => $activities->items(),
            'meta'    => $this->paginationMeta($activities),
            'filters' => ['keyword' => $keyword, 'per_page' => $perPage],
        ]);
    }

    /**
     * GET /api/v1/owner/kunjungan
     * Query params: start_date, end_date, vip_status, keyword, per_page
     */
    public function kunjungan(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
        ]);

        $vipFilter = $request->input('vip_status', 'all');

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = visits::with(['guest.category', 'assignedUser', 'purpose', 'lead.followUps'])
            ->whereIn('status', self::FINAL_STATUSES);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%{$keyword}%")
                        ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('assignedUser', function ($q3) use ($keyword) {
                    $q3->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('check_in_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_at', '<=', $request->end_date);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'is_vip')) {
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
            'data'    => $visits->items(),
            'meta'    => $this->paginationMeta($visits),
            'filters' => [
                'vip_status' => $vipFilter,
                'start_date' => $request->input('start_date'),
                'end_date'   => $request->input('end_date'),
                'keyword'    => $request->input('keyword'),
                'per_page'   => $perPage,
            ],
        ]);
    }

    /**
     * GET /api/v1/owner/leads
     * Query params: filter, vip_status, keyword, per_page
     */
    public function leads(Request $request)
    {
        $today  = Carbon::today();
        $filter = $request->input('filter', 'active');
        $vipFilter = $request->input('vip_status', 'all');
        $keyword = trim((string) $request->input('keyword', ''));

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = leads::with([
            'guest',
            'visit',
            'owner',
            'followUps' => fn($q) => $q->orderBy('created_at', 'desc'),
        ]);

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
            case 'lost':
                $query->where('status', 'lost');
                break;
        }

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%{$keyword}%")
                        ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('owner', function ($q3) use ($keyword) {
                    $q3->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'is_vip')) {
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

        // Base count yang ikut menghormati filter vip_status & keyword yang sedang aktif,
        // supaya badge jumlah di tiap chip kategori selalu sesuai dengan data yang
        // benar-benar sedang ditampilkan — bukan total keseluruhan tanpa filter.
        $baseCount = function () use ($vipFilter, $keyword) {
            $q = leads::query();

            if ($keyword !== '') {
                $q->where(function ($qq) use ($keyword) {
                    $qq->whereHas('guest', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%{$keyword}%")
                            ->orWhere('company_name', 'like', "%{$keyword}%");
                    })->orWhereHas('owner', function ($q3) use ($keyword) {
                        $q3->where('name', 'like', "%{$keyword}%");
                    });
                });
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'is_vip')) {
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

        return response()->json([
            'success' => true,
            'data'    => collect($leads->items())->map(fn($l) => $this->mapLead($l)),
            'meta'    => $this->paginationMeta($leads),
            'counts'  => [
                'all'      => $baseCount()->count(),
                'active'   => $baseCount()->whereNotIn('status', ['deal', 'lost'])->count(),
                'overdue'  => $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count(),
                'today'    => $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count(),
                'upcoming' => $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count(),
                'deal'     => $baseCount()->where('status', 'deal')->count(),
                'lost'     => $baseCount()->where('status', 'lost')->count(),
            ],
            'filters' => [
                'filter'     => $filter,
                'vip_status' => $vipFilter,
                'keyword'    => $request->input('keyword'),
                'per_page'   => $perPage,
            ],
        ]);
    }

    /**
     * GET /api/v1/owner/laporan
     * Query params: month, year, category, branch_id, pic_id, per_page
     */
    public function laporan(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
            ->where(function ($q) use ($month, $year) {
                $q->where(function ($q2) use ($month, $year) {
                    $q2->whereMonth('check_in_at', $month)->whereYear('check_in_at', $year);
                })->orWhere(function ($q2) use ($month, $year) {
                    $q2->whereNull('check_in_at')
                        ->whereMonth('scheduled_at', $month)
                        ->whereYear('scheduled_at', $year);
                });
            })
            ->whereIn(DB::raw('LOWER(TRIM(status))'), [
                'completed',
                'selesai',
                'meeting selesai',
                'cancelled',
                'dibatalkan',
                'ditolak',
            ]);

        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'is_vip')) {
            if ($category === 'vip') {
                $baseQuery->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($category === 'reguler') {
                $baseQuery->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        if ($branchId !== '') {
            $baseQuery->where('branch_id', $branchId);
        }

        if ($picId !== '') {
            $baseQuery->where('assigned_to', $picId);
        }

        $totalKunjungan = (clone $baseQuery)->count();
        $totalDeal = (clone $baseQuery)->whereHas('lead', fn($q) => $q->where('status', 'deal'))->count();
        $totalVip = \Illuminate\Support\Facades\Schema::hasColumn('guests', 'is_vip')
            ? (clone $baseQuery)->whereHas('guest', fn($q) => $q->where('is_vip', true))->count()
            : 0;

        $conversionRate = $totalKunjungan > 0 ? round(($totalDeal / $totalKunjungan) * 100, 1) : 0;

        $avgDuration = (clone $baseQuery)
            ->whereNotNull('check_in_at')
            ->whereNotNull('check_out_at')
            ->get(['check_in_at', 'check_out_at'])
            ->avg(fn($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->check_out_at)));
        $avgDuration = $avgDuration ?? 0;

        $visits = $baseQuery->orderBy('check_in_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        $branches = branches::orderBy('name')->get();
        $picUsers = users::whereIn(
            'id',
            visits::whereNotNull('assigned_to')->distinct()->pluck('assigned_to')
        )->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => collect($visits->items())->map(fn($v) => $this->mapVisitLaporan($v)),
            'meta'    => $this->paginationMeta($visits),
            'summary' => [
                'total_kunjungan' => $totalKunjungan,
                'total_deal'      => $totalDeal,
                'total_vip'       => $totalVip,
                'conversion_rate' => $conversionRate,
                'avg_duration'    => $avgDuration,
            ],
            'options' => [
                'branches'  => $branches,
                'pic_users' => $picUsers,
            ],
            'filters' => [
                'month'     => $month,
                'year'      => $year,
                'category'  => $category,
                'branch_id' => $branchId,
                'pic_id'    => $picId,
                'per_page'  => $perPage,
            ],
        ]);
    }

    /**
     * GET /api/v1/owner/laporan/export-excel
     * Sama seperti versi web: generate file lalu simpan ke storage,
     * balikin URL supaya Flutter tinggal download/buka linknya.
     * (Streaming file binary sebagai response JSON tidak praktis di Flutter.)
     */
    public function exportExcel(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $branchName = $branchId !== '' ? optional(\App\Models\branches::find($branchId))->name : null;
        $picName    = $picId !== '' ? optional(users::find($picId))->name : null;

        $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '-' . time() . '.xlsx';
        $path = 'exports/' . $fileName;

        \Maatwebsite\Excel\Facades\Excel::store(
            new \App\Exports\KunjunganLaporanExport(
                $month,
                $year,
                $category,
                $branchId,
                $picId,
                $months[$month] ?? (string) $month,
                $branchName,
                $picName
            ),
            $path,
            'public'
        );

        $downloadUrl = URL::temporarySignedRoute(
            'laporan.download',
            now()->addMinutes(10),
            ['filename' => $fileName]
        );

        return response()->json([
            'success'   => true,
            'file_url'  => $downloadUrl,
            'file_name' => $fileName,
        ]);
    }

    /**
     * GET /api/v1/owner/laporan/export-pdf
     * Sama pendekatannya: simpan ke storage, balikin URL.
     */
    public function exportPdf(Request $request)
    {
        $month    = (int) $request->input('month', now()->month);
        $year     = (int) $request->input('year', now()->year);
        $category = (string) $request->input('category', '');
        $branchId = (string) $request->input('branch_id', '');
        $picId    = (string) $request->input('pic_id', '');

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
            ->where(function ($q) use ($month, $year) {
                $q->where(function ($q2) use ($month, $year) {
                    $q2->whereMonth('check_in_at', $month)->whereYear('check_in_at', $year);
                })->orWhere(function ($q2) use ($month, $year) {
                    $q2->whereNull('check_in_at')
                        ->whereMonth('scheduled_at', $month)
                        ->whereYear('scheduled_at', $year);
                });
            })
            ->whereIn(DB::raw('LOWER(TRIM(status))'), [
                'completed',
                'selesai',
                'meeting selesai',
                'cancelled',
                'dibatalkan',
                'ditolak',
            ]);

        if (\Illuminate\Support\Facades\Schema::hasColumn('guests', 'is_vip')) {
            if ($category === 'vip') {
                $baseQuery->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($category === 'reguler') {
                $baseQuery->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        if ($branchId !== '') {
            $baseQuery->where('branch_id', $branchId);
        }
        if ($picId !== '') {
            $baseQuery->where('assigned_to', $picId);
        }

        $visits = $baseQuery->orderBy('check_in_at', 'asc')->get();

        $totalKunjungan = $visits->count();
        $totalDeal = $visits->filter(fn($v) => optional($v->lead)->status === 'deal')->count();
        $totalVip = $visits->filter(fn($v) => isset($v->guest) && $v->guest->is_vip)->count();
        $conversionRate = $totalKunjungan > 0 ? round(($totalDeal / $totalKunjungan) * 100, 1) : 0;

        $topSource = $visits->filter(fn($v) => $v->source)
            ->groupBy(fn($v) => $v->source->name)
            ->map->count()
            ->sortDesc();
        $topSourceName = $topSource->keys()->first();
        $topSourceCount = $topSource->first();

        $topPic = $visits->filter(fn($v) => $v->assignedUser)
            ->groupBy(fn($v) => $v->assignedUser->name)
            ->map->count()
            ->sortDesc();
        $topPicName = $topPic->keys()->first();
        $topPicCount = $topPic->first();

        $durations = $visits->filter(fn($v) => $v->check_in_at && $v->check_out_at)
            ->map(fn($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->check_out_at)));
        $avgDuration = $durations->count() > 0 ? round($durations->avg()) : null;

        $waitTimes = $visits->filter(fn($v) => $v->check_in_at && $v->meeting_start_at)
            ->map(fn($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->meeting_start_at)));
        $avgWaitMinutes = $waitTimes->count() > 0 ? round($waitTimes->avg()) : null;

        $branchName = $branchId !== '' ? optional(\App\Models\branches::find($branchId))->name : null;
        $picName = $picId !== '' ? optional(users::find($picId))->name : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('manager.laporan_pdf', [
            'visits'          => $visits,
            'monthLabel'      => $months[$month] ?? $month,
            'year'            => $year,
            'category'        => $category,
            'branchName'      => $branchName,
            'picName'         => $picName,
            'totalKunjungan'  => $totalKunjungan,
            'totalDeal'       => $totalDeal,
            'totalVip'        => $totalVip,
            'conversionRate'  => $conversionRate,
            'topSourceName'   => $topSourceName,
            'topSourceCount'  => $topSourceCount,
            'topPicName'      => $topPicName,
            'topPicCount'     => $topPicCount,
            'avgWaitMinutes'  => $avgWaitMinutes,
            'generatedBy'     => auth()->user()->name ?? '-',
            'generatedAt'     => now(),
            'avgDuration'     => $avgDuration,
        ])->setPaper('a4', 'landscape');

        $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '-' . time() . '.pdf';
        $path = 'exports/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $downloadUrl = URL::temporarySignedRoute(
            'laporan.download',
            now()->addMinutes(10),
            ['filename' => $fileName]
        );

        return response()->json([
            'success'   => true,
            'file_url'  => $downloadUrl,
            'file_name' => $fileName,
        ]);
    }

    public function downloadLaporan(Request $request, string $filename)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Link download tidak valid atau sudah kadaluarsa.');
        }

        $path = 'exports/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($path, $filename);
    }

   /**
     * GET /api/v1/owner/guests
     * Query params: search, per_page
     */
    public function databaseOwner(Request $request)
    {
        Carbon::setLocale('id');

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $search = $request->input('search') ?? $request->input('keyword');

        // 🟢 Eager load 'purpose' bersama assignedUser & products
        $guests = guests::with([
            'category',
            'visits' => function ($query) {
                $query->with(['assignedUser', 'products', 'purpose'])->latest();
            }
        ])
            ->withCount('visits')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        $formattedData = $guests->getCollection()->map(function ($guest) {
            $latestVisit = $guest->visits->first();

            $minatProduk = '-';
            if ($latestVisit) {
                if ($latestVisit->relationLoaded('products') && $latestVisit->products && $latestVisit->products->isNotEmpty()) {
                    $minatProduk = $latestVisit->products->pluck('name')->join(', ');
                } else {
                    $minatProduk = $latestVisit->product_interest ?? 'Umum';
                }
            }

            $categoryName = optional($guest->category)->name ?? 'Regular';

            return [
                'id'                 => $guest->id,
                'nama'               => $guest->name,
                'kontak'             => $guest->phone,
                'instansi'           => $guest->company_name ?? '-',
                'jabatan'            => $guest->position ?? '-',
                'kategoriTamu'       => $guest->is_vip ? 'VIP' : $categoryName,
                'minatProduk'        => $minatProduk,
                'totalKunjungan'     => $guest->visits_count ?? 0,
                'terakhirBerkunjung' => $latestVisit
                    ? Carbon::parse($latestVisit->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY')
                    : '-',
                'timelineRiwayat'    => $guest->visits->map(function ($visit) {
                    return [
                        'id'        => $visit->id,
                        'waktu'     => Carbon::parse($visit->created_at)->locale('id')->isoFormat('D MMM YYYY, HH:mm') . ' WIB',
                        'pic'       => optional($visit->assignedUser)->name ?? 'Admin',
                        'keperluan' => optional($visit->purpose)->name ?? $visit->notes ?? '-', // 🟢 Ambil nama dari relasi purpose
                        'status'    => $visit->status ?? 'Terjadwal',
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'status'  => true,
            'message' => 'Data database tamu berhasil dimuat.',
            'data'    => $formattedData,
            'meta'    => [
                'current_page' => $guests->currentPage(),
                'last_page'    => $guests->lastPage(),
                'per_page'     => $guests->perPage(),
                'total'        => $guests->total(),
            ],
        ], 200);
    }

    /**
     * GET /api/v1/owner/guests/{id}
     */
    public function databaseOwnerDetail($id)
    {
        Carbon::setLocale('id');

        $guest = guests::with([
                'category',
                'visits' => function ($query) {
                    $query->with(['assignedUser', 'products', 'purpose'])->latest();
                }
            ])
            ->withCount('visits')
            ->find($id);

        if (!$guest) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tamu tidak ditemukan.',
            ], 404);
        }

        $latestVisit = $guest->visits->first();
        $minatProduk = '-';
        if ($latestVisit) {
            if ($latestVisit->relationLoaded('products') && $latestVisit->products && $latestVisit->products->isNotEmpty()) {
                $minatProduk = $latestVisit->products->pluck('name')->join(', ');
            } else {
                $minatProduk = $latestVisit->product_interest ?? 'Umum';
            }
        }

        $categoryName = optional($guest->category)->name ?? 'Regular';

        $detailData = [
            'id'                 => $guest->id,
            'nama'               => $guest->name,
            'kontak'             => $guest->phone,
            'email'              => $guest->email ?? '-',
            'instansi'           => $guest->company_name ?? '-',
            'jabatan'            => $guest->position ?? '-',
            'alamat'             => $guest->address ?? '-',
            'kategoriTamu'       => $guest->is_vip ? 'VIP' : $categoryName,
            'minatProduk'        => $minatProduk,
            'totalKunjungan'     => $guest->visits_count ?? 0,
            'terakhirBerkunjung' => $latestVisit
                ? Carbon::parse($latestVisit->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY')
                : '-',
            'timelineRiwayat'    => $guest->visits->map(function ($visit) {
                return [
                    'id'        => $visit->id,
                    'waktu'     => Carbon::parse($visit->created_at)->locale('id')->isoFormat('D MMM YYYY, HH:mm') . ' WIB',
                    'pic'       => optional($visit->assignedUser)->name ?? 'Admin',
                    'keperluan' => optional($visit->purpose)->name ?? $visit->notes ?? '-',
                    'status'    => $visit->status ?? 'Terjadwal',
                ];
            })->values(),
        ];

        return response()->json([
            'status'  => true,
            'message' => 'Detail data tamu berhasil dimuat.',
            'data'    => $detailData,
        ], 200);
    }


    /**
     * Helper: bentuk meta pagination yang konsisten buat semua endpoint.
     */
    private function paginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'from'         => $paginator->firstItem(),
            'to'           => $paginator->lastItem(),
        ];
    }

    private function mapVisitForOwnerDashboard($v): array
    {
        return [
            'id'               => $v->id,
            'token'            => $v->visit_code ?? ('TRX-' . str_pad($v->id, 3, '0', STR_PAD_LEFT)),
            'nama'             => optional($v->guest)->name,
            'jabatan'          => optional($v->guest)->position,
            'instansi'         => optional($v->guest)->company_name,
            'waktu'            => $v->scheduled_at ?? $v->check_in_at,
            'jenis'            => optional(optional($v->guest)->category)->name,
            'keperluan'        => optional($v->purpose)->name,
            'pic'              => optional($v->assignedUser)->name,
            'catatan'          => $v->notes ?? $v->meeting_result ?? optional(optional($v->lead)->followUps->sortByDesc('created_at')->first())->result,
            'status_kunjungan' => $v->status,
            'status_lead'      => optional($v->lead)->status, // ← TAMBAHAN: ini yang kelewat
            'potential_level'  => $v->potensi_level,
            'is_vip'           => (bool) optional($v->guest)->is_vip,
        ];
    }

    private function mapVisitLaporan($v): array
    {
        $checkIn  = $v->check_in_at;
        $checkOut = $v->check_out_at;
        $durasiMenit = ($checkIn && $checkOut)
            ? Carbon::parse($checkIn)->diffInMinutes(Carbon::parse($checkOut))
            : null;

        // Samain persis logika $isCompleted di blade (riwayat.blade.php)
        $statusLower = strtolower(trim((string) $v->status));
        $isCompleted = in_array($statusLower, ['completed', 'selesai', 'meeting selesai']);

        return [
            'id'               => $v->id,
            'visit_code'       => $v->visit_code ?? ('TRX-' . str_pad($v->id, 3, '0', STR_PAD_LEFT)),
            'check_in_at'      => $checkIn,
            'check_out_at'     => $checkOut,
            'durasi_menit'     => $durasiMenit,
            'guest_name'       => optional($v->guest)->name,
            'guest_phone'      => optional($v->guest)->phone,
            'is_vip'           => (bool) optional($v->guest)->is_vip,
            'branch_name'      => optional($v->branch)->name,
            'pic_name'         => optional($v->assignedUser)->name,
            'purpose_name'     => optional($v->purpose)->name,
            'product_names'    => $v->products->pluck('name')->implode(', '),
            'source_name'      => optional($v->source)->name,
            'potential_level'  => $v->potential_level,              // FIX: langsung dari visits.potensi_level
            'meeting_result'   => $v->meeting_result,
            'notes'            => $v->notes,                      // FIX: field baru untuk "Catatan Hasil"
            'status'           => $v->status,
            'lead_status'      => optional($v->lead)->status,      // FIX: leads.status terpisah
            'is_completed'     => $isCompleted,                    // FIX: flag buat logic badge
            'company_name'     => optional($v->guest)->company_name,
        ];
    }

    public function produkDiminati(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year  = (int) $request->input('year', now()->year);

        $products = DB::table('visit_products')
            ->join('products', 'products.id', '=', 'visit_products.product_id')
            ->join('visits', 'visits.id', '=', 'visit_products.visit_id')
            ->join('leads', 'leads.visit_id', '=', 'visits.id')
            ->where('leads.status', 'deal')
            ->where(function ($q) use ($month, $year) {
                $q->whereMonth('visits.check_in_at', $month)->whereYear('visits.check_in_at', $year)
                    ->orWhere(function ($q2) use ($month, $year) {
                        $q2->whereNull('visits.check_in_at')
                            ->whereMonth('visits.scheduled_at', $month)
                            ->whereYear('visits.scheduled_at', $year);
                    });
            })
            ->select('products.id', 'products.name', DB::raw('count(*) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->get();

        $totalPeminatan = $products->sum('total');

        $data = $products->map(function ($p) use ($totalPeminatan) {
            return [
                'nama'       => $p->name,
                'jumlah'     => $p->total,
                'persentase' => $totalPeminatan > 0 ? round(($p->total / $totalPeminatan) * 100, 1) : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'products'         => $data,
                'total_peminatan'  => $totalPeminatan,
                'month'            => $month,
                'year'             => $year,
            ],
        ]);
    }

    /**
     * GET /api/owner/kategori-tamu?month=8&year=2026
     */
    public function kategoriTamu(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year  = (int) $request->input('year', now()->year);

        $categories = DB::table('visits')
            ->join('guests', 'guests.id', '=', 'visits.guest_id')
            ->join('guest_categories', 'guest_categories.id', '=', 'guests.guest_category_id')
            ->join('leads', 'leads.visit_id', '=', 'visits.id')
            ->where('leads.status', 'deal')
            ->where(function ($q) use ($month, $year) {
                $q->whereMonth('visits.check_in_at', $month)->whereYear('visits.check_in_at', $year)
                    ->orWhere(function ($q2) use ($month, $year) {
                        $q2->whereNull('visits.check_in_at')
                            ->whereMonth('visits.scheduled_at', $month)
                            ->whereYear('visits.scheduled_at', $year);
                    });
            })
            ->select('guest_categories.id', 'guest_categories.name', DB::raw('count(*) as total'))
            ->groupBy('guest_categories.id', 'guest_categories.name')
            ->orderByDesc('total')
            ->get();

        $totalTamu = $categories->sum('total');

        $data = $categories->map(function ($c) use ($totalTamu) {
            return [
                'kategori'   => $c->name,
                'jumlah'     => $c->total,
                'persentase' => $totalTamu > 0 ? round(($c->total / $totalTamu) * 100, 1) : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'categories'  => $data,
                'total_tamu'  => $totalTamu,
                'month'       => $month,
                'year'        => $year,
            ],
        ]);
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
            'follow_ups'      => $l->followUps->map(fn($f) => [
                'id'              => $f->id,
                'result'          => $f->result ?? null,
                'status'          => $f->status ?? null,
                'due_at'          => $f->due_at ?? null,
                'estimated_value' => $f->estimated_value ?? null,   // ⬅️ BARU: nilai estimasi per update
                'created_at'      => $f->created_at,
            ]),
        ];
    }
}
