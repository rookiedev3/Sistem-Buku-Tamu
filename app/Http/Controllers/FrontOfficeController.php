<?php

namespace App\Http\Controllers;

use App\Models\branches;
use App\Models\guest_categories;
use App\Models\guests;
use App\Models\lead_sources;
use App\Models\notifications;
use App\Models\products;
use App\Models\User;
use App\Models\users;
use App\Models\visit_purposes;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FrontOfficeController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();
        $userBranchId = auth()->user()->branch_id ?? null;

        // 1. Buat Query Dasar untuk Visits (Khusus Hari Ini & Filter Branch)
        $todayVisitsQuery = visits::whereDate('scheduled_at', $today);
        if ($userBranchId) {
            $todayVisitsQuery->where('branch_id', $userBranchId);
        }

        // 2. Hitung Total Tamu Hari Ini & Tamu Belum Selesai Hari Ini
        $totalToday = (clone $todayVisitsQuery)->count();

        $unfinishedTodayCount = (clone $todayVisitsQuery)
            ->whereNotIn('status', ['Selesai', 'completed', 'Dibatalkan', 'cancelled'])
            ->count();

        // 3. Eksekusi Pagination Data Visits
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = visits::with(['guest', 'purpose', 'assignedUser']);

        // Filter Cabang User Login
        if ($userBranchId) {
            $query->where('branch_id', $userBranchId);
        }

        if ($request->input('date_filter') === 'today') {
            $query->whereDate('scheduled_at', $today);
        } else {
            $query->whereDate('scheduled_at', '>=', $today);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($g) use ($keyword) {
                    $g->where('name', 'like', "%{$keyword}%")
                        ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('assignedUser', function ($u) use ($keyword) {
                    $u->where('name', 'like', "%{$keyword}%");
                })->orWhere('visit_code', 'like', "%{$keyword}%");
            });
        }

        $visits = $query->orderBy('scheduled_at', 'asc')
            ->paginate($perPage)
            ->appends($request->query());

        // 4. Data Pendukung Modal
        $pics = User::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::where('is_active', 1)->select('id', 'name')->get();
        $purposes = visit_purposes::where('is_active', 1)->select('id', 'name')->get();
        $guestCategories = guest_categories::select('id', 'name')->get();
        $products = products::where('is_active', 1)->select('id', 'name')->get();
        $leadSources = lead_sources::select('id', 'name')->get();

        // 5. Data Notifikasi
        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('frontoffice.dashboard', compact(
            'visits',
            'totalToday',
            'unfinishedTodayCount',
            'pics',
            'branches',
            'purposes',
            'guestCategories',
            'products',
            'leadSources',
            'notifications'
        ));
    }

    public function checkIn($id)
    {
        return DB::transaction(function () use ($id) {
            $visit = visits::where('id', $id)->lockForUpdate()->firstOrFail();

            if (in_array(strtolower($visit->status), ['menunggu', 'waiting', 'check-in', 'proses'])) {
                return redirect()->back()->with('info', 'Tamu sudah melakukan Check-in.');
            }

            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => $visit->status,
                'new_status' => 'Menunggu',
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);

            $visit->update([
                'status'      => 'Menunggu',
                'check_in_at' => now(),
            ]);

            $guestName   = $visit->guest->name ?? 'Tamu';
            $assignedPic = $visit->assignedUser;

            if ($assignedPic) {
                $title   = 'Tamu Anda Sudah Datang';
                $message = "Tamu {$guestName} telah check-in dan sedang menunggu untuk bertemu dengan Anda.";

                notifications::send(
                    $assignedPic->id,
                    'guest_arrived',
                    $title,
                    $message
                );

                $targetPhone = $assignedPic->phone ?? null;

                if (! empty($targetPhone)) {
                    $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
                    $waMessage = "*{$title}*\n\n" . $message;

                    try {
                        Http::withoutVerifying()
                            ->withHeaders([
                                'Authorization' => $token,
                            ])->post('https://api.fonnte.com/send', [
                                'target'  => $targetPhone,
                                'message' => $waMessage,
                            ]);
                    } catch (\Exception $e) {
                        \Log::error("Gagal mengirim WA ke PIC {$assignedPic->name} ({$targetPhone}): " . $e->getMessage());
                    }
                }
            }

            return redirect()->back()->with('success', 'Tamu berhasil Check-in!');
        });
    }

    public function checkOut($id)
    {
        $visit = visits::findOrFail($id);

        visit_status_logs::create([
            'visit_id' => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Selesai',
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->update([
            'status' => 'Selesai',
            'check_out_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tamu berhasil Check-out!');
    }

    public function storeManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'required|email|max:150',
            'guest_category_id' => 'required|exists:guest_categories,id',
            'assigned_to' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'product_id' => 'nullable|exists:products,id',
            'scheduled_at' => 'required|date',
            'notes' => 'required|string',
            'photo_path' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Cek Isian Anda: ' . implode(', ', $validator->errors()->all()));
        }

        $validated = $validator->validated();

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        $currentUserId = auth()->check() ? auth()->id() : null;

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($request->hasFile('photo_path')) {
                $photoPath = $request->file('photo_path')->store('photos', 'public');
            }

            $guest = guests::where('phone', $phone)->first();

            if ($guest) {
                $updateData = [
                    'name' => $validated['name'],
                    'company_name' => $validated['company_name'],
                    'position' => $validated['position'],
                    'address' => $validated['address'],
                    'email' => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                    'created_by' => $currentUserId,
                ];
                if ($photoPath) {
                    $updateData['photo_path'] = $photoPath;
                }
                unset($updateData['is_vip']);
                $guest->update($updateData);
            } else {
                $todayDate = Carbon::now()->format('Ymd');
                $prefixGuest = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequenceGuest = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guest = guests::create([
                    'guest_code' => $prefixGuest . $sequenceGuest,
                    'name' => $validated['name'],
                    'company_name' => $validated['company_name'],
                    'position' => $validated['position'],
                    'address' => $validated['address'],
                    'phone' => $phone,
                    'email' => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                    'photo_path' => $photoPath,
                    'is_vip' => 0,
                    'created_by' => $currentUserId,
                ]);
            }

            $checkInDateTime = Carbon::parse($validated['scheduled_at'])->format('Y-m-d H:i:s');
            $checkInDateOnly = Carbon::parse($validated['scheduled_at'])->format('Y-m-d');

            $todayVisitCount = visits::whereDate('scheduled_at', $checkInDateOnly)->count();
            $queueNumber = sprintf('%03d', $todayVisitCount + 1);

            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit . $sequenceVisit;

            $visit = visits::create([
                'visit_code' => $visitCode,
                'guest_id' => $guest->id,
                'assigned_to' => $validated['assigned_to'],
                'branch_id' => $validated['branch_id'],
                'purpose_id' => $validated['purpose_id'],
                'scheduled_at' => $checkInDateTime,
                'source_id' => $request->input('source_id'),
                'notes' => $validated['notes'],
                'status' => 'Terjadwal',
                'queue_number' => $queueNumber,
                'created_by' => $currentUserId,
            ]);

            if ($request->filled('product_id')) {
                DB::table('visit_products')->insert([
                    'visit_id' => $visit->id,
                    'product_id' => (int) $request->input('product_id'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            visit_status_logs::create([
                'visit_id' => $visit->id,
                'old_status' => null,
                'new_status' => 'Terjadwal',
                'changed_by' => $currentUserId,
                'changed_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil membuat antrian janji temu!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $userBranchId = auth()->user()->branch_id ?? null;

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereIn('status', ['Selesai', 'completed', 'Dibatalkan', 'cancelled']);

        // Filter Cabang User Login
        if ($userBranchId) {
            $query->where('branch_id', $userBranchId);
        }

        if ($request->filled('date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('check_out_at', $request->date)
                    ->orWhereDate('scheduled_at', $request->date);
            });
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($g) use ($keyword) {
                    $g->where('name', 'like', "%{$keyword}%")
                        ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('assignedUser', function ($u) use ($keyword) {
                    $u->where('name', 'like', "%{$keyword}%");
                })->orWhere('visit_code', 'like', "%{$keyword}%");
            });
        }

        $visits = $query->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        $filterDate = $request->query('date', '');

        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('frontoffice.history', compact('visits', 'filterDate', 'notifications'));
    }

    public function appointment(Request $request)
    {
        $today = Carbon::today();
        $userBranchId = auth()->user()->branch_id ?? null;

        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereDate('scheduled_at', $today);

        // Filter Cabang User Login
        if ($userBranchId) {
            $query->where('branch_id', $userBranchId);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('guest', function ($g) use ($keyword) {
                    $g->where('name', 'like', "%{$keyword}%")
                        ->orWhere('company_name', 'like', "%{$keyword}%");
                })->orWhereHas('assignedUser', function ($u) use ($keyword) {
                    $u->where('name', 'like', "%{$keyword}%");
                })->orWhere('visit_code', 'like', "%{$keyword}%");
            });
        }

        $visits = $query->orderBy('scheduled_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Hitung Statistik Berdasarkan Cabang User Login
        $totalTodayQuery = visits::whereDate('scheduled_at', $today);
        $waitingTodayQuery = visits::whereDate('scheduled_at', $today)
            ->whereIn('status', ['Menunggu', 'waiting', 'Check-in']);

        if ($userBranchId) {
            $totalTodayQuery->where('branch_id', $userBranchId);
            $waitingTodayQuery->where('branch_id', $userBranchId);
        }

        $totalToday = $totalTodayQuery->count();
        $waitingToday = $waitingTodayQuery->count();

        $pics = users::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::where('is_active', 1)->select('id', 'name')->get();
        $purposes = visit_purposes::where('is_active', 1)->select('id', 'name')->get();
        $guestCategories = guest_categories::select('id', 'name')->get();
        $products = products::where('is_active', 1)->select('id', 'name')->get();
        $leadSources = lead_sources::select('id', 'name')->get();

        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('frontoffice.appointment', compact(
            'visits',
            'totalToday',
            'waitingToday',
            'pics',
            'branches',
            'purposes',
            'guestCategories',
            'products',
            'leadSources',
            'notifications'
        ));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'company_name' => 'required|string|max:180',
            'phone' => 'required|string|max:25',
            'scheduled_at' => 'required|date',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'assigned_to' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        DB::transaction(function () use ($request, $phone) {
            $guest = guests::where('phone', $phone)->first();
            if (! $guest) {
                $todayDate = Carbon::now()->format('Ymd');
                $prefix = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequence = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guest = guests::create([
                    'guest_code' => $prefix . $sequence,
                    'name' => $request->name,
                    'phone' => $phone,
                    'company_name' => $request->company_name,
                ]);
            } else {
                $guest->update([
                    'name' => $request->name,
                    'company_name' => $request->company_name,
                ]);
            }

            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit . $sequenceVisit;

            $todayVisitCount = visits::whereDate('scheduled_at', Carbon::today())->count();
            $queueNumber = $todayVisitCount + 1;

            visits::create([
                'visit_code' => $visitCode,
                'guest_id' => $guest->id,
                'branch_id' => $request->branch_id,
                'purpose_id' => $request->purpose_id,
                'assigned_to' => $request->assigned_to,
                'scheduled_at' => $request->scheduled_at,
                'status' => 'waiting',
                'queue_number' => $queueNumber,
            ]);
        });

        return redirect()->back()->with('success', 'Janji temu baru berhasil disimpan!');
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,waiting,Menunggu,Disetujui,Ditolak',
        ]);

        $statusMap = [
            'confirmed' => 'confirmed',
            'Disetujui' => 'confirmed',
            'cancelled' => 'cancelled',
            'Ditolak' => 'cancelled',
            'waiting' => 'waiting',
            'Menunggu' => 'waiting',
        ];

        $dbStatus = $statusMap[$request->status] ?? $request->status;

        $visit = visits::findOrFail($id);
        $visit->update([
            'status' => $dbStatus,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status janji temu berhasil diperbarui!',
            'new_status' => $dbStatus,
        ]);
    }

    public function markAllNotificationsRead(Request $request)
    {
        notifications::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function markNotificationRead(Request $request, $id)
    {
        $notif = notifications::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['read_at' => now()]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function guest(Request $request)
    {
        // 1. Ambil nilai per_page dinamis (Default 10)
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        // 2. Query Data Tamu beserta Jumlah Kunjungannya
        $query = guests::withCount('visits');

        // Filter Kategori VIP (0 = Reguler, 1 = VIP)
        if ($request->has('vip') && $request->vip !== null && $request->vip !== '') {
            $query->where('is_vip', $request->vip);
        }

        // Filter Pencarian Keyword (Nama / Instansi / No HP / Jabatan)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('position', 'like', "%{$keyword}%");
            });
        }

        // 3. Eksekusi Pagination dengan Mempertahankan Query String
        $guests = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        $guestCategories = guest_categories::orderBy('name')->get();

        return view('frontoffice.listGuests', compact('guests', 'guestCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'phone'        => 'required|string|max:25',
            'email'        => 'nullable|email|max:150',
            'company_name' => 'nullable|string|max:180',
            'position'     => 'nullable|string|max:100',
            'guest_category_id'  => 'required|exists:guest_categories,id',
            'address'      => 'nullable|string',
            'is_vip'       => 'required|boolean',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $guestCode = 'GST-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('guests', 'public');
        }

        $phoneDigits = preg_replace('/\D/', '', $request->phone);
        if (str_starts_with($phoneDigits, '0')) {
            $phoneDigits = '62' . substr($phoneDigits, 1);
        } elseif (!str_starts_with($phoneDigits, '62')) {
            $phoneDigits = '62' . $phoneDigits;
        }
        $normalizedPhone = '+' . $phoneDigits;

        guests::create([
            'guest_code'   => $guestCode,
            'name'         => $request->name,
            'phone'        => $normalizedPhone,
            'email'        => $request->email,
            'company_name' => $request->company_name,
            'position'     => $request->position,
            'guest_category_id'  => $request->guest_category_id,
            'address'      => $request->address,
            'is_vip'       => $request->is_vip,
            'photo_path'   => $photoPath,
            'created_by'   => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data tamu berhasil ditambahkan!');
    }

    public function toggleVip(Request $request, $id)
    {
        $request->validate([
            'is_vip' => 'required|boolean',
        ]);

        $guest = guests::findOrFail($id);
        $guest->is_vip = $request->is_vip;
        $guest->save();

        return response()->json([
            'success' => true,
            'message' => 'Status VIP berhasil diperbarui',
            'is_vip' => $guest->is_vip,
        ]);
    }

    public function cancel($id)
    {
        $visit = visits::findOrFail($id);

        visit_status_logs::create([
            'visit_id' => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Dibatalkan',
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->update(['status' => 'Dibatalkan']);

        return redirect()->back()->with('success', 'Jadwal kunjungan berhasil dibatalkan.');
    }
}
