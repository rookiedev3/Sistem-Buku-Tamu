<?php

namespace App\Http\Controllers;

use App\Models\leads;
use Illuminate\Http\Request;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI
use App\Exports\KunjunganLaporanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller
{
    public function dashboard(Request $request)
    {
        $selectedDate = $request->query('date', Carbon::today()->format('Y-m-d'));
        $selectedDateCarbon = Carbon::parse($selectedDate);

        $visits = visits::with(['guest.category', 'assignedUser'])
            ->where(function ($q) use ($selectedDateCarbon) {
                $q->whereDate('check_in_at', $selectedDateCarbon)
                  ->orWhereDate('scheduled_at', $selectedDateCarbon);
            })
            ->get();

        $totalToday = $visits->count();

        $leadDealsCount = visits::whereMonth('scheduled_at', Carbon::now()->month)
            ->where('status', 'deal')
            ->count();

        return view('manager.dashboard', compact('visits', 'totalToday', 'leadDealsCount', 'selectedDate'));
    }

public function kunjungan(Request $request)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ], [
        'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
    ]);

    $vipFilter = $request->input('vip_status', 'all'); // <-- TAMBAHKAN IN

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

        // Filter status VIP/Reguler (berdasarkan guests.is_vip) <-- TAMBAHKAN BLOK INI
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
        ->paginate(10)
        ->appends($request->query());

    return view('manager.kunjungan', compact('visits', 'vipFilter'));
}
public function leadsPipeline(Request $request)
{
    $today  = Carbon::today();
    $filter = $request->input('filter', 'active');
        $vipFilter = $request->input('vip_status', 'all'); // <-- TAMBAHKAN INI

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
        // 'all' => tanpa filter tambahan, tampilkan semua status termasuk lost
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

            // Filter status VIP/Reguler (berdasarkan guests.is_vip) <-- TAMBAHKAN BLOK INI
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
        ->paginate(10)
        ->appends($request->query());

    // Counter lintas semua PIC — sekarang termasuk lost
    $countAll      = leads::count();
    $countActive   = leads::whereNotIn('status', ['deal', 'lost'])->count();
    $countOverdue  = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
    $countToday    = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
    $countUpcoming = leads::whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
    $countDeal     = leads::where('status', 'deal')->count();
    $countLost     = leads::where('status', 'lost')->count();

    return view('manager.leads', compact(
        'leads', 'filter',
    'countAll', 'countActive', 'countOverdue', 'countToday', 'countUpcoming', 'countDeal', 'countLost', 'vipFilter'
    ));
}

public function laporan(Request $request)
{
    $month    = (int) $request->input('month', now()->month);
    $year     = (int) $request->input('year', now()->year);
    $category = (string) $request->input('category', '');
    $branchId = (string) $request->input('branch_id', ''); // <-- TAMBAHKAN
    $picId    = (string) $request->input('pic_id', '');    // <-- TAMBAHKAN

    $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
        ->whereMonth('check_in_at', $month)
        ->whereYear('check_in_at', $year);

    if (Schema::hasColumn('guests', 'is_vip')) {
        if ($category === 'vip') {
            $baseQuery->whereHas('guest', fn($q) => $q->where('is_vip', true));
        } elseif ($category === 'reguler') {
            $baseQuery->whereHas('guest', function ($q) {
                $q->where('is_vip', false)->orWhereNull('is_vip');
            });
        }
    }

    // Filter Cabang <-- TAMBAHKAN BLOK INI
    if ($branchId !== '') {
        $baseQuery->where('branch_id', $branchId);
    }

    // Filter PIC <-- TAMBAHKAN BLOK INI
    if ($picId !== '') {
        $baseQuery->where('assigned_to', $picId);
    }

    $totalKunjungan = (clone $baseQuery)->count();
    $totalDeal = (clone $baseQuery)->whereHas('lead', fn($q) => $q->where('status', 'deal'))->count();
    $totalVip = Schema::hasColumn('guests', 'is_vip')
        ? (clone $baseQuery)->whereHas('guest', fn($q) => $q->where('is_vip', true))->count()
        : 0;

    $visits = $baseQuery->orderBy('check_in_at', 'desc')
        ->paginate(15)
        ->appends($request->query());

    // Data untuk dropdown filter <-- TAMBAHKAN BLOK INI
    $branches = \App\Models\branches::orderBy('name')->get();
    $picUsers = \App\Models\users::whereIn(
        'id',
        visits::whereNotNull('assigned_to')->distinct()->pluck('assigned_to')
    )->orderBy('name')->get();

    return view('manager.laporan', compact(
        'visits', 'month', 'year', 'category', 'branchId', 'picId', 'branches', 'picUsers', // <-- tambahkan
        'totalKunjungan', 'totalDeal', 'totalVip'
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
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $branchName = $branchId !== '' ? optional(\App\Models\branches::find($branchId))->name : null;
    $picName    = $picId !== '' ? optional(\App\Models\users::find($picId))->name : null;

    $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '.xlsx';

    return Excel::download(
        new KunjunganLaporanExport(
            $month, $year, $category, $branchId, $picId,
            $months[$month] ?? (string) $month, $branchName, $picName
        ),
        $fileName
    );
}

public function exportPdf(Request $request)
{
    $month    = (int) $request->input('month', now()->month);
    $year     = (int) $request->input('year', now()->year);
    $category = (string) $request->input('category', '');
    $branchId = (string) $request->input('branch_id', ''); // <-- TAMBAHKAN
    $picId    = (string) $request->input('pic_id', '');    // <-- TAMBAHKAN

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $baseQuery = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
        ->whereMonth('check_in_at', $month)
        ->whereYear('check_in_at', $year);

    if (Schema::hasColumn('guests', 'is_vip')) {
        if ($category === 'vip') {
            $baseQuery->whereHas('guest', fn($q) => $q->where('is_vip', true));
        } elseif ($category === 'reguler') {
            $baseQuery->whereHas('guest', function ($q) {
                $q->where('is_vip', false)->orWhereNull('is_vip');
            });
        }
    }

    // Filter Cabang & PIC <-- TAMBAHKAN BLOK INI
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

    $waitTimes = $visits->filter(fn($v) => $v->check_in_at && $v->meeting_start_at)
        ->map(fn($v) => \Carbon\Carbon::parse($v->check_in_at)->diffInMinutes(\Carbon\Carbon::parse($v->meeting_start_at)));
    $avgWaitMinutes = $waitTimes->count() > 0 ? round($waitTimes->avg()) : null;

    // Nama cabang & PIC untuk ditampilkan di narasi PDF <-- TAMBAHKAN BLOK INI
    $branchName = $branchId !== '' ? optional(\App\Models\branches::find($branchId))->name : null;
    $picName = $picId !== '' ? optional(\App\Models\users::find($picId))->name : null;

    $pdf = Pdf::loadView('manager.laporan_pdf', [
        'visits'          => $visits,
        'monthLabel'      => $months[$month] ?? $month,
        'year'            => $year,
        'category'        => $category,
        'branchName'      => $branchName, // <-- tambahkan
        'picName'         => $picName,    // <-- tambahkan
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
    ])->setPaper('a4', 'landscape');

    $fileName = 'laporan-kunjungan-' . $month . '-' . $year . '.pdf';

    return $pdf->download($fileName);
}
}