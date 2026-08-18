<?php

namespace App\Http\Controllers\Api;

use App\Models\branches;
use App\Models\guest_categories;
use App\Models\guests;
use App\Models\lead_sources;
use App\Models\notifications;
use App\Models\products;
use App\Models\User;
use App\Models\visit_purposes;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminApiController extends BaseApiController
{
    /**
     * Data Dashboard & List Kunjungan (Search & Filter)
     */
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        $todayVisitsQuery = visits::whereDate('scheduled_at', $today);
        $totalToday = (clone $todayVisitsQuery)->count();
        $unfinishedTodayCount = (clone $todayVisitsQuery)
            ->whereNotIn('status', ['Selesai', 'completed', 'Dibatalkan', 'cancelled'])
            ->count();

        // 🟢 1. Hitung Notifikasi Belum Dibaca & Ambil 5 Notifikasi Terbaru
        $unreadNotifCount = notifications::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $perPage = (int) $request->input('per_page', 10);
        $query = visits::with(['guest', 'purpose', 'assignedUser', 'branch']);

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

        $visits = $query->orderBy('scheduled_at', 'asc')->paginate($perPage);

        // 🟢 2. Masukkan data notifikasi ke dalam respon JSON
        return $this->responseHasil(200, true, [
            'statistics' => [
                'total_today'          => $totalToday,
                'unfinished_today'     => $unfinishedTodayCount,
                'unread_notifications' => $unreadNotifCount, // 🟢 Ditambahkan
            ],
            'visits'        => $visits,
            'notifications' => $notifications, // 🟢 Ditambahkan
        ]);
    }

    /**
     * Master Data Dropdown
     */
    public function masterData()
    {
        $data = [
            'pics'             => User::where('role', 'pic')->select('id', 'name', 'branch_id')->get(),
            'branches'         => branches::where('is_active', 1)->select('id', 'name')->get(),
            'visit_purposes'   => visit_purposes::select('id', 'name')->get(),
            'guest_categories' => guest_categories::select('id', 'name')->get(),
            'products'         => products::select('id', 'name')->get(),
            'lead_sources'     => lead_sources::select('id', 'name')->get(),
        ];

        return $this->responseHasil(200, true, $data);
    }

    /**
     * Proses Check-in Kunjungan
     */
    public function checkIn($id)
    {
        return DB::transaction(function () use ($id) {
            $visit = visits::where('id', $id)->lockForUpdate()->first();

            if (! $visit) {
                return $this->responseHasil(404, false, "Data kunjungan tidak ditemukan.");
            }

            if (in_array(strtolower($visit->status), ['menunggu', 'waiting', 'check-in', 'proses'])) {
                return $this->responseHasil(400, false, "Tamu sudah melakukan Check-in.");
            }

            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => $visit->status,
                'new_status' => 'Menunggu',
                'changed_by' => auth()->id(),
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

                notifications::send($assignedPic->id, 'guest_arrived', $title, $message);

                $targetPhone = $assignedPic->phone ?? null;
                if (! empty($targetPhone)) {
                    $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
                    $waMessage = "*{$title}*\n\n" . $message;

                    try {
                        Http::withoutVerifying()
                            ->withHeaders(['Authorization' => $token])
                            ->post('https://api.fonnte.com/send', [
                                'target'  => $targetPhone,
                                'message' => $waMessage,
                            ]);
                    } catch (\Exception $e) {
                        Log::error("Gagal mengirim WA ke PIC {$assignedPic->name}: " . $e->getMessage());
                    }
                }
            }

            return $this->responseHasil(200, true, $visit->fresh(['guest', 'assignedUser', 'purpose']));
        });
    }

    /**
     * Proses Check-out Kunjungan
     */
    public function checkOut($id)
    {
        $visit = visits::find($id);

        if (! $visit) {
            return $this->responseHasil(404, false, "Data kunjungan tidak ditemukan.");
        }

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Selesai',
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        $visit->update([
            'status'       => 'Selesai',
            'check_out_at' => now(),
        ]);

        return $this->responseHasil(200, true, $visit->fresh());
    }

    /**
     * Pembatalan Kunjungan
     */
    public function cancel($id)
    {
        $visit = visits::find($id);

        if (! $visit) {
            return $this->responseHasil(404, false, "Data kunjungan tidak ditemukan.");
        }

        visit_status_logs::create([
            'visit_id'   => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Dibatalkan',
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        $visit->update(['status' => 'Dibatalkan']);

        return $this->responseHasil(200, true, $visit->fresh());
    }

    /**
     * Simpan Kunjungan / Antrian Manual
     */
    public function storeManual(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'company_name'      => 'required|string|max:255',
                'position'          => 'required|string|max:255',
                'address'           => 'required|string|max:255',
                'phone'             => 'required|string',
                'email'             => 'required|email|max:150',
                'guest_category_id' => 'required|exists:guest_categories,id',
                'assigned_to'       => 'required|exists:users,id',
                'branch_id'         => 'required|exists:branches,id',
                'purpose_id'        => 'required|exists:visit_purposes,id',
                'product_id'        => 'nullable|exists:products,id',
                'scheduled_at'      => 'required|date',
                'notes'             => 'required|string',
                'photo_path'        => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        $currentUserId = auth()->id();

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($request->hasFile('photo_path')) {
                $photoPath = $request->file('photo_path')->store('photos', 'public');
            }

            $guest = guests::where('phone', $phone)->first();

            if ($guest) {
                $updateData = [
                    'name'              => $validated['name'],
                    'company_name'      => $validated['company_name'],
                    'position'          => $validated['position'],
                    'address'           => $validated['address'],
                    'email'             => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                    'created_by'        => $currentUserId,
                ];
                if ($photoPath) {
                    $updateData['photo_path'] = $photoPath;
                }
                unset($updateData['is_vip']);
                $guest->update($updateData);
            } else {
                $todayDate        = Carbon::now()->format('Ymd');
                $prefixGuest      = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequenceGuest    = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guest = guests::create([
                    'guest_code'        => $prefixGuest . $sequenceGuest,
                    'name'              => $validated['name'],
                    'company_name'      => $validated['company_name'],
                    'position'          => $validated['position'],
                    'address'           => $validated['address'],
                    'phone'             => $phone,
                    'email'             => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                    'photo_path'        => $photoPath,
                    'is_vip'            => 0,
                    'created_by'        => $currentUserId,
                ]);
            }

            $checkInDateTime = Carbon::parse($validated['scheduled_at'])->format('Y-m-d H:i:s');
            $checkInDateOnly = Carbon::parse($validated['scheduled_at'])->format('Y-m-d');

            $todayVisitCount = visits::whereDate('scheduled_at', $checkInDateOnly)->count();
            $queueNumber     = sprintf('%03d', $todayVisitCount + 1);

            $todayDate        = Carbon::now()->format('Ymd');
            $prefixVisit      = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit    = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode        = $prefixVisit . $sequenceVisit;

            $visit = visits::create([
                'visit_code'   => $visitCode,
                'guest_id'     => $guest->id,
                'assigned_to'  => $validated['assigned_to'],
                'branch_id'    => $validated['branch_id'],
                'purpose_id'   => $validated['purpose_id'],
                'scheduled_at' => $checkInDateTime,
                'source_id'    => $request->input('source_id'),
                'notes'        => $validated['notes'],
                'status'       => 'Terjadwal',
                'queue_number' => $queueNumber,
                'check_in_at'  => now(),
                'created_by'   => $currentUserId,
            ]);

            if ($request->filled('product_id')) {
                DB::table('visit_products')->insert([
                    'visit_id'   => $visit->id,
                    'product_id' => (int) $request->input('product_id'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => null,
                'new_status' => 'Terjadwal',
                'changed_by' => $currentUserId,
                'changed_at' => now(),
            ]);

            DB::commit();

            return $this->responseHasil(200, true, [
                'visit_id'     => $visit->id,
                'visit_code'   => $visit->visit_code,
                'queue_number' => $visit->queue_number,
                'scheduled_at' => $visit->scheduled_at,
                'guest'        => $guest,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseHasil(500, false, "Gagal menyimpan data: " . $e->getMessage());
        }
    }

    /**
     * Riwayat Kunjungan
     */
    public function history(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $query   = visits::with(['guest', 'purpose', 'assignedUser', 'branch'])
            ->whereIn('status', ['Selesai', 'completed', 'Dibatalkan', 'cancelled']);

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

        $visits = $query->latest('updated_at')->paginate($perPage);

        return $this->responseHasil(200, true, $visits);
    }

    /**
     * Daftar Janji Temu Hari Ini
     */
    public function appointment(Request $request)
    {
        $today   = Carbon::today();
        $perPage = (int) $request->input('per_page', 10);

        $query = visits::with(['guest', 'purpose', 'assignedUser', 'branch'])
            ->whereDate('scheduled_at', $today);

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

        $visits       = $query->orderBy('scheduled_at', 'desc')->paginate($perPage);
        $totalToday   = visits::whereDate('scheduled_at', $today)->count();
        $waitingToday = visits::whereDate('scheduled_at', $today)
            ->whereIn('status', ['Menunggu', 'waiting', 'Check-in'])
            ->count();

        return $this->responseHasil(200, true, [
            'statistics' => [
                'total_today'   => $totalToday,
                'waiting_today' => $waitingToday,
            ],
            'appointments' => $visits,
        ]);
    }

    /**
     * Buat Janji Temu Baru
     */
    public function storeAppointment(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'         => 'required|string|max:150',
                'company_name' => 'required|string|max:180',
                'phone'        => 'required|string|max:25',
                'scheduled_at' => 'required|date',
                'purpose_id'   => 'required|exists:visit_purposes,id',
                'assigned_to'  => 'required|exists:users,id',
                'branch_id'    => 'required|exists:branches,id',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        try {
            $visit = DB::transaction(function () use ($validated, $phone) {
                $guest = guests::where('phone', $phone)->first();
                if (! $guest) {
                    $todayDate        = Carbon::now()->format('Ymd');
                    $prefix           = 'GST-' . $todayDate . '-';
                    $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                    $sequence         = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                    $guest = guests::create([
                        'guest_code'   => $prefix . $sequence,
                        'name'         => $validated['name'],
                        'phone'        => $phone,
                        'company_name' => $validated['company_name'],
                    ]);
                } else {
                    $guest->update([
                        'name'         => $validated['name'],
                        'company_name' => $validated['company_name'],
                    ]);
                }

                $todayDate        = Carbon::now()->format('Ymd');
                $prefixVisit      = 'VST-' . $todayDate . '-';
                $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
                $sequenceVisit    = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
                $visitCode        = $prefixVisit . $sequenceVisit;

                $todayVisitCount = visits::whereDate('scheduled_at', Carbon::today())->count();
                $queueNumber     = $todayVisitCount + 1;

                return visits::create([
                    'visit_code'   => $visitCode,
                    'guest_id'     => $guest->id,
                    'branch_id'    => $validated['branch_id'],
                    'purpose_id'   => $validated['purpose_id'],
                    'assigned_to'  => $validated['assigned_to'],
                    'scheduled_at' => $validated['scheduled_at'],
                    'status'       => 'waiting',
                    'queue_number' => $queueNumber,
                ]);
            });

            return $this->responseHasil(200, true, $visit);
        } catch (\Exception $e) {
            return $this->responseHasil(500, false, "Gagal membuat janji temu: " . $e->getMessage());
        }
    }

    /**
     * Update Status Janji Temu
     */
    public function updateAppointmentStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:confirmed,cancelled,waiting,Menunggu,Disetujui,Ditolak',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $statusMap = [
            'confirmed' => 'confirmed',
            'Disetujui' => 'confirmed',
            'cancelled' => 'cancelled',
            'Ditolak'   => 'cancelled',
            'waiting'   => 'waiting',
            'Menunggu'  => 'waiting',
        ];

        $dbStatus = $statusMap[$validated['status']] ?? $validated['status'];
        $visit    = visits::find($id);

        if (! $visit) {
            return $this->responseHasil(404, false, "Data kunjungan tidak ditemukan.");
        }

        $visit->update(['status' => $dbStatus]);

        return $this->responseHasil(200, true, $visit);
    }

    /**
     * List Tamu
     */
    public function guest(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $query   = guests::withCount('visits');

        if ($request->has('vip') && $request->vip !== null && $request->vip !== '') {
            $query->where('is_vip', $request->vip);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('position', 'like', "%{$keyword}%");
            });
        }

        $guests = $query->latest()->paginate($perPage);

        return $this->responseHasil(200, true, $guests);
    }

    /**
     * Tambah Data Tamu Baru
     */
    public function storeGuest(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'         => 'required|string|max:150',
                'phone'        => 'required|string|max:25',
                'email'        => 'nullable|email|max:150',
                'company_name' => 'nullable|string|max:180',
                'position'     => 'nullable|string|max:100',
                'address'      => 'nullable|string',
                'is_vip'       => 'required|boolean',
                'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $guestCode = 'GST-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $guest = guests::create([
            'guest_code'   => $guestCode,
            'name'         => $validated['name'],
            'phone'        => $validated['phone'],
            'email'        => $validated['email'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'position'     => $validated['position'] ?? null,
            'address'      => $validated['address'] ?? null,
            'is_vip'       => $validated['is_vip'],
            'photo_path'   => $photoPath,
            'created_by'   => auth()->id(),
        ]);

        return $this->responseHasil(200, true, $guest);
    }

    /**
     * Toggle VIP Status
     */
    public function toggleVip(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'is_vip' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return $this->responseHasil(400, false, $e->errors());
        }

        $guest = guests::find($id);

        if (! $guest) {
            return $this->responseHasil(404, false, "Data tamu tidak ditemukan.");
        }

        $guest->is_vip = $validated['is_vip'];
        $guest->save();

        return $this->responseHasil(200, true, $guest);
    }

    /**
     * List Notifikasi User
     */
    public function notifications()
    {
        $notifications = notifications::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->take(10)
            ->get();

        return $this->responseHasil(200, true, $notifications);
    }

    /**
     * Mark All Notifications Read
     */
    public function markAllNotificationsRead()
    {
        notifications::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->responseHasil(200, true, "Semua notifikasi ditandai sudah dibaca.");
    }

    /**
     * Mark Single Notification Read
     */
    public function markNotificationRead($id)
    {
        $notif = notifications::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $notif) {
            return $this->responseHasil(404, false, "Notifikasi tidak ditemukan.");
        }

        // Paksa update langsung dan simpan ke database
        $notif->read_at = now();
        $notif->save();

        return $this->responseHasil(200, true, "Notifikasi ditandai sudah dibaca.");
    }
}
