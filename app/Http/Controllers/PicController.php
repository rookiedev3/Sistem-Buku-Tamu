<?php

namespace App\Http\Controllers;

use App\Models\follow_ups;
use App\Models\leads;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PicController extends Controller
{
    // dashboard menampilkan kunjungan yang sedang berlangsung, menunggu, atau pending follow-up
public function dashboardPic(Request $request)
{
    $filter = $request->input('filter', 'all');
    $perPage = (int) $request->input('per_page', 10);
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
        $query->where(function ($q) use ($today) {
            $q->whereIn('status', ['pending', 'waiting', 'Menunggu', 'confirmed', 'Disetujui', 'meeting'])
              ->orWhereDate('check_in_at', $today)
              ->orWhereDate('scheduled_at', $today);
        });
    }

    $visits = $query->orderBy('created_at', 'desc')
        ->paginate($perPage)
        ->appends($request->query());

    // Kolom is_vip belum ada di database (masih tahap pengembangan bareng tim).
    // Cek dulu sebelum query, supaya tidak error dan otomatis aktif begitu kolomnya sudah ada.
    if (Schema::hasColumn('guests', 'is_vip')) {
        $vipCount = (clone $query)->whereHas('guest', fn($q) => $q->where('is_vip', true))->count();
        $regularCount = (clone $query)->count() - $vipCount;
    } else {
        $vipCount = 0;
        $regularCount = (clone $query)->count();
    }

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
     * Menampilkan halaman pipeline follow-up aktif (new/contacted/negotiation)
     */
    public function followupIndex(Request $request)
    {
        $today = Carbon::today();
        $filter = $request->input('filter', 'all');

        $query = leads::with(['guest', 'followUps' => fn($q) => $q->orderBy('created_at', 'desc')])
            ->where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost']); // yang masih aktif di pipeline

        if ($filter === 'today') {
            $query->whereDate('follow_up_at', $today);
        } elseif ($filter === 'overdue') {
            $query->whereDate('follow_up_at', '<', $today);
        } elseif ($filter === 'upcoming') {
            $query->whereDate('follow_up_at', '>', $today);
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

        // Semua badge counter sekarang konsisten dari tabel leads
        $totalLeads = leads::where('owner_id', auth()->id())->count();

        $totalDeal = leads::where('owner_id', auth()->id())
            ->where('status', 'deal')
            ->count();

        $countOverdue = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->whereDate('follow_up_at', '<', $today)
            ->count();

        $countToday = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->whereDate('follow_up_at', $today)
            ->count();

        $countAll = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->count();

        $countUpcoming = leads::where('owner_id', auth()->id())
            ->whereNotIn('status', ['deal', 'lost'])
            ->whereDate('follow_up_at', '>', $today)
            ->count();

        return view('pic.leads', compact('leads', 'totalLeads', 'totalDeal', 'filter', 'countOverdue', 'countToday', 'countAll', 'countUpcoming'));
    }

    /**
     * Riwayat Kunjungan PIC
     */
public function riwayatPic(Request $request)
{
    $perPage = (int) $request->input('per_page', 10);
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ], [
        'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
    ]);

// riwayatPic()
$query = visits::with(['guest', 'purpose', 'branch', 'lead.followUps'])
    ->where('assigned_to', auth()->id())
    ->whereIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Meeting Selesai']);

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
        ->paginate($perPage)
        ->appends($request->query());

    return view('pic.riwayat', compact('visits'));
}

    /**
     * Update Status Kehadiran / Kunjungan
     */
    public function updateStatus(Request $request, $id)
    {
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
     * Selesaikan Pertemuan & Catat Hasil Diskusi.
     * potential_level di sini menentukan APAKAH visit ini layak dikonversi jadi lead
     * (bukan tahap pipeline-nya — itu diatur belakangan lewat updateFollowUp).
     */
    public function completeMeeting(Request $request, $id)
    {
        $request->validate([
            'potential_level' => 'required|string',
            'estimated_value' => 'nullable|numeric',
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Meeting Selesai',
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->update([
            'status' => 'Meeting Selesai',
            'meeting_result' => $request->notes ?? $request->meeting_result,
            'potential_level' => $request->potential_level,
            'follow_up_at' => $request->followup_date ?? $request->follow_up_at,
            'is_converted_to_lead' => in_array($request->potential_level, ['warm', 'hot']),
        ]);

        if (in_array($request->potential_level, ['warm', 'hot'])) {
            leads::updateOrCreate(
                ['visit_id' => $visit->id],
                [
                    'guest_id' => $visit->guest_id,
                    'owner_id' => auth()->id(),
                    'status' => 'new',
                    'estimated_value' => $request->estimated_value ?? 0,
                    'follow_up_at' => $request->followup_date ?? $request->follow_up_at,
                ]
            );
        }

        return redirect()->back()->with('success', 'Catatan hasil pertemuan dan data lead berhasil disimpan!');
    }

    /**
     * Update Tahap Pipeline Lead dari Modal (Daftar Follow-Up)
     */
    public function updateFollowUp(Request $request, $leadId)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,negotiation,deal,lost',
            'result' => 'required|string',
            'due_at' => 'nullable|date',
            'estimated_value' => 'nullable|numeric|min:0',
        ]);

        $lead = leads::where('id', $leadId)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

            if ($lead->status === 'deal') {
        return redirect()->back()->with('error', 'Lead ini sudah Deal dan tidak bisa diubah lagi.');
    }

        $lead->status = $request->status;
        $lead->follow_up_at = in_array($request->status, ['deal', 'lost']) ? null : $request->due_at;
        // Kosongkan input = nilai lama dipertahankan, bukan direset ke 0
        $lead->estimated_value = $request->filled('estimated_value')
            ? $request->estimated_value
            : $lead->estimated_value;
        $lead->save();

        follow_ups::create([
            'lead_id' => $lead->id,
            'visit_id' => $lead->visit_id,
            'assigned_to' => auth()->id(),
            'due_at' => $request->due_at ?? now(),
            'result' => $request->result,
            'status' => $request->status,
            // Simpan nilai deal yang berlaku SAAT update ini dibuat (baik nilai baru maupun nilai lama yang dipertahankan),
            // supaya riwayat tiap update tetap punya jejak nilainya sendiri-sendiri.
            'estimated_value' => $lead->estimated_value,
        ]);

        return redirect()->route('pic.followup')->with('success', 'Status pipeline lead berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman daftar klien yang sudah Deal (Leads)
     */
    public function leadsIndex(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $today   = Carbon::today();
        $filter  = $request->input('filter', 'active');
        $ownerId = auth()->id();

        // Base query: selalu exclude status 'lost' dari halaman ini
        $query = leads::with(['guest', 'visit', 'followUps'])
            ->where('owner_id', $ownerId)
    ->whereNotIn('status', ['deal', 'lost']);   // ganti dari '!= lost' aja
    
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
            ->paginate($perPage)
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
            'status' => 'Sedang Bertemu',
            'meeting_start_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pertemuan dimulai. Silakan lakukan diskusi dengan tamu.');
    }
}