<?php

namespace App\Http\Controllers;

use App\Models\guests;
use App\Models\visits;
use App\Models\users;
use App\Models\User;
use App\Models\branches;
use App\Models\visit_purposes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FrontOfficeController extends Controller
{
    public function dashboard()
    {
        // Ambil data kunjungan hari ini
        $today = Carbon::today();
        
        $visits = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereDate('scheduled_at', $today)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Hitung statistik
        $totalToday = $visits->count();
        $waitingToday = $visits->whereIn('status', ['Menunggu', 'waiting'])->count();

        // Ambil data pendukung untuk modal input manual
        $pics = User::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::select('id', 'name')->get();
        $purposes = visit_purposes::select('id', 'name')->get();

        return view('frontoffice.dashboard', compact('visits', 'totalToday', 'waitingToday', 'pics', 'branches', 'purposes'));
    }

    public function checkIn($id)
    {
        $visit = visits::findOrFail($id);
        $visit->update([
            'status' => 'Sedang Bertemu',
            'check_in_at' => now(),
            'meeting_start_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tamu berhasil Check-in!');
    }

    public function checkOut($id)
    {
        $visit = visits::findOrFail($id);
        $visit->update([
            'status' => 'Selesai',
            'check_out_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tamu berhasil Check-out!');
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'company_name' => 'required|string|max:180',
            'position' => 'required|string|max:100',
            'phone' => 'required|string|max:25',
            'assigned_to' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'purpose_id' => 'required|exists:visit_purposes,id',
        ]);

        // Sanitasi nomor telepon/WA
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        DB::transaction(function () use ($request, $phone) {
            // Cari atau buat guest baru
            $guest = guests::where('phone', $phone)->first();
            if (!$guest) {
                $todayDate = Carbon::now()->format('Ymd');
                $prefix = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequence = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $guest = guests::create([
                    'guest_code' => $prefix . $sequence,
                    'name' => $request->name,
                    'phone' => $phone,
                    'company_name' => $request->company_name,
                    'position' => $request->position,
                ]);
            } else {
                $guest->update([
                    'name' => $request->name,
                    'company_name' => $request->company_name,
                    'position' => $request->position,
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
                'scheduled_at' => now(),
                'status' => 'Menunggu',
                'queue_number' => $queueNumber,
            ]);
        });

        return redirect()->back()->with('success', 'Tamu manual berhasil didaftarkan ke antrian!');
    }

    public function history(Request $request)
    {
        $query = visits::with(['guest', 'purpose', 'assignedUser'])
            ->whereIn('status', ['Selesai', 'completed']);

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $visits = $query->orderBy('scheduled_at', 'desc')->get();
        $filterDate = $request->query('date', '');

        return view('frontoffice.history', compact('visits', 'filterDate'));
    }

    // ==========================================
    // PEGAWAI (PIC)
    // ==========================================
    public function pegawai()
    {
        // Ambil data pegawai (yang bukan tamu)
        $pegawaiList = User::where('role', 'pic')->get();
        $branches = branches::select('id', 'name')->get();

        return view('frontoffice.pegawai', compact('pegawaiList', 'branches'));
    }

    public function storePegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:25',
            'role' => 'required|in:owner,manager,admin,pic,security',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'password' => Hash::make('pegawai123'), // password default
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Pegawai baru berhasil ditambahkan!');
    }

    public function updatePegawai(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:25',
            'role' => 'required|in:owner,manager,admin,pic,security',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'branch_id' => $request->branch_id,
        ]);

        return redirect()->back()->with('success', 'Data pegawai berhasil diubah!');
    }

    public function appointment()
    {
        // Ambil semua data janji temu dari database
        $appointments = visits::with(['guest', 'purpose', 'assignedUser', 'branch'])
            ->orderBy('scheduled_at', 'desc')
            ->get();

        // Ambil data pendukung untuk modal
        $pics = User::where('role', 'pic')->select('id', 'name')->get();
        $branches = branches::select('id', 'name')->get();
        $purposes = visit_purposes::select('id', 'name')->get();

        return view('frontoffice.appointment', compact('appointments', 'pics', 'branches', 'purposes'));
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
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        DB::transaction(function () use ($request, $phone) {
            // Cari atau buat guest baru
            $guest = guests::where('phone', $phone)->first();
            if (!$guest) {
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
            'status' => 'required|in:confirmed,cancelled,waiting,Menunggu,Disetujui,Ditolak'
        ]);

        // Map status input ke database status standar
        $statusMap = [
            'confirmed' => 'confirmed',
            'Disetujui' => 'confirmed',
            'cancelled' => 'cancelled',
            'Ditolak' => 'cancelled',
            'waiting' => 'waiting',
            'Menunggu' => 'waiting'
        ];

        $dbStatus = $statusMap[$request->status] ?? $request->status;

        $visit = visits::findOrFail($id);
        $visit->update([
            'status' => $dbStatus,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status janji temu berhasil diperbarui!',
            'new_status' => $dbStatus
        ]);
    }
}
