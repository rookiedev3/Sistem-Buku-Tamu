<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\visits;
use Carbon\Carbon;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

$visits = visits::with(['guest.category', 'assignedUser'])
            ->whereDate('check_in_at', $today)
            ->orWhereDate('scheduled_at', $today)
            ->get();

        $totalToday = $visits->count();

        $leadDealsCount = visits::whereMonth('scheduled_at', Carbon::now()->month)
                            ->where('status', 'deal')
                            ->count();

        return view('manager.dashboard', compact('visits', 'totalToday', 'leadDealsCount'));
    }
}