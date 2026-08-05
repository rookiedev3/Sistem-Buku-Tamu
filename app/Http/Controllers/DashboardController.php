<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'owner') {
            return $this->ownerDashboard();
        }

        if ($user->role === 'manager') {
            return $this->managerDashboard();
        }
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }
        if ($user->role === 'pic') {
            return $this->picDashboard();
        }
        if ($user->role === 'security') {
            return $this->securityDashboard();
        }

        if ($user->role === 'tamu') {
            return $this->tamuDashboard($user);
        }

        // fallback sementara untuk role lain (manager, admin, pic, security)
        return redirect()->route('dashboard');
    }

    /**
     * Dashboard untuk role owner: ringkasan statistik keseluruhan.
     */
    protected function ownerDashboard()
    {
        $totalGuests    = DB::table('guests')->count();
        $totalVisits    = DB::table('visits')->count();
        $totalBranches  = DB::table('branches')->count();
        $totalLeads     = DB::table('leads')->count();

        $visitHariIni = DB::table('visits')
            ->whereDate('check_in_at', now())
            ->count();

        $visitMenunggu = DB::table('visits')
            ->where('status', 'waiting')
            ->count();

        return view('dashboard.index', compact(
            'totalGuests',
            'totalVisits',
            'totalBranches',
            'totalLeads',
            'visitHariIni',
            'visitMenunggu'
        ));
    }

    protected function managerDashboard()
    {
        return view('manager.dashboard');
    }

    protected function adminDashboard()
    {
        return view('frontoffice.dashboard');
    }

    protected function picDashboard()
    {
        return view('pic.dashboard');
    }

    protected function securityDashboard()
    {
        return view('security.dashboard');
    }

    /**
     * Dashboard untuk role tamu: lihat status kunjungan sendiri
     * berdasarkan email akun yang cocok dengan data guest.
     */
    protected function tamuDashboard($user)
    {
        $guest = DB::table('guests')
            ->where('email', $user->email)
            ->first();

        $visits = [];

        if ($guest) {
            $visits = DB::table('visits')
                ->where('guest_id', $guest->id)
                ->orderByDesc('check_in_at')
                ->get();
        }

        return view('tamu.dashboard', compact('guest', 'visits'));
    }
}