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
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontOfficeController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // 1. Buat Query Dasar untuk Visits
        $visitQuery = visits::with(['guest', 'purpose', 'assignedUser']);

        // Opsional: Jika ingin membatasi antrian HANYA untuk hari ini saja, buka komentar baris di bawah:
        // $visitQuery->whereDate('scheduled_at', $today);

        // 2. Hitung Total Statistik SEBELUM di-paginate (agar jumlah statistik tidak berubah saat pindah halaman)
        $totalToday   = (clone $visitQuery)->count();
        $waitingToday = (clone $visitQuery)->whereIn('status', ['Menunggu', 'waiting', 'Terjadwal', 'scheduled'])->count();

        // 3. Eksekusi Pagination (10 data per halaman)
        $visits = $visitQuery->orderBy('scheduled_at', 'asc')->paginate(10);

        // 4. Data Pendukung Modal
        $pics            = User::where('role', 'pic')->select('id', 'name')->get();
        $branches        = branches::select('id', 'name')->get();
        $purposes        = visit_purposes::select('id', 'name')->get();
        $guestCategories = guest_categories::select('id', 'name')->get();
        $products        = products::select('id', 'name')->get(); // ⚠️ Diubah ke 'id' agar sesuai dengan value select di Blade
        $leadSources     = lead_sources::select('id', 'name')->get();

        // 5. Data Notifikasi
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
                'Tamu ' . ($guest->name ?? 'Tamu') . ' telah check-in dan sedang menunggu untuk bertemu dengan Anda.'
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
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'company_name'      => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'phone'             => 'required|string',
            'email'             => 'required|email|max:150',
            'guest_category_id' => 'required|exists:guest_categories,id',
            'assigned_to'       => 'required|exists:users,id',
            'branch_id'         => 'required|exists:branches,id',
            'purpose_id'        => 'required|exists:visit_purposes,id',
            'product_id'        => 'nullable',
            'scheduled_at'      => 'required|date',
            'notes'             => 'required|string',
            'photo_path'        => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Cek Isian Anda: ' . implode(', ', $validator->errors()->all()));
        }

        $validated = $validator->validated();

        // Sanitasi Format Nomor Telepon (+62...)
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        DB::beginTransaction();
        try {
            // 2. Handle Upload Foto Tamu
            $photoPath = null;
            if ($request->hasFile('photo_path')) {
                $photoPath = $request->file('photo_path')->store('photos', 'public');
            }

            // 3. Simpan / Update Data Tamu (Guest)
            $guest = guests::where('phone', $phone)->first();

            if ($guest) {
                $updateData = [
                    'name'              => $validated['name'],
                    'company_name'      => $validated['company_name'],
                    'position'          => $validated['position'],
                    'email'             => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                ];
                if ($photoPath) {
                    $updateData['photo_path'] = $photoPath;
                }
                $guest->update($updateData);
            } else {
                $todayDate = Carbon::now()->format('Ymd');
                $prefixGuest = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequenceGuest = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guest = guests::create([
                    'guest_code'        => $prefixGuest . $sequenceGuest,
                    'name'              => $validated['name'],
                    'company_name'      => $validated['company_name'],
                    'position'          => $validated['position'],
                    'phone'             => $phone,
                    'email'             => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                    'photo_path'        => $photoPath,
                ]);
            }

            // 4. Generate Tanggal, Queue Number, dan Visit Code
            $checkInDateTime = Carbon::parse($validated['scheduled_at'])->format('Y-m-d H:i:s');
            $checkInDateOnly = Carbon::parse($validated['scheduled_at'])->format('Y-m-d');

            $todayVisitCount = visits::whereDate('scheduled_at', $checkInDateOnly)->count();
            $queueNumber = sprintf('%03d', $todayVisitCount + 1);

            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit . $sequenceVisit;

            // 5. Simpan Data Kunjungan (Visit)
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
            ]);

            // 6. Simpan ke Tabel visit_products
            $inputProduct = $request->input('product_id') ?? $request->input('product_interest');

            if (!empty($inputProduct)) {
                DB::table('visit_products')->insert([
                    'visit_id'   => $visit->id,
                    'product_id' => (int) $inputProduct,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 7. Simpan Log Status Awal
            visit_status_logs::create([
                'visit_id'   => $visit->id,
                'old_status' => null,
                'new_status' => 'Terjadwal',
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);

            DB::commit();

            // 🟢 PERBAIKAN: Hapus .$product_id dari pesan success
            return redirect()->back()->with('success', 'Berhasil membuat antrian janji temu!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $query = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereIn('status', ['Selesai', 'completed']);

        if ($request->has('date') && ! empty($request->date)) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $visits = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereIn('status', ['Selesai', 'completed', 'checkout'])
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('check_out_at', $date);
            })
            ->latest('check_out_at')
            ->paginate(10)
            ->appends(request()->query()); // 👈 Penting agar parameter 'date' tetap ada saat berpindah halaman
        $filterDate = $request->query('date', '');

        return view('frontoffice.history', compact('visits', 'filterDate'));
    }

    public function appointment()
    {
        // Ambil data kunjungan hari ini
        $today = Carbon::today();

        $visits = visits::with(['guest', 'purpose', 'assignedUser'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        // Hitung statistik
        $totalToday = $visits->count();
        $waitingToday = $visits->whereIn('status', ['Menunggu', 'waiting'])->count();

        // Ambil seluruh data pendukung untuk modal input manual 3-Step
        $pics = User::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::select('id', 'name')->get();
        $purposes = visit_purposes::select('id', 'name')->get();
        $guestCategories = guest_categories::select('id', 'name')->get();
        $products = products::select('code', 'name')->get();
        $leadSources = lead_sources::select('id', 'name')->get();

        // Ambil data notifikasi untuk navbar/layout
        $notifications = notifications::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

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

        // Sanitasi nomor telepon/WA
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        DB::transaction(function () use ($request, $phone) {
            // Cari atau buat guest baru
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

            // Generate visit code
            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit . $sequenceVisit;

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
        $query = guests::withCount('visits');

        if ($request->filled('vip')) {
            $query->where('is_vip', $request->vip);
        }

        $guests = $query->latest()
            ->paginate(10)
            ->appends($request->query());

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
