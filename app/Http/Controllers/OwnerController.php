<?php

namespace App\Http\Controllers;

use App\Models\guests;
use App\Models\leads;
use App\Models\products;
use App\Models\users;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OwnerController extends Controller
{
    /**
     * Status yang dianggap "sudah final" (kunjungan sudah selesai / dibatalkan).
     * Dipakai di kunjungan() supaya halaman ini hanya menampilkan tamu yang
     * sudah masuk tabel leads (jadi prospek) ATAU non-lead (selesai biasa,
     * tidak dikonversi), sama persis dengan pendekatan di manager/kunjungan.
     */
    private const FINAL_STATUSES = [
        'completed',
        'Selesai',
        'Meeting Selesai',
        'cancelled',
        'Dibatalkan',
        'Ditolak',
    ];

    private const KUNJUNGAN_OWNER = [
        'completed',
        'Selesai',
        'cancelled',
        'Dibatalkan',
        'Ditolak',
    ];

    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        // Cast eksplisit ke string sebelum trim, supaya nilai null tidak lolos
        // sebagai "bukan string kosong" dan malah dipakai jadi WHERE assigned_to = NULL.
        $statusFilter = trim((string) $request->input('status', ''));
        $picFilter    = trim((string) $request->input('pic_id', ''));
        $keyword      = trim((string) $request->input('keyword', ''));
        $leadOnly     = $request->boolean('lead_only'); // <-- baru

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

        if ($request->boolean('partial') || $request->ajax() || $request->wantsJson()) {
            return response()
                ->view('partials.kunjungan-hari-ini-table', compact('visits'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

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


        return view('partials.ringkasan-operasional', compact(
            'totalTamuHariIni',
            'sedangMenunggu',
            'sedangBertemu',
            'menjadiLeadHariIni',
            'topProduct',
            'topCategory',
            'topCategoryPercentage',
            'avgWaitMinutes',
            'serviceRate',
            'conversionRate',
            'recentActivities',
            'visits',
            'statusOptions',
            'picOptions',
            'statusFilter',
            'picFilter',
            'keyword',
            'pertemuanSelesai',
            'terjadwalHariIni',
            'leadOnly'
        ));
    }

    public function activityLog(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10); // <-- default disamain jadi 10
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

        return view('owner.aktivitas', compact('activities', 'keyword'));
    }
    public function kunjungan(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
        ]);

        $vipFilter = $request->input('vip_status', 'all');

        // Validasi per_page, konsisten sama activityLog() dan databaseOwner()
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = visits::with(['guest.category', 'assignedUser', 'purpose', 'lead.followUps'])
            ->whereIn('status', self::KUNJUNGAN_OWNER);

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

        return view('kunjungan.index', compact('visits', 'vipFilter'));
    }

    public function leads(Request $request)
    {
        $today  = Carbon::today();
        $filter = $request->input('filter', 'active');
        $vipFilter = $request->input('vip_status', 'all');

        // Validasi per_page, konsisten sama kunjungan()/activityLog()/databaseOwner()
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
                // 'all' => tanpa filter tambahan
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
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

        $countAll      = leads::count();
        $countActive   = leads::whereNotIn('status', ['deal', 'lost'])->count();
        $countOverdue  = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
        $countToday    = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
        $countUpcoming = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
        $countDeal     = leads::where('status', 'deal')->count();
        $countLost     = leads::where('status', 'lost')->count();

        return view('leads.index', compact(
            'leads',
            'filter',
            'countAll',
            'countActive',
            'countOverdue',
            'countToday',
            'countUpcoming',
            'countDeal',
            'countLost',
            'vipFilter'
        ));
    }

    private const COMPLETED_STATUSES = ['completed', 'Selesai', 'Meeting Selesai'];

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
                // 'meeting selesai',
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

        $branches = \App\Models\branches::orderBy('name')->get();
        $picUsers = users::whereIn(
            'id',
            visits::whereNotNull('assigned_to')->distinct()->pluck('assigned_to')
        )->orderBy('name')->get();

        return view('laporan.index', compact(
            'visits',
            'month',
            'year',
            'category',
            'branchId',
            'picId',
            'branches',
            'picUsers',
            'totalKunjungan',
            'totalDeal',
            'totalVip',
            'conversionRate',
            'avgDuration'
        ));
    }

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

        $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
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
            $fileName
        );
    }

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
                // 'meeting selesai',
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

        // Rata-rata Durasi = check_in_at -> check_out_at, disamakan dengan
        // logic "Durasi" yang ditampilkan di preview halaman Laporan.
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

        $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Halaman Daftar Database Tamu
     */
    public function databaseOwner(Request $request)
    {
        Carbon::setLocale('id');

        // 1. Ambil & validasi jumlah data per halaman (Default: 10)
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $search = $request->input('search');

        // 2. Query Data Tamu beserta Relasi Kunjungan & Produk
        $guests = guests::with(['visits.products']) // 🟢 Eager load relasi produk dari kunjungan
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

        return view('tamu.index', compact('guests'));
    }
    /**
     * Halaman Detail & Riwayat Kunjungan Tamu
     */
    public function databaseOwnerDetail($id)
    {
        Carbon::setLocale('id');

        // Load relasi visits beserta assignedUser (User yang dituju/PIC)
        $guest = guests::with(['category', 'visits' => function ($query) {
            $query->with('assignedUser')->latest();
        }])
            ->withCount('visits')
            ->findOrFail($id);

        return view('tamu.detail', compact('guest'));
    }
}
