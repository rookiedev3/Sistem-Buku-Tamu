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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontOfficeController extends Controller
{
    public function dashboard()
    {
        // Ambil data kunjungan hari ini
        $today = Carbon::today();

        $visits = visits::with(['guest', 'purpose', 'assignedUser'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Hitung statistik
        $totalToday = $visits->count();
        $waitingToday = $visits->whereIn('status', ['Menunggu', 'waiting'])->count();

        // Ambil data pendukung untuk modal input manual
        $pics = User::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::select('id', 'name')->get();
        $purposes = visit_purposes::select('id', 'name')->get();
        $guestCategories = guest_categories::select('id', 'name')->get();
        $products = products::select('code', 'name')->get();
        $leadSources = lead_sources::select('id', 'name')->get();

        // 3. Dengan Pagination (jika datanya banyak/halaman khusus notifikasi)
        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('frontoffice.dashboard', compact(
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

    public function checkIn($id)
    {
        $visit = visits::findOrFail($id);

        visit_status_logs::create([
            'visit_id' => $visit->id,
            'old_status' => $visit->status,
            'new_status' => 'Menunggu',
            'changed_by' => auth()->check() ? auth()->id() : null,
            'changed_at' => now(),
        ]);

        $visit->update([
            'status' => 'Menunggu',
            'check_in_at' => now(),
            'meeting_start_at' => now(),
        ]);

        // 1. Ambil semua user yang memiliki role 'admin'
        $picUsers = users::where('role', 'pic')->get();

        // 2. Looping untuk kirim notifikasi creke masing-masing admin
        foreach ($picUsers as $pic) {
            notifications::send(
                $pic->id,
                'guest_arrived',
                'Tamu Anda Sudah Datang 🔔',
                'Tamu '.($guest->name ?? 'Tamu').' telah check-in dan sedang menunggu untuk bertemu dengan Anda.'
            );
        }

        return redirect()->back()->with('success', 'Tamu berhasil Check-in!');
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
        $request->validate([
            // Step 1
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'email' => 'required|email|max:150',
            'guest_category_id' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Step 2
            'assigned_to' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'scheduled_at' => 'required',
            'product_interest' => 'nullable|string|max:255',
            'source_id' => 'nullable|exists:lead_sources,id',
            'notes' => 'required|string|max:1000',
        ]);

        // Sanitasi nomor telepon/WA
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+'.$phone;
        }

        // Handle profile photo upload
        $photoPath = null;
        if ($request->hasFile('photo_path')) {
            $photoPath = $request->file('photo_path')->store('photos', 'public');
        }

        DB::transaction(function () use ($request, $phone, $photoPath) {
            // 1. Cari / Buat Guest
            $guest = guests::where('phone', $phone)->first();

            $guestData = [
                'name' => $request->name,
                'company_name' => $request->company_name,
                'address' => $request->address,
                'email' => $request->email,
                'guest_category_id' => $request->guest_category_id,
                'position' => $request->position,
                'phone' => $phone,
            ];

            if ($photoPath) {
                $guestData['photo_path'] = $photoPath;
            }

            if ($guest) {
                $guest->update($guestData);
            } else {
                $todayDate = Carbon::now()->format('Ymd');
                $prefix = 'GST-'.$todayDate.'-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequence = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guestData['guest_code'] = $prefix.$sequence;
                if (! isset($guestData['photo_path'])) {
                    $guestData['photo_path'] = null;
                }
                $guest = guests::create($guestData);
            }

            // 2. Format Tanggal & Jam Check-In
            $rawCheckInDate = $request->scheduled_at ?? now();
            $checkInDateTime = Carbon::parse($rawCheckInDate)->format('Y-m-d H:i:s');
            $checkInDateOnly = Carbon::parse($rawCheckInDate)->format('Y-m-d');

            // 3. Hitung antrean berdasarkan tanggal kunjungan
            $todayVisitCount = visits::whereDate('scheduled_at', $checkInDateOnly)->count();
            $queueNumber = sprintf('%03d', $todayVisitCount + 1);

            // 4. Generate Visit Code
            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-'.$todayDate.'-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit.$sequenceVisit;

            // 5. Simpan ke tabel visits (status: Terjadwal)
            $newVisit = visits::create([
                'visit_code' => $visitCode,
                'guest_id' => $guest->id,
                'assigned_to' => $request->assigned_to,
                'branch_id' => $request->branch_id,
                'purpose_id' => $request->purpose_id,
                'scheduled_at' => $checkInDateTime,
                'product_interest' => $request->product_interest ?? null,
                'source_id' => $request->source_id ?? null,
                'notes' => $request->notes,
                'status' => 'Terjadwal',
                'queue_number' => $queueNumber,
            ]);

            // 6. Simpan Log Status Awal
            visit_status_logs::create([
                'visit_id' => $newVisit->id,
                'old_status' => null,
                'new_status' => 'Terjadwal',
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);

            // Ambil data pendukung untuk isi notifikasi
            $purposeType = visit_purposes::find($request->purpose_id);
            $branch = branches::find($request->branch_id);

            // 7. Ambil semua user yang memiliki role 'admin'
            $adminUsers = users::where('role', 'admin')->get();

            // 8. Kirim notifikasi ke masing-masing admin
            foreach ($adminUsers as $admin) {
                notifications::send(
                    $admin->id,
                    'guest_arrived',
                    'Notifikasi Admin 🔔',
                    'Tamu baru (manual) membuat jadwal pertemuan.'.
                        "\n".'Nama: '.($guest->name ?? '-').
                        "\n".'Instansi: '.($guest->company_name ?? '-').
                        "\n".'Tujuan: '.($purposeType->name ?? '-').
                        "\n".'Cabang: '.($branch->name ?? '-')
                );
            }
        });

        return redirect()->back()->with('success', 'Tamu manual berhasil didaftarkan ke antrian!');
    }

    public function history(Request $request)
    {
        $query = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereIn('status', ['Selesai', 'completed']);

        if ($request->has('date') && ! empty($request->date)) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $visits = $query->orderBy('scheduled_at', 'desc')->get();
        $filterDate = $request->query('date', '');

        return view('frontoffice.history', compact('visits', 'filterDate'));
    }

    public function appointment()
    {
        // Ambil data kunjungan hari ini
        $today = Carbon::today();

        $visits = visits::with(['guest', 'purpose', 'assignedUser'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Hitung statistik
        $totalToday = $visits->count();
        $waitingToday = $visits->whereIn('status', ['Menunggu', 'waiting'])->count();

        // Ambil data pendukung untuk modal input manual
        $pics = User::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::select('id', 'name')->get();
        $purposes = visit_purposes::select('id', 'name')->get();

        return view('frontoffice.appointment', compact('visits', 'totalToday', 'waitingToday', 'pics', 'branches', 'purposes'));
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

        // Sanitasi nomor telepon/WA
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+'.$phone;
        }

        DB::transaction(function () use ($request, $phone) {
            // Cari atau buat guest baru
            $guest = guests::where('phone', $phone)->first();
            if (! $guest) {
                $todayDate = Carbon::now()->format('Ymd');
                $prefix = 'GST-'.$todayDate.'-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequence = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guest = guests::create([
                    'guest_code' => $prefix.$sequence,
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

            // Generate visit code
            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-'.$todayDate.'-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit.$sequenceVisit;

            // Hitung nomor antrean
            $todayVisitCount = visits::whereDate('scheduled_at', Carbon::today())->count();
            $queueNumber = $todayVisitCount + 1;

            visits::create([
                'visit_code' => $visitCode,
                'guest_id' => $guest->id,
                'branch_id' => $request->branch_id,
                'purpose_id' => $request->purpose_id,
                'assigned_to' => $request->assigned_to,
                'scheduled_at' => $request->scheduled_at,
                'status' => 'waiting', // default status
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

        // Map status input ke database status standar
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
        $query = guests::query();

        if ($request->has('vip') && $request->vip !== null) {
            $query->where('is_vip', $request->vip);
        }

        $guests = $query->latest()->get();

        return view('frontoffice.listGuests', compact('guests'));
    }

    // 2. Menyimpan Data Tamu Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_vip' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('guests', 'public');
        }

        guests::create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'position' => $request->position,
            'phone' => $request->phone,
            'is_vip' => $request->is_vip,
            'photo_path' => $photoPath,
            'visits_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Data tamu berhasil ditambahkan!');
    }

    // 3. Fungsionalitas Toggle Status VIP via AJAX
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
