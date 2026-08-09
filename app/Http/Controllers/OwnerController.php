<?php

namespace App\Http\Controllers;

use App\Models\guests;
use App\Models\leads;
use App\Models\products;
use App\Models\users;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        // Cast eksplisit ke string sebelum trim, supaya nilai null tidak lolos
        // sebagai "bukan string kosong" dan malah dipakai jadi WHERE assigned_to = NULL.
        $statusFilter = trim((string) $request->input('status', ''));
        $picFilter    = trim((string) $request->input('pic_id', ''));
        $keyword      = trim((string) $request->input('keyword', ''));
        $leadOnly     = $request->boolean('lead_only'); // <-- baru

        $baseTodayQuery = fn () => visits::where(function ($q) use ($today) {
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

        $visits = $visitsQuery->orderBy('scheduled_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

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
            ->map(fn ($v) => Carbon::parse($v->check_in_at)->diffInMinutes(Carbon::parse($v->meeting_start_at)));

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
            ->map(fn ($s) => trim($s))
            ->filter(fn ($s) => $s !== '')
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
            'terjadwalHariIni'
        ));
    }

    public function activityLog(Request $request)
{
    $keyword = trim((string) $request->input('keyword', ''));

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
        ->paginate(20)
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

    $query = visits::with(['guest.category', 'assignedUser', 'purpose', 'lead.followUps']);

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
        ->paginate(10)
        ->appends($request->query());

    return view('kunjungan.index', compact('visits', 'vipFilter'));
}

public function leads(Request $request)
{
    $today  = Carbon::today();
    $filter = $request->input('filter', 'active');
    $vipFilter = $request->input('vip_status', 'all');

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
        ->paginate(10)
        ->appends($request->query());

    $countAll      = leads::count();
    $countActive   = leads::whereNotIn('status', ['deal', 'lost'])->count();
    $countOverdue  = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
    $countToday    = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
    $countUpcoming = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
    $countDeal     = leads::where('status', 'deal')->count();
    $countLost     = leads::where('status', 'lost')->count();

    return view('leads.index', compact(
        'leads', 'filter',
        'countAll', 'countActive', 'countOverdue', 'countToday', 'countUpcoming', 'countDeal', 'countLost', 'vipFilter'
    ));
}
}