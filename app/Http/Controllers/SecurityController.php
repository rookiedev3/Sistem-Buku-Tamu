<?php

namespace App\Http\Controllers;

use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Dashboard Security — hanya menampilkan data kehadiran tamu hari ini.
     * TIDAK memuat kolom bisnis sensitif (meeting_result, potential_level, follow_up_at, dll).
     */
    public function dashboard()
    {
        $today = Carbon::today();

        $visits = visits::with(['guest:id,name,company_name', 'assignedUser:id,name'])
            ->select('id', 'visit_code', 'guest_id', 'assigned_to', 'scheduled_at', 'check_in_at', 'check_out_at', 'status')
            ->whereDate('scheduled_at', $today)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $totalToday = $visits->count();

        return view('security.dashboard', compact('visits', 'totalToday'));
    }

    /**
     * Security cuma boleh catat kehadiran (masuk), tidak bisa sentuh status prospek/lead.
     */
    public function checkIn($id)
    {
        $visit = visits::findOrFail($id);
        $visit->update([
            'status'      => 'confirmed',
            'check_in_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tamu berhasil dicatat masuk area.');
    }

    /**
     * Catat kehadiran keluar.
     */
    public function checkOut($id)
    {
        $visit = visits::findOrFail($id);
        $visit->update([
            'check_out_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tamu berhasil dicatat keluar area.');
    }
}