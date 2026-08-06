<?php

namespace App\Http\Controllers;

use App\Models\visits;
use App\Models\follow_ups;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FollowUpController extends Controller
{
    /**
     * Menampilkan halaman daftar follow-up aktif (Warm & Hot)
     */
    public function followupIndex(Request $request)
    {
        $leads = visits::with(['guest', 'followUps' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where('assigned_to', auth()->id())
            ->whereIn('potential_level', ['warm', 'hot']) 
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $totalLeads = visits::where('assigned_to', auth()->id())
            ->where(function ($query) {
                $query->where('is_converted_to_lead', true)
                      ->orWhereNotNull('follow_up_at');
            })
            ->count();

        $totalDeal = visits::where('assigned_to', auth()->id())
            ->where('potential_level', 'deal')
            ->count();

        return view('pic.followup', compact('leads', 'totalLeads', 'totalDeal'));
    }

    /**
     * Dashboard PIC & Manajemen Pertemuan
     */
public function dashboardPic()
    {
        $visits = visits::with(['guest', 'purpose', 'branch'])
            ->where('assigned_to', auth()->id())
            // Abaikan status yang sudah selesai atau dibatalkan baik EN/ID
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak']) 
            ->where(function ($query) {
                $query->whereIn('status', ['pending', 'waiting', 'Menunggu', 'confirmed', 'Disetujui', 'meeting'])
                      ->orWhereDate('check_in_at', Carbon::today())
                      ->orWhereDate('scheduled_at', Carbon::today());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $vipCount = $visits->filter(function ($v) {
            return optional($v->guest)->is_vip == true;
        })->count();

        $regularCount = $visits->count() - $vipCount;

        return view('pic.dashboard', compact('visits', 'vipCount', 'regularCount'));
    }

    /**
     * Riwayat Kunjungan PIC
     */
public function riwayatPic(Request $request)
    {
        $query = visits::with(['guest', 'purpose', 'branch'])
            ->where('assigned_to', auth()->id())
            // Tangkap status selesai/batal dalam bahasa Inggris maupun Indonesia
            ->whereIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak']);

        // Filter Pencarian (Nama Tamu / Perusahaan)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_at', '<=', $request->end_date);
        }

        $visits = $query->orderBy('check_in_at', 'desc')->paginate(10);

        return view('pic.riwayat', compact('visits'));
    }

    /**
     * Update Status Kehadiran / Kunjungan
     */
public function updateStatus(Request $request, $id)
    {
        // Validasi disesuaikan agar menerima nilai Inggris/Indonesia dari view
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,Dikonfirmasi,Dibatalkan'
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        // Mapping status ke Bahasa Indonesia untuk disimpan ke database
        $isConfirmed = in_array($request->status, ['confirmed', 'Dikonfirmasi']);
        
        $visit->status = $isConfirmed ? 'Dikonfirmasi' : 'Dibatalkan';

        if ($isConfirmed) {
            $visit->meeting_start_at = now();
        }

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
        
        $visit->update([
            // Status TIDAK diubah jadi 'completed', biarkan tetap atau pastikan 'meeting'
            'status'               => 'Meeting Selesai', // Diseragamkan ke Bahasa Indonesia
            'meeting_result'       => $request->notes ?? $request->meeting_result,
            'potential_level'      => $request->potential_level,
            'follow_up_at'         => $request->followup_date ?? $request->follow_up_at,
            'is_converted_to_lead' => in_array($request->potential_level, ['warm', 'hot', 'deal']),
            // 'check_out_at' dihilangkan/tidak diisi di sini karena di-checkout oleh admin nanti
        ]);

        if (in_array($request->potential_level, ['warm', 'hot']) && ($request->followup_date || $request->follow_up_at)) {
            follow_ups::create([
                'visit_id'    => $visit->id,
                'assigned_to' => auth()->id(),
                'due_at'      => $request->followup_date ?? $request->follow_up_at,
                'result'      => null,
                'status'      => 'pending',
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
            'visit_id'    => $visit->id,
            'assigned_to' => auth()->id(),
            'result'      => $request->result,
            'due_at'      => $request->due_at ?? now(),
            'status'      => $request->status === 'deal' ? 'completed' : 'pending',
        ]);

        return redirect()->route('pic.followup')->with('success', 'Status dan catatan follow-up berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman daftar klien yang sudah Deal (Leads)
     */
    public function leadsIndex(Request $request)
    {
        $leads = visits::with(['guest', 'followUps' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where('assigned_to', auth()->id())
            ->where('potential_level', 'deal') 
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $totalLeads = visits::where('assigned_to', auth()->id())
            ->where(function ($query) {
                $query->where('is_converted_to_lead', true)
                      ->orWhereNotNull('follow_up_at');
            })
            ->count();

        $totalDeal = visits::where('assigned_to', auth()->id())
            ->where('potential_level', 'deal')
            ->count();

        return view('pic.leads', compact('leads', 'totalLeads', 'totalDeal'));
    }

public function startMeeting($id)
    {
        $visit = visits::findOrFail($id);
        
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