<?php

namespace App\Http\Controllers;

use App\Models\follow_ups;
use App\Models\leads;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{

    // dashboard menampilkan kunjungan yang sedang berlangsung, menunggu, atau pending follow-up
    public function dashboardPic(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $today = Carbon::today();

        $query = visits::with(['guest', 'purpose', 'branch'])
            ->where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak']);

        if ($filter === 'today') {
            $query->where(function ($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            });
        } elseif ($filter === 'upcoming') {
            $query->whereNull('check_in_at')
                ->whereDate('scheduled_at', '>', $today);
        } else {
            // Semua (default) — perilaku lama tetap dipertahankan
            $query->where(function ($q) use ($today) {
                $q->whereIn('status', ['pending', 'waiting', 'Menunggu', 'confirmed', 'Disetujui', 'meeting'])
                    ->orWhereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            });
        }

        $visits = $query->orderBy('created_at', 'desc')->get();

        $vipCount = $visits->filter(function ($v) {
            return optional($v->guest)->is_vip == true;
        })->count();

        $regularCount = $visits->count() - $vipCount;

        // Hitung badge counter untuk filter cepat
        $countToday = visits::where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak'])
            ->where(function ($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            })->count();

        $countUpcoming = visits::where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak'])
            ->whereNull('check_in_at')
            ->whereDate('scheduled_at', '>', $today)
            ->count();

        return view('pic.dashboard', compact('visits', 'vipCount', 'regularCount', 'filter', 'countToday', 'countUpcoming'));
    }


    /**
     * Menampilkan halaman daftar follow-up aktif (Warm & Hot)
     */
    public function followupIndex(Request $request)
    {
        $query = visits::with(['guest', 'followUps' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->where('assigned_to', auth()->id())
            ->whereIn('potential_level', ['warm', 'hot']);

        // Filter cepat: today / overdue / upcoming
        $filter = $request->input('filter', 'all');
        $today = Carbon::today();

        if ($filter === 'today') {
            $query->whereDate('follow_up_at', $today);
        } elseif ($filter === 'overdue') {
            $query->whereDate('follow_up_at', '<', $today);
        } elseif ($filter === 'upcoming') {
            $query->whereDate('follow_up_at', '>', $today);
        }

        // Filter rentang tanggal manual (opsional, tetap bisa dipakai bareng filter cepat)
        if ($request->filled('start_date')) {
            $query->whereDate('follow_up_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('follow_up_at', '<=', $request->end_date);
        }

        // Urutkan: yang follow_up_at paling dekat/terlambat di atas, yang null di bawah
        $leads = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate(10)
            ->appends($request->query());

        $totalLeads = visits::where('assigned_to', auth()->id())
            ->where(function ($query) {
                $query->where('is_converted_to_lead', true)
                    ->orWhereNotNull('follow_up_at');
            })
            ->count();

        $totalDeal = visits::where('assigned_to', auth()->id())
            ->where('potential_level', 'deal')
            ->count();

        // Hitung ringkasan untuk badge counter di filter
        $countOverdue = visits::where('assigned_to', auth()->id())
            ->whereIn('potential_level', ['warm', 'hot'])
            ->whereDate('follow_up_at', '<', $today)
            ->count();

        $countToday = visits::where('assigned_to', auth()->id())
            ->whereIn('potential_level', ['warm', 'hot'])
            ->whereDate('follow_up_at', $today)
            ->count();

        $countAll = visits::where('assigned_to', auth()->id())
            ->whereIn('potential_level', ['warm', 'hot'])
            ->count();

        $countUpcoming = visits::where('assigned_to', auth()->id())
            ->whereIn('potential_level', ['warm', 'hot'])
            ->whereDate('follow_up_at', '>', $today)
            ->count();

        return view('pic.leads', compact('leads', 'totalLeads', 'totalDeal', 'filter', 'countOverdue', 'countToday', 'countAll', 'countUpcoming'));
    }

    /**
     * Dashboard PIC & Manajemen Pertemuan
     */

    /**
     * Riwayat Kunjungan PIC
     */
  public function riwayatPic(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
        ]);

        $query = visits::with(['guest', 'purpose', 'branch', 'lead.followUps'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('company_name', 'like', "%{$keyword}%");
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

        return view('pic.riwayat', compact('visits'));
    }
    /**
     * Update Status Kehadiran / Kunjungan
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi disesuaikan agar menerima nilai Inggris/Indonesia dari view
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,Dikonfirmasi,Dibatalkan',
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $oldStatus = $visit->status;
        $isConfirmed = in_array($request->status, ['confirmed', 'Dikonfirmasi']);
        $newStatus = $isConfirmed ? 'Dikonfirmasi' : 'Dibatalkan';

        $visit->status = $newStatus;

        if ($isConfirmed) {
            $visit->meeting_start_at = now();
        }

        visit_status_logs::create([
            'visit_id' => $visit->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->save();

        $msg = $isConfirmed
            ? 'Kehadiran tamu dikonfirmasi. Silakan mulai pertemuan.'
            : 'Kunjungan telah dibatalkan.';

        return back()->with('success', $msg);
    }

    /**
     * Selesaikan Pertemuan & Catat Hasil Diskusi
     */
    /**
     * Menyimpan Hasil Diskusi / Catatan Pertemuan (Status tetap 'meeting' sampai nanti di-checkout Admin)
     */
    public function completeMeeting(Request $request, $id)
    {
        $request->validate([
            'potential_level' => 'required|string',
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        if ($visit->status != 'Meeting Selesai') {
            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => $visit->status,
                'new_status' => 'Meeting Selesai',
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);
        }

        $visit->update([
            // Status TIDAK diubah jadi 'completed', biarkan tetap atau pastikan 'meeting'
            'status' => 'Meeting Selesai', // Diseragamkan ke Bahasa Indonesia
            'meeting_result' => $request->notes ?? $request->meeting_result,
            'potential_level' => $request->potential_level,
            'follow_up_at' => $request->followup_date ?? $request->follow_up_at,
            'is_converted_to_lead' => in_array($request->potential_level, ['warm', 'hot', 'deal']),
            // 'check_out_at' dihilangkan/tidak diisi di sini karena di-checkout oleh admin nanti
        ]);

        if (in_array($request->potential_level, ['warm', 'hot']) && ($request->followup_date || $request->follow_up_at)) {
            follow_ups::create([
                'visit_id' => $visit->id,
                'assigned_to' => auth()->id(),
                'due_at' => $request->followup_date ?? $request->follow_up_at,
                'result' => null,
                'status' => 'pending',
            ]);
        }

        return redirect()->back()->with('success', 'Catatan hasil pertemuan berhasil disimpan!');
    }

    /**
     * Update Follow-Up dari Modal (Daftar Follow-Up)
     */
    public function updateFollowUp(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'result' => 'required|string',
            'due_at' => 'nullable|date',
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        // 1. Update status potensi dan jadwal follow-up pada tabel visits
        $visit->potential_level = $request->status;
        $visit->follow_up_at = in_array($request->status, ['deal', 'drop']) ? null : $request->due_at;

        // Jika status diubah jadi deal, tandai juga konversinya
        if ($request->status === 'deal') {
            $visit->is_converted_to_lead = true;
        }
        $visit->save();

        // 2. Simpan catatan riwayat follow-up baru ke tabel follow_ups
        follow_ups::create([
            'visit_id' => $visit->id,
            'assigned_to' => auth()->id(),
            'result' => $request->result,
            'due_at' => $request->due_at ?? now(),
            'status' => $request->status === 'deal' ? 'completed' : 'pending',
        ]);

        return redirect()->route('pic.followup')->with('success', 'Status dan catatan follow-up berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman daftar klien yang sudah Deal (Leads)
     */
    public function leadsIndex(Request $request)
    {
        $today   = Carbon::today();
        $filter  = $request->input('filter', 'active');
        $ownerId = auth()->id();

        // Base query: selalu exclude status 'lost' dari halaman ini
        $query = leads::with(['guest', 'visit', 'followUps'])
            ->where('owner_id', $ownerId)
            ->where('status', '!=', 'lost');

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
            // 'all' => tanpa filter tambahan (tapi tetap exclude lost dari base query)
        }

        if ($request->filled('start_date')) {
            $query->whereDate('follow_up_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('follow_up_at', '<=', $request->end_date);
        }

        $leads = $query->orderByRaw('follow_up_at IS NULL, follow_up_at ASC')
            ->paginate(10)
            ->appends($request->query());

        // Semua counter juga exclude 'lost' secara permanen
        $baseCount = fn() => leads::where('owner_id', $ownerId)->where('status', '!=', 'lost');

        $countAll      = $baseCount()->count();
        $countActive   = $baseCount()->whereNotIn('status', ['deal', 'lost'])->count();
        $countOverdue  = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
        $countToday    = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
        $countUpcoming = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
        $countDeal     = $baseCount()->where('status', 'deal')->count();

        return view('pic.leads', compact(
            'leads', 'filter',
            'countAll', 'countActive', 'countOverdue', 'countToday', 'countUpcoming', 'countDeal'
        ));
    }

    public function startMeeting($id)
    {
        $visit = visits::findOrFail($id);

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Sedang Bertemu',
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->update([
            'status' => 'Sedang Bertemu', // Diseragamkan ke Bahasa Indonesia
            'meeting_start_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pertemuan dimulai. Silakan lakukan diskusi dengan tamu.');
    }

    // public function storeMeetingResult(Request $request, $id)
    // {
    //     $request->validate([
    //         'meeting_result' => 'nullable|string',
    //         'potential_level' => 'required|string',
    //         'follow_up_at' => 'nullable|date',
    //     ]);

    //     $visit = visits::findOrFail($id);

    //     // 1. Update data pada tabel visits
    //     $visit->update([
    //         'meeting_result' => $request->meeting_result,
    //         'potential_level' => $request->potential_level,
    //         'follow_up_at' => $request->follow_up_at,
    //         'is_converted_to_lead' => in_array($request->potential_level, ['warm', 'hot', 'deal']),
    //         'status' => 'completed',
    //         'check_out_at' => now(),
    //     ]);

    //     // 2. Buat Record otomatis di tabel follow_ups jika opsi membutuhkan Follow-Up (Warm/Hot)
    //     if (in_array($request->potential_level, ['warm', 'hot']) && $request->filled('follow_up_at')) {
    //         follow_ups::create([
    //             'visit_id' => $visit->id,
    //             'assigned_to' => auth()->id(),
    //             'due_at' => $request->follow_up_at,
    //             'result' => null, // Belum ada hasil follow-up awal
    //             'status' => 'pending', // Status awal pengingat
    //         ]);
    //     }

    //     return back()->with('success', 'Hasil pertemuan dan jadwal follow-up berhasil dicatat!');
    // }

    // public function leadsIndex(Request $request)
    // {
    //     // Hanya ambil data yang statusnya sudah 'deal'
    //     $leads = visits::with(['guest', 'followUps' => function ($query) {
    //             $query->orderBy('created_at', 'desc');
    //         }])
    //         ->where('assigned_to', auth()->id())
    //         ->where('potential_level', 'deal') // 👈 Ubah filter di sini menjadi 'deal' saja
    //         ->orderBy('updated_at', 'desc')
    //         ->paginate(10);

    //     $totalLeads = visits::where('assigned_to', auth()->id())
    //         ->where(function ($query) {
    //             $query->where('is_converted_to_lead', true)
    //                   ->orWhereNotNull('follow_up_at');
    //         })
    //         ->count();

    //     // Hitung total klien yang sudah deal
    //     $totalDeal = visits::where('assigned_to', auth()->id())
    //         ->where('potential_level', 'deal')
    //         ->count();

    //     return view('pic.leads', compact('leads', 'totalLeads', 'totalDeal'));
    // }
}
