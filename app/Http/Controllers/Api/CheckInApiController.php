<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CheckInApiController extends Controller
{
    /**
     * Get Master Data untuk Form Step 1 & Step 2 (Flutter)
     */
    public function getFormData()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data formulir check-in berhasil dimuat.',
            'data'    => [
                'guest_categories' => guest_categories::select('id', 'name')->get(),
                'pics' => users::select('id', 'name', 'branch_id') // 🟢 Tambahkan 'branch_id'
                    ->whereIn(DB::raw('LOWER(role)'), ['pic'])
                    ->orderBy('name', 'asc')
                    ->get(),
                'branches'         => branches::select('id', 'name', 'code')->get(),
                'visit_purposes'   => visit_purposes::select('id', 'name')->get(),
                'products'         => products::select('id', 'name')->get(),
                'lead_sources'     => lead_sources::select('id', 'name')->get(),
            ],
        ], 200);
    }

    /**
     * Validasi Step 1 (Opsional)
     */
    public function validateStep1(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'company_name'      => 'required|string|max:255',
            'address'           => 'nullable|string|max:500',
            'email'             => 'required|email|max:150',
            'guest_category_id' => 'required|exists:guest_categories,id',
            'position'          => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'photo_path'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Step 1 valid.',
            'data'    => $validated,
        ], 200);
    }

    /**
     * Process & Submit Final Check-In (Gabungan Step 1 & Step 2)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'company_name'      => 'required|string|max:255',
            'address'           => 'nullable|string|max:500',
            'email'             => 'required|email|max:150',
            'guest_category_id' => 'required|exists:guest_categories,id',
            'position'          => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'photo_path'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'assigned_to'       => 'required|exists:users,id',
            'branch_id'         => 'required|exists:branches,id',
            'purpose_id'        => 'required|exists:visit_purposes,id',
            'scheduled_at'      => 'required|date',
            'product_interest'  => 'nullable|array',
            'product_interest.*' => 'exists:products,id',
            'source_id'         => 'nullable|exists:lead_sources,id',
            'notes'             => 'required|string|max:1000',
        ]);

        // 2. Normalisasi Nomor Telepon (+62...)
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        // 3. Handling Upload Foto
        $photoPath = null;
        if ($request->hasFile('photo_path')) {
            $photoPath = $request->file('photo_path')->store('photos', 'public');
        }

        // Set ID User pembuat (fallback ke assigned_to jika auth null)
        $systemUserId = auth()->id() ?? $validated['assigned_to'];

        // 4. Eksekusi Database Transaction
        try {
            $visit = DB::transaction(function () use ($request, $validated, $phone, $photoPath, $systemUserId) {

                // A. Simpan / Update Data Guest
                $existingGuest = guests::where('phone', $phone)->first();

                $guestData = [
                    'name'              => $validated['name'],
                    'company_name'      => $validated['company_name'],
                    'address'           => $validated['address'] ?? null,
                    'email'             => $validated['email'],
                    'guest_category_id' => $validated['guest_category_id'],
                    'position'          => $validated['position'],
                    'phone'             => $phone,
                    'created_by'        => $existingGuest ? ($existingGuest->created_by ?? $systemUserId) : $systemUserId,
                ];

                if ($photoPath) {
                    if ($existingGuest && ! empty($existingGuest->photo_path) && Storage::disk('public')->exists($existingGuest->photo_path)) {
                        Storage::disk('public')->delete($existingGuest->photo_path);
                    }
                    $guestData['photo_path'] = $photoPath;
                }

                if ($existingGuest) {
                    $existingGuest->update($guestData);
                    $guest = $existingGuest;
                } else {
                    // Generate Guest Code dengan Pengurutan String + Loop Guard
                    $todayDate = Carbon::now()->format('Ymd');
                    $prefixGuest = 'GST-' . $todayDate . '-';

                    $latestGuest = guests::where('guest_code', 'like', $prefixGuest . '%')
                        ->orderBy('guest_code', 'desc') // 🟢 Urutkan berdasarkan string guest_code
                        ->first();

                    $nextGuestSeq = $latestGuest ? ((int) substr($latestGuest->guest_code, -4)) + 1 : 1;
                    $guestCode = $prefixGuest . str_pad($nextGuestSeq, 4, '0', STR_PAD_LEFT);

                    // Safety Guard: Pastikan tidak ada bentrokan kode guest
                    while (guests::where('guest_code', $guestCode)->exists()) {
                        $nextGuestSeq++;
                        $guestCode = $prefixGuest . str_pad($nextGuestSeq, 4, '0', STR_PAD_LEFT);
                    }

                    $guestData['guest_code'] = $guestCode;
                    $guestData['is_vip'] = 0;
                    $guest = guests::create($guestData);
                }

                // B. Generate Visit Code dengan Pengurutan String + Loop Guard
                $rawCheckInDate = $validated['scheduled_at'] ?? now();
                $checkInDateTime = Carbon::parse($rawCheckInDate)->format('Y-m-d H:i:s');
                $checkInDateOnly = Carbon::parse($rawCheckInDate)->format('Y-m-d');

                $todayDate = Carbon::now()->format('Ymd');
                $prefixVisit = 'VST-' . $todayDate . '-';

                $latestVisit = visits::where('visit_code', 'like', $prefixVisit . '%')
                    ->orderBy('visit_code', 'desc') // 🟢 Urutkan berdasarkan string visit_code
                    ->first();

                $nextVisitSeq = $latestVisit ? ((int) substr($latestVisit->visit_code, -4)) + 1 : 1;
                $visitCode = $prefixVisit . str_pad($nextVisitSeq, 4, '0', STR_PAD_LEFT);

                // 🟢 Safety Guard: Otomatis mencari nomor berikutnya jika visit_code sudah terpakai
                while (visits::where('visit_code', $visitCode)->exists()) {
                    $nextVisitSeq++;
                    $visitCode = $prefixVisit . str_pad($nextVisitSeq, 4, '0', STR_PAD_LEFT);
                }

                // Hitung Nomor Antrean Hari Ini
                $todayVisitCount = visits::whereDate('scheduled_at', $checkInDateOnly)->count();
                $queueNumber = sprintf('%03d', $todayVisitCount + 1);

                // C. Simpan Visit Baru
                $newVisit = visits::create([
                    'visit_code'   => $visitCode,
                    'guest_id'     => $guest->id,
                    'assigned_to'  => $validated['assigned_to'],
                    'branch_id'    => $validated['branch_id'],
                    'purpose_id'   => $validated['purpose_id'],
                    'scheduled_at' => $checkInDateTime,
                    'source_id'    => $validated['source_id'] ?? null,
                    'notes'        => $validated['notes'],
                    'status'       => 'Terjadwal',
                    'queue_number' => $queueNumber,
                    'check_in_at'  => now(),
                    'created_by'   => $systemUserId,
                ]);

                // D. Simpan Relasi Produk (visit_products)
                if (! empty($validated['product_interest'])) {
                    $productIds = array_filter((array) $validated['product_interest']);
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

                // E. Status Log
                visit_status_logs::create([
                    'visit_id'   => $newVisit->id,
                    'old_status' => null,
                    'new_status' => 'Terjadwal',
                    'changed_by' => $systemUserId,
                    'changed_at' => now(),
                ]);

                // F. Kirim Notifikasi Admin
                try {
                    $purposeType = visit_purposes::find($validated['purpose_id']);
                    $branch = branches::find($validated['branch_id']);
                    $adminUsers = users::where('role', 'admin')->get();

                    foreach ($adminUsers as $admin) {
                        notifications::send(
                            $admin->id,
                            'guest_arrived',
                            'Notifikasi Admin 🔔',
                            "Tamu baru membuat jadwal pertemuan.\n" .
                                "Nama: " . ($guest->name ?? '-') . "\n" .
                                "Instansi: " . ($guest->company_name ?? '-') . "\n" .
                                "Tujuan: " . ($purposeType->name ?? '-') . "\n" .
                                "Cabang: " . ($branch->name ?? '-')
                        );
                    }
                } catch (\Throwable $th) {
                    Log::warning('Gagal kirim notifikasi admin: ' . $th->getMessage());
                }

                return $newVisit;
            });

            // 5. Response Sukses
            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil disimpan.',
                'data'    => [
                    'visit_id'     => $visit->id,
                    'visit_code'   => $visit->visit_code,
                    'queue_number' => $visit->queue_number,
                    'status'       => $visit->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('CheckIn Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses check-in.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Detail Kunjungan
     */
    public function show($id)
    {
        $visit = visits::with([
            'guest',
            'assignedUser',
            'branch',
            'purpose',
            'source',
            'products',
        ])->find($id);

        if (! $visit) {
            return response()->json([
                'success' => false,
                'message' => 'Data kunjungan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail kunjungan ditemukan.',
            'data'    => $visit,
        ], 200);
    }
}
