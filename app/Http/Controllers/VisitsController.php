<?php

namespace App\Http\Controllers;

use App\Models\branches;
use App\Models\guest_categories;
use App\Models\guests;
use App\Models\lead_sources;
use App\Models\notifications;
use App\Models\products;
use App\Models\users;
use App\Models\visit_purposes;
use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VisitsController extends Controller
{
    // ==========================================
    // STEP 1
    // ==========================================
    public function step1()
    {
        $step1Data = session('step1_data', []);
        $guestCategories = guest_categories::select('id', 'name')->get();

        return view('check-in.step1', compact('step1Data', 'guestCategories'));
    }

    public function storeStep1(Request $request)
    {
        $existingStep1 = session('step1_data', []);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'email' => 'required|email|max:150',
            'guest_category_id' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validatedData['is_vip'] = 0;

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        $validatedData['phone'] = $phone;

        $existingGuestInDb = guests::where('phone', $phone)->first();

        if ($request->hasFile('photo_path')) {
            if (! empty($existingStep1['photo_path']) && Storage::disk('public')->exists($existingStep1['photo_path'])) {
                Storage::disk('public')->delete($existingStep1['photo_path']);
            }

            $path = $request->file('photo_path')->store('photos', 'public');
            $validatedData['photo_path'] = $path;
        } else {
            $validatedData['photo_path'] = $existingStep1['photo_path']
                ?? ($existingGuestInDb->photo_path ?? null);
        }

        session(['step1_data' => $validatedData]);

        return redirect()->route('check-in.step2');
    }

    // ==========================================
    // STEP 2
    // ==========================================
    public function step2()
    {
        if (! session()->has('step1_data')) {
            return redirect()->route('check-in.step1')->with('error', 'Silakan isi data identitas terlebih dahulu.');
        }

        $step1Data = session('step1_data');
        $step2Data = session('step2_data', []);

        $pic = users::select('id', 'name')->where('role', 'pic')->get();
        $branches = branches::where('is_active', 1)->select('id', 'name')->get();
        $visitPurposes = visit_purposes::select('id', 'name')->get();
        $products = products::select('id', 'name')->get();
        $leadSources = lead_sources::select('id', 'name')->get();

        return view('check-in.step2', compact('step1Data', 'step2Data', 'pic', 'branches', 'visitPurposes', 'products', 'leadSources'));
    }

    public function storeStep2(Request $request)
    {
        if (! session()->has('step1_data')) {
            return redirect()->route('check-in.step1');
        }

        $validated = $request->validate([
            'assigned_to' => 'required',
            'branch_id' => 'required',
            'purpose_id' => 'required',
            'scheduled_at' => 'required',
            'product_interest' => 'nullable',
            'source_id' => 'nullable',
            'notes' => 'required|string|max:1000',
        ]);

        session(['step2_data' => $validated]);

        return redirect()->route('check-in.step3');
    }

    // ==========================================
    // STEP 3 (Konfirmasi & Final Save DB)
    // ==========================================
    public function step3()
    {
        if (! session()->has('step1_data') || ! session()->has('step2_data')) {
            return redirect()->route('check-in.step1')->with('error', 'Sesi Anda telah berakhir, silakan isi kembali.');
        }

        $step1Data = session('step1_data');
        $step2Data = session('step2_data');

        $pic = users::find($step2Data['assigned_to']);
        $branch = branches::find($step2Data['branch_id']);
        $purposeType = visit_purposes::find($step2Data['purpose_id']);

        $productIds = array_filter((array) ($step2Data['product_interest'] ?? []));
        $productNames = ! empty($productIds)
            ? products::whereIn('id', $productIds)->pluck('name')->implode(', ')
            : '-';

        $source = ! empty($step2Data['source_id']) ? lead_sources::find($step2Data['source_id']) : null;
        $guestCategory = ! empty($step1Data['guest_category_id']) ? guest_categories::find($step1Data['guest_category_id']) : null;

        return view('check-in.step3', compact(
            'step1Data',
            'step2Data',
            'pic',
            'branch',
            'purposeType',
            'source',
            'guestCategory',
            'productNames'
        ));
    }

    public function showStep3(Request $request)
    {
        $step1Data = session('step1_data', session('checkin_step1', []));
        $step2Data = session('step2_data', session('checkin_step2', []));

        if (empty($step1Data)) {
            return redirect()->route('check-in.step1')->with('error', 'Silakan isi identitas terlebih dahulu.');
        }

        $guestCategory = isset($step1Data['guest_category_id']) ? guest_categories::find($step1Data['guest_category_id']) : null;
        $pic = isset($step2Data['assigned_to']) ? users::find($step2Data['assigned_to']) : null;
        $branch = isset($step2Data['branch_id']) ? branches::find($step2Data['branch_id']) : null;
        $purposeType = isset($step2Data['purpose_id']) ? visit_purposes::find($step2Data['purpose_id']) : null;

        $productIds = array_filter((array) ($step2Data['product_interest'] ?? $step2Data['product_id'] ?? []));
        $productNames = ! empty($productIds)
            ? products::whereIn('id', $productIds)->pluck('name')->implode(', ')
            : '-';

        $source = isset($step2Data['source_id']) ? lead_sources::find($step2Data['source_id']) : null;

        return view('check-in.step3', compact(
            'step1Data',
            'step2Data',
            'guestCategory',
            'pic',
            'branch',
            'purposeType',
            'source',
            'productNames'
        ));
    }

    public function storeFinal(Request $request)
    {
        if (! session()->has('step1_data') || ! session()->has('step2_data')) {
            return redirect()->route('check-in.step1');
        }

        $step1 = session('step1_data');
        $step2 = session('step2_data');

        $visit = DB::transaction(function () use ($step1, $step2) {

            $guest = guests::where('phone', $step1['phone'])->first();

            if ($guest) {
                if (empty($step1['photo_path'])) {
                    unset($step1['photo_path']);
                }
                unset($step1['is_vip']);
                $guest->update($step1);
            } else {
                $todayDate = Carbon::now()->format('Ymd');
                $prefix = 'GST-' . $todayDate . '-';
                $todayGuestsCount = guests::whereDate('created_at', Carbon::today())->count();
                $sequence = str_pad($todayGuestsCount + 1, 4, '0', STR_PAD_LEFT);

                $step1['guest_code'] = $prefix . $sequence;
                $step1['is_vip'] = 0;
                $guest = guests::create($step1);
            }

            $rawCheckInDate = $step2['scheduled_at'] ?? now();
            $checkInDateTime = Carbon::parse($rawCheckInDate)->format('Y-m-d H:i:s');
            $checkInDateOnly = Carbon::parse($rawCheckInDate)->format('Y-m-d');

            $todayVisitCount = visits::whereDate('scheduled_at', $checkInDateOnly)->count();
            $queueNumber = sprintf('%03d', $todayVisitCount + 1);

            $todayDate = Carbon::now()->format('Ymd');
            $prefixVisit = 'VST-' . $todayDate . '-';
            $todayVisitsCount = visits::whereDate('created_at', Carbon::today())->count();
            $sequenceVisit = str_pad($todayVisitsCount + 1, 4, '0', STR_PAD_LEFT);
            $visitCode = $prefixVisit . $sequenceVisit;

            $newVisit = visits::create([
                'visit_code' => $visitCode,
                'guest_id' => $guest->id,
                'assigned_to' => $step2['assigned_to'],
                'branch_id' => $step2['branch_id'],
                'purpose_id' => $step2['purpose_id'],
                'scheduled_at' => $checkInDateTime,
                'source_id' => $step2['source_id'] ?? null,
                'notes' => $step2['notes'],
                'status' => 'Terjadwal',
                'queue_number' => $queueNumber,
            ]);

            // Simpan produk ke tabel relasi visit_products
            if (! empty($step2['product_interest'])) {
                $productIds = array_filter((array) $step2['product_interest']);

                $visitProducts = [];
                foreach ($productIds as $pId) {
                    $visitProducts[] = [
                        'visit_id'   => $newVisit->id,
                        'product_id' => $pId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (! empty($visitProducts)) {
                    DB::table('visit_products')->insert($visitProducts);
                }
            }

            visit_status_logs::create([
                'visit_id' => $newVisit->id,
                'old_status' => null,
                'new_status' => 'Terjadwal',
                'changed_by' => null,
                'changed_at' => now(),
            ]);

            $newVisit->status = 'Terjadwal';
            $newVisit->save();

            $purposeType = visit_purposes::find($step2['purpose_id']);
            $branch = branches::find($step2['branch_id']);

            $adminUsers = users::where('role', 'admin')->get();

            foreach ($adminUsers as $admin) {
                notifications::send(
                    $admin->id,
                    'guest_arrived',
                    'Notifikasi Admin',
                    'Tamu baru membuat jadwal pertemuan.' .
                        "\n" . 'Nama: ' . ($guest->name ?? '-') .
                        "\n" . 'Instansi: ' . ($guest->company_name ?? '-') .
                        "\n" . 'Tujuan: ' . ($purposeType->name ?? '-') .
                        "\n" . 'Cabang: ' . ($branch->name ?? '-')
                );
            }

            $token = env('FONNTE_TOKEN'); // Mengambil value token dari env

            // Isi pesan notifikasi ke WhatsApp
            $message = "*Notifikasi Admin*\n\n"
                . "Tamu baru membuat jadwal pertemuan.\n"
                . "Nama: " . ($guest->name ?? '-') . "\n"
                . "Instansi: " . ($guest->company_name ?? '-') . "\n"
                . "Tujuan: " . ($purposeType->name ?? '-') . "\n"
                . "Cabang: " . ($branch->name ?? '-');

            //Http::withoutVerifying()
            //   ->withHeaders([
            //        'Authorization' => $token,
            //    ])->post('https://api.fonnte.com/send', [
            //        'target'  => '085926276649',
            //        'message' => $message,
            //    ]);

            return $newVisit;
        });

        session()->forget(['step1_data', 'step2_data']);
        session(['final_visit_id' => $visit->id]);

        return redirect()->route('check-in.step4', ['id' => $visit->id]);
    }

    public function step4($id)
    {
        $visit = visits::findOrFail($id);

        return view('check-in.step4', compact('visit'));
    }

    public function dashboardPic()
    {
        $visits = visits::with(['guest', 'branch'])
            ->where('assigned_to', auth()->id())
            ->where(function ($query) {
                $query->whereDate('scheduled_at', Carbon::today())
                    ->orWhereIn('status', ['pending', 'waiting', 'confirmed']);
            })
            ->orderBy('scheduled_at', 'desc')
            ->get();

        $vipCount = $visits->filter(function ($v) {
            return optional($v->guest)->is_vip == true;
        })->count();

        $regularCount = $visits->count() - $vipCount;

        return view('pic.dashboard', compact('visits', 'vipCount', 'regularCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,Dikonfirmasi,Dibatalkan',
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $oldStatus = trim($visit->status ?? '');
        $isConfirmed = in_array($request->status, ['confirmed', 'Dikonfirmasi']);
        $newStatus = $isConfirmed ? 'Dikonfirmasi' : 'Dibatalkan';

        if (strtolower($oldStatus) === strtolower($newStatus)) {
            return back()->with('info', 'Status sudah sesuai, tidak ada perubahan.');
        }

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

    public function completeMeeting(Request $request, $id)
    {
        $request->validate([
            'meeting_result' => 'required|string',
            'potential_level' => 'required|string',
            'follow_up_at' => 'nullable|date',
        ]);

        $visit = visits::where('id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $oldStatus = trim($visit->status ?? '');
        $newStatus = 'completed';

        if (strtolower($oldStatus) !== strtolower($newStatus)) {
            visit_status_logs::create([
                'visit_id' => $visit->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);
        }

        $visit->update([
            'meeting_result' => $request->meeting_result,
            'potential_level' => $request->potential_level,
            'follow_up_at' => $request->follow_up_at,
            'status' => $newStatus,
            'check_out_at' => now(),
        ]);

        return back()->with('success', 'Hasil pertemuan berhasil disimpan!');
    }

    public function getPicsByBranch($branchId)
    {
        // Mengambil user dengan role 'pic' yang terikat dengan branch_id terpilih
        $pics = users::where('role', 'pic')
            ->where('branch_id', $branchId)
            ->select('id', 'name')
            ->get();

        return response()->json($pics);
    }
}
