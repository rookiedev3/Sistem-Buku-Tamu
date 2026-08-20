<?php

namespace App\Http\Controllers;

use App\Models\follow_ups;
use App\Models\leads;
use App\Models\notifications;
use App\Models\User;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PicController extends Controller
{
    // dashboard menampilkan kunjungan yang sedang berlangsung, menunggu, atau pending follow-up
    public function dashboardPic(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $vipFilter = $request->input('vip_status', 'all');
        $keyword = trim((string) $request->input('keyword', ''));
        $perPage = (int) $request->input('per_page', 10);
        $today = Carbon::today();

        $query = visits::with(['guest.category', 'purpose', 'branch'])
            ->where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan']);

        if ($filter === 'today') {
            $query->where(function ($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            });
        } elseif ($filter === 'upcoming') {
            $query->whereDate('scheduled_at', '>', $today);
        }

        if ($keyword !== '') {
            $query->whereHas('guest', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($vipFilter === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($vipFilter === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        if (Schema::hasColumn('guests', 'is_vip')) {
            $vipCount = (clone $query)->whereHas('guest', fn($q) => $q->where('is_vip', true))->count();
            $regularCount = (clone $query)->whereHas('guest', function ($q) {
                $q->where('is_vip', false)->orWhereNull('is_vip');
            })->count();

            // Urutan diprioritaskan berdasarkan TANGGAL KUNJUNGAN dulu (yang paling dekat di atas),
            // baru kalau tanggalnya sama, tamu VIP ditampilkan lebih dulu daripada Reguler.
            $query->leftJoin('guests', 'visits.guest_id', '=', 'guests.id')
                ->select('visits.*')
                ->orderByRaw('COALESCE(visits.check_in_at, visits.scheduled_at) ASC')
                ->orderByRaw('CASE WHEN guests.is_vip = 1 THEN 0 ELSE 1 END ASC')
                ->orderBy('visits.created_at', 'asc');
        } else {
            $vipCount = 0;
            $regularCount = (clone $query)->count();

            $query->orderByRaw('COALESCE(check_in_at, scheduled_at) ASC')
                ->orderBy('created_at', 'asc');
        }

        $visits = $query->paginate($perPage)->appends($request->query());

        $countToday = visits::where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan'])
            ->where(function ($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                    ->orWhereDate('scheduled_at', $today);
            })->count();

        $countUpcoming = visits::where('assigned_to', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Dibatalkan', 'dibatalkan'])
            ->whereDate('scheduled_at', '>', $today)
            ->count();

        $payload = compact(
            'visits',
            'vipCount',
            'regularCount',
            'filter',
            'vipFilter',
            'countToday',
            'countUpcoming'
        );

        if ($request->ajax()) {
            return view('pic.partials._dashboard_panel', $payload);
        }

        return view('pic.dashboard', $payload);
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
            ->whereNotIn('status', ['deal', 'lost']);

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
        $vipFilter = $request->input('vip_status', 'all');
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
        ]);

        $query = visits::with(['guest', 'purpose', 'branch', 'lead.followUps'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['completed', 'cancelled', 'Selesai', 'Ditolak', 'Meeting Selesai', 'Dibatalkan', 'dibatalkan']);

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
            ->paginate($perPage)
            ->appends($request->query());

        return view('pic.riwayat', compact('visits', 'vipFilter'));
    }

    /**
     * Update Status Kehadiran / Kunjungan
     */
    public function updateStatus(Request $request, $id)
    {
        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $oldStatus = trim($visit->status ?? '');

        $isConfirmed = in_array($request->status, ['confirmed', 'Dikonfirmasi']);
        $newStatus = $isConfirmed ? 'Dikonfirmasi' : 'Dibatalkan';

        $terminalStatuses = ['meeting selesai', 'selesai', 'dibatalkan', 'completed', 'cancelled'];
        if (in_array(strtolower($oldStatus), $terminalStatuses)) {
            return back()->with('error', 'Status sudah akhir dan tidak dapat diubah lagi.');
        }

        if (strtolower($oldStatus) === strtolower($newStatus)) {
            return back()->with('info', 'Status sudah sesuai, tidak ada perubahan.');
        }

        $visit->status = $newStatus;
        $visit->updated_by = auth()->id();

        if ($isConfirmed) {
            $visit->meeting_start_at = now();
        }

        $visit->save();

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Selesaikan Pertemuan & Catat Hasil Diskusi.
     *
     * Gabungan v1 + v2:
     * - Dari v2: opsi potential_level 'deal' (dengan validasi nilai deal > 0),
     *   dan penguncian hasil pertemuan (tidak bisa diedit lagi setelah status
     *   'Meeting Selesai') supaya PIC tidak bisa mengganti potential_level
     *   (mis. deal -> non_lead) setelah lead terbentuk.
     * - Dari v1: pengiriman notifikasi DB & WhatsApp (Fonnte, masih nonaktif)
     *   ke manager/owner/admin saat lead baru pertama kali terbentuk.
     */
    public function completeMeeting(Request $request, $id)
    {
        $visit = visits::with(['guest', 'lead'])
            ->where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $oldStatus = trim($visit->status ?? '');

        // 🔒 Sekali dicatat, hasil pertemuan tidak bisa diubah lagi —
        // mencegah PIC ganti potential_level (misal deal → non_lead) setelah lead sudah terbentuk.
        if (strtolower($oldStatus) === 'meeting selesai') {
            return back()->with('error', 'Hasil pertemuan sudah pernah dicatat dan tidak bisa diubah lagi.');
        }

        // 🛠️ Sanitasi Input Rupiah: Bersihkan karakter selain angka jika yang terkirim berformat Rupiah
        if ($request->has('estimated_value') && !empty($request->estimated_value)) {
            $cleanedValue = preg_replace('/[^0-9]/', '', $request->estimated_value);
            $request->merge([
                'estimated_value' => $cleanedValue !== '' ? (float) $cleanedValue : null,
            ]);
        }

        $request->validate([
            'meeting_result'  => 'required|string',
            'potential_level' => 'required|in:hot,warm,cold,non_lead,deal',
            'follow_up_at' => 'nullable|date|required_unless:potential_level,cold,non_lead,deal',
            'estimated_value' => 'nullable|numeric|min:0',
        ], [
            'follow_up_at.required_unless' => 'Tanggal follow-up wajib dipilih sebelum menyimpan.',
            'estimated_value.numeric'      => 'Estimasi nilai harus berupa angka yang valid.',
        ]);

        // Nilai final = input baru (kalau diisi) atau nilai lama dari lead yang sudah ada
        $existingEstValue = $visit->lead->estimated_value ?? 0;
        $finalEstValue = $request->filled('estimated_value') ? (float) $request->estimated_value : (float) $existingEstValue;

        // 🚫 Cegah Deal tanpa estimasi nilai yang valid (> 0).
        if ($request->potential_level === 'deal' && $finalEstValue <= 0) {
            return back()->withInput()->with('error', 'Estimasi Nilai Deal wajib diisi (lebih dari Rp 0) sebelum bisa ditandai Deal.');
        }

        $newStatus = 'Meeting Selesai';

        // 1. Catat log perubahan status
        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        // 2. Update data kunjungan
        $visit->update([
            'status'               => $newStatus,
            'meeting_result'       => $request->meeting_result,
            'potential_level'      => $request->potential_level,
            'follow_up_at'         => $request->follow_up_at,
            'is_converted_to_lead' => in_array($request->potential_level, ['warm', 'hot', 'deal']),
            'updated_by'           => auth()->id(),
        ]);

        // 3. PROSES LEAD (Berjalan jika potensi Warm/Hot/Deal)
        if (in_array($request->potential_level, ['warm', 'hot', 'deal'])) {
            $isDeal = $request->potential_level === 'deal';

            // updateOrCreate otomatis membuat baru jika belum ada, atau mengupdate jika sudah ada
            $lead = leads::updateOrCreate(
                ['visit_id' => $visit->id],
                [
                    'guest_id'        => $visit->guest_id,
                    'owner_id'        => auth()->id(),
                    'status'          => $isDeal ? 'deal' : 'new',
                    'estimated_value' => $finalEstValue,
                    'follow_up_at'    => $isDeal ? null : $request->follow_up_at,
                ]
            );

            // Kirim Notifikasi WA & DB
            // Catatan: Jika ingin notifikasi terkirim baik saat baru dibuat maupun saat di-update, 
            // biarkan kondisi ini atau sesuaikan bila hanya untuk lead baru ($lead->wasRecentlyCreated)
            $managers = User::whereIn('role', ['manager', 'owner', 'admin'])->get();

            $guestName      = $visit->guest->name ?? 'Tamu';
            $companyName    = $visit->guest->company_name ?? 'Instansi';
            $formattedValue = 'Rp ' . number_format($finalEstValue, 0, ',', '.');
            $picName        = auth()->user()->name ?? 'PIC';
            $potensiText    = strtoupper($request->potential_level);

            $title   = "Lead Baru Masuk: {$guestName}";
            $message = implode("\n", [
                "Terdapat Lead baru dari {$guestName} ({$companyName})",
                "• Potensi: {$potensiText}",
                "• Est. Nilai: {$formattedValue}",
                "• PIC: {$picName}",
            ]);

            // 1. Notifikasi Database Internal
            foreach ($managers as $manager) {
                notifications::send($manager->id, 'new_lead', $title, $message);
            }

            // 2. Notifikasi WhatsApp Fonnte (Mengirim ke nomor HP unik milik Manager/Owner/Admin)
            $targetPhones = $managers->pluck('phone')->filter()->unique();

            if (!$targetPhones->isEmpty()) {
                $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
                $waMessage = "*{$title}*\n\n" . $message;

                foreach ($targetPhones as $phone) {
                    try {
                        Http::withoutVerifying()
                            ->withHeaders([
                                'Authorization' => $token,
                            ])->post('https://api.fonnte.com/send', [
                                'target'  => $phone,
                                'message' => $waMessage,
                            ]);
                    } catch (\Exception $e) {
                        
                    }
                }
            }
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

        // Nilai final = input baru (kalau diisi) atau nilai lama yang tersimpan di lead
        $finalEstimatedValue = $request->filled('estimated_value')
            ? $request->estimated_value
            : $lead->estimated_value;

        // 🚫 Cegah Deal tanpa estimasi nilai yang valid (> 0).
        // PENTING: pakai perbandingan numerik, BUKAN empty()/truthy check —
        // kalau kolomnya di-cast 'decimal' di model, nilai 0 akan berbentuk
        // string "0.00" yang truthy di PHP dan lolos dari empty().
        if ($request->status === 'deal' && (float) $finalEstimatedValue <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Estimasi Nilai Deal wajib diisi (lebih dari Rp 0) sebelum lead bisa ditandai Deal.');
        }

        $lead->status = $request->status;
        $lead->follow_up_at = in_array($request->status, ['deal', 'lost']) ? null : $request->due_at;
        $lead->estimated_value = $finalEstimatedValue;
        $lead->save();

        follow_ups::create([
            'lead_id' => $lead->id,
            'visit_id' => $lead->visit_id,
            'assigned_to' => auth()->id(),
            'due_at' => $request->due_at ?? now(),
            'result' => $request->result,
            'status' => $request->status,
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
        $vipFilter = $request->input('vip_status', 'all');
        $ownerId = auth()->id();

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
        }

        if ($request->filled('start_date')) {
            $query->whereDate('follow_up_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('follow_up_at', '<=', $request->end_date);
        }

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
            ->paginate($perPage)
            ->appends($request->query());

        $baseCount = function () use ($ownerId, $vipFilter) {
            $q = leads::where('owner_id', $ownerId)->where('status', '!=', 'lost');

            // Samakan filter VIP dengan $query di atas, supaya angka di badge tab
            // (Semua/Aktif/Deal/dst) selalu sesuai dengan isi tabel yang sedang
            // ditampilkan — sebelumnya count ini abai terhadap vip_status, jadi
            // badge bisa menunjukkan angka > 0 padahal tabel kosong.
            if (Schema::hasColumn('guests', 'is_vip')) {
                if ($vipFilter === 'vip') {
                    $q->whereHas('guest', fn($gq) => $gq->where('is_vip', true));
                } elseif ($vipFilter === 'reguler') {
                    $q->whereHas('guest', function ($gq) {
                        $gq->where('is_vip', false)->orWhereNull('is_vip');
                    });
                }
            }

            return $q;
        };

        $countAll      = $baseCount()->count();
        $countActive   = $baseCount()->whereNotIn('status', ['deal', 'lost'])->count();
        $countOverdue  = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '<', $today)->count();
        $countToday    = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', $today)->count();
        $countUpcoming = $baseCount()->whereNotIn('status', ['deal', 'lost'])->whereDate('follow_up_at', '>', $today)->count();
        $countDeal     = $baseCount()->where('status', 'deal')->count();

        return view('pic.leads', compact(
            'leads',
            'filter',
            'vipFilter',
            'countAll',
            'countActive',
            'countOverdue',
            'countToday',
            'countUpcoming',
            'countDeal'
        ));
    }

    public function startMeeting($id)
    {
        $visit = visits::findOrFail($id);

        $oldStatus = trim($visit->status ?? '');
        $newStatus = 'Sedang Bertemu';

        if (strtolower($oldStatus) !== strtolower($newStatus)) {
            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);
        }

        $visit->update([
            'status' => $newStatus,
            'meeting_start_at' => $visit->meeting_start_at ?? now(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Pertemuan dimulai. Silakan lakukan diskusi dengan tamu.');
    }
}