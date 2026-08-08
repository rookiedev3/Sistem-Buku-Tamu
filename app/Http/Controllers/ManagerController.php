<?php

namespace App\Http\Controllers;

use App\Models\leads;
use Illuminate\Http\Request;
use App\Models\visits;
use Carbon\Carbon;

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

    $visits = $query->orderBy('check_in_at', 'desc')
        ->paginate(10)
        ->appends($request->query());

    return view('manager.kunjungan', compact('visits'));
}
public function leadsPipeline(Request $request)
{
    $today  = Carbon::today();
    $filter = $request->input('filter', 'active');

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
        'countAll', 'countActive', 'countOverdue', 'countToday', 'countUpcoming', 'countDeal', 'countLost'
    ));
}
}