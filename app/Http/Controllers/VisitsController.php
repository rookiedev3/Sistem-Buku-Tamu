<?php

namespace App\Http\Controllers;

use App\Models\guests;
use App\Models\visits;
use App\Models\users;
use App\Models\branches;
use App\Models\visit_purposes;
use App\Models\products;
use App\Models\lead_sources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VisitsController extends Controller
{
    // ==========================================
    // STEP 1
    // ==========================================
    public function step1()
    {
        // Ambil data sementara dari session jika pengguna menekan tombol kembali
        $step1Data = session('step1_data', []);

        return view('check-in.step1', compact('step1Data'));
    }

    public function storeStep1(Request $request)
    {
        $existingStep1 = session('step1_data', []);

        // 1. Validasi Input Step 1
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address'      => 'nullable|string|max:500',
            'position'     => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'photo'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Sanitasi Format WhatsApp
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '+62' . substr($phone, 1);
        }
        $validatedData['phone'] = $phone;

        // Cek apakah nomor WA ini sudah pernah terdaftar di database (Tamu Lama)
        $existingGuestInDb = guests::where('phone', $phone)->first();

        // 3. Handle Upload Foto
        if ($request->hasFile('photo')) {
            // Hapus file temporary lama di storage jika pengguna mengunggah ulang foto baru
            if (!empty($existingStep1['photo']) && Storage::disk('public')->exists($existingStep1['photo'])) {
                Storage::disk('public')->delete($existingStep1['photo']);
            }

            // Simpan foto baru ke folder temporary storage
            $path = $request->file('photo')->store('temp_photos', 'public');
            $validatedData['photo'] = $path;
        } else {
            // Jika tidak upload foto baru:
            // Priority 1: Pakai foto dari session step 1
            // Priority 2: Jika session kosong, pakai foto lama dari DB (Guest Eksisting)
            $validatedData['photo'] = $existingStep1['photo']
                ?? ($existingGuestInDb->photo ?? null);
        }

        // 4. Simpan Sementara ke Session (Belum ke DB)
        session(['step1_data' => $validatedData]);

        return redirect()->route('check-in.step2');
    }

    // ==========================================
    // STEP 2
    // ==========================================
    public function step2()
    {
        if (!session()->has('step1_data')) {
            return redirect()->route('check-in.step1')->with('error', 'Silakan isi data identitas terlebih dahulu.');
        }

        $step1Data = session('step1_data');
        $step2Data = session('step2_data', []);

        $pic           = users::select('id', 'name')->where('role', 'pic')->get();
        $branches      = branches::select('id', 'name', 'code')->get();
        $visitPurposes = visit_purposes::select('id', 'name')->get();
        $products      = products::select('code', 'name')->get();
        $leadSources   = lead_sources::select('id', 'name')->get();

        return view('check-in.step2', compact('step1Data', 'step2Data', 'pic', 'branches', 'visitPurposes', 'products', 'leadSources'));
    }

    public function storeStep2(Request $request)
    {
        if (!session()->has('step1_data')) {
            return redirect()->route('check-in.step1');
        }

        // 1. Validasi Input Step 2
        $validated = $request->validate([
            'assigned_to'      => 'required',
            'branch_id'        => 'required',
            'purpose_id'       => 'required',
            'check_in_at'      => 'required',
            'product_interest' => 'nullable',
            'source_info'      => 'nullable',
            'purpose'          => 'required|string|max:1000',
        ]);

        // 2. Simpan Sementara ke Session
        session(['step2_data' => $validated]);

        return redirect()->route('check-in.step3');
    }

    // ==========================================
    // STEP 3 (Konfirmasi & Final Save DB)
    // ==========================================
    public function step3()
    {
        if (!session()->has('step1_data') || !session()->has('step2_data')) {
            return redirect()->route('check-in.step1')->with('error', 'Sesi Anda telah berakhir, silakan isi kembali.');
        }

        $step1Data = session('step1_data');
        $step2Data = session('step2_data');

        $pic         = users::find($step2Data['assigned_to']);
        $branch      = branches::find($step2Data['branch_id']);
        $purposeType = visit_purposes::find($step2Data['purpose_id']);
        $product     = !empty($step2Data['product_interest']) ? products::where('code', $step2Data['product_interest'])->first() : null;
        $source      = !empty($step2Data['source_info']) ? lead_sources::find($step2Data['source_info']) : null;

        return view('check-in.step3', compact(
            'step1Data',
            'step2Data',
            'pic',
            'branch',
            'purposeType',
            'product',
            'source'
        ));
    }

    public function storeFinal(Request $request)
    {
        if (!session()->has('step1_data') || !session()->has('step2_data')) {
            return redirect()->route('check-in.step1');
        }

        $step1 = session('step1_data');
        $step2 = session('step2_data');

        $visit = DB::transaction(function () use ($step1, $step2) {

            // 1. Cari / Buat Guest berdasarkan Nomor WhatsApp
            $guest = guests::where('phone', $step1['phone'])->first();

            if ($guest) {
                if (empty($step1['photo'])) {
                    unset($step1['photo']);
                }
                $guest->update($step1);
            } else {
                $todayDate = Carbon::now()->format('Ymd');
                $prefix = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequence = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $step1['guest_code'] = $prefix . $sequence;
                $guest = guests::create($step1);
            }

            // 2. Format Tanggal & Jam Check-In
            $rawCheckInDate = $step2['check_in_at'] ?? now();

            // Menyimpan Tanggal + Jam lengkap ke DB (YYYY-MM-DD HH:MM:SS)
            $checkInDateTime = Carbon::parse($rawCheckInDate)->format('Y-m-d H:i:s');

            // Format khusus YYYY-MM-DD untuk query hitung antrean
            $checkInDateOnly = Carbon::parse($rawCheckInDate)->format('Y-m-d');

            // 3. Hitung Jumlah Antrean berdasarkan Tanggal Kunjungan
            $todayVisitCount = visits::whereDate('check_in_at', $checkInDateOnly)->count();
            $queueNumber = sprintf('%03d', $todayVisitCount + 1);

            // 4. Generate Visit Code
            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit . $sequenceVisit;

            // 5. Simpan ke Tabel Visits
            return visits::create([
                'visit_code'       => $visitCode,
                'guest_id'         => $guest->id,
                'assigned_to'      => $step2['assigned_to'],
                'branch_id'        => $step2['branch_id'],
                'purpose_id'       => $step2['purpose_id'],
                'check_in_at'      => $checkInDateTime, // 👈 Gunakan variabel yang mencakup jam & menit
                'product_interest' => $step2['product_interest'] ?? null,
                'source_info'      => $step2['source_info'] ?? null,
                'purpose'          => $step2['purpose'],
                'status'           => 'pending',
                'queue_number'     => $queueNumber,
            ]);
        });

        // Hapus session temporary
        session()->forget(['step1_data', 'step2_data']);
        session(['final_visit_id' => $visit->id]);

        return redirect()->route('check-in.step4', ['id' => $visit->id]);
    }

    public function step4($id)
    {
        $visit = visits::findOrFail($id);

        return view('check-in.step4', compact('visit'));
    }

        // ==========================================
    // DASHBOARD PIC & MANAJEMEN PERTEMUAN
    // ==========================================
public function dashboardPic()
{
    // Mengambil data milik PIC yang sedang login
    $visits = visits::with(['guest', 'purpose', 'branch'])
        ->where('assigned_to', auth()->id())
        ->where(function ($query) {
            // Tampilkan jika check-in hari ini ATAU statusnya masih menunggu/dikonfirmasi
            $query->whereDate('check_in_at', Carbon::today())
                  ->orWhereIn('status', ['pending', 'waiting', 'confirmed']);
        })
        ->orderBy('check_in_at', 'desc')
        ->get();

    $vipCount = $visits->filter(function ($v) {
        return optional($v->guest)->is_vip == true;
    })->count();

    $regularCount = $visits->count() - $vipCount;

    return view('pic.dashboard', compact('visits', 'vipCount', 'regularCount'));
}

    // Aksi untuk tombol Centang (✓) dan Silang (✕)
    public function updateStatus(Request $request, $id)
    {
// Uji coba hapus sementara whereDate() untuk melihat seluruh data:
$visits = visits::with(['guest', 'purpose', 'branch'])
    ->where('assigned_to', auth()->id())
    ->orderBy('check_in_at', 'desc')
    ->get();

        $request->validate([
            'status' => 'required|in:confirmed,cancelled'
        ]);

        $visit->status = $request->status;

        if ($request->status === 'confirmed') {
            $visit->meeting_start_at = now();
        }

        $visit->save();

        $msg = $request->status === 'confirmed'
            ? 'Kehadiran tamu dikonfirmasi. Silakan mulai pertemuan.'
            : 'Kunjungan telah dibatalkan.';

        return back()->with('success', $msg);
    }

    // Aksi untuk simpan data dari Modal Pertemuan
    public function completeMeeting(Request $request, $id)
    {
        $request->validate([
            'meeting_result'  => 'required|string',
            'potential_level' => 'required|string',
            'follow_up_at'    => 'nullable|date',
        ]);

        // Pastikan hanya data kunjungan milik PIC yang sedang login yang bisa di-complete
        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $visit->update([
            'meeting_result'  => $request->meeting_result,
            'potential_level' => $request->potential_level,
            'follow_up_at'    => $request->follow_up_at,
            'status'          => 'completed',
            'check_out_at'    => now(),
        ]);

        return back()->with('success', 'Hasil pertemuan berhasil disimpan!');
    }
}