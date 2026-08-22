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

        // fallback sementara untuk role lain (manager, admin, pic, security)
        return redirect()->route('dashboard');
    }

    /**
     * Dashboard untuk role owner: ringkasan statistik keseluruhan.
     */
    protected function ownerDashboard()
    {
    return redirect()->route('owner.dashboard');
    }

    public function halamanUtama(){
        return view('halaman_utama');
    }
    protected function managerDashboard()
    {
        return redirect()->route('manager.dashboard');
    }

    protected function adminDashboard()
    {
        return redirect()->route('frontoffice.dashboard');
    }

    protected function picDashboard()
    {
return redirect()->route('pic.dashboard');
} // ✅ Otomatis lempar ke rute yang ada datanya    }

    protected function securityDashboard()
    {
    return redirect()->route('security.dashboard');
    }

    /**
     * Dashboard untuk role tamu: lihat status kunjungan sendiri
     * berdasarkan email akun yang cocok dengan data guest.
     */    
}