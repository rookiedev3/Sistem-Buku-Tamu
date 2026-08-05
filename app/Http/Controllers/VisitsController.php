<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\guests;
use App\Models\visits;
use App\Models\users;
use App\Models\branches;
use App\Models\visit_purposes;
use App\Models\products;
use App\Models\lead_sources;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    public function step1()
    {
        return view('check-in.step1');
    }
    // Menyimpan data Tahap 1 & Lanjut ke Tahap 2
    public function storeStep1(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address'      => 'nullable|string|max:500',
            'position'     => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'photo'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

       // Sanitasi Format WhatsApp (+62 / 62)
    $phone = preg_replace('/[^0-9]/', '', $request->phone);
    if (str_starts_with($phone, '0')) {
        $phone = '+62' . substr($phone, 1);
    }
    
    // Ganti data phone yang tervalidasi dengan data yang sudah disanitasi
    $validatedData['phone'] = $phone;

    // Handle Upload Foto jika ada
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('guest_photos', 'public');
        $validatedData['photo'] = $path;
    }

    // Cek keberadaan tamu berdasarkan nomor HP yang sudah berformat rapi
    $guest = guests::where('phone', $phone)->first();

    if ($guest) {
        $guest->update($validatedData);
    } else {
        $validatedData['guest_code'] = $this->generateGuestCode();
        $guest = guests::create($validatedData);
    }

    session([
        'guest_id'   => $guest->id,
        'guest_code' => $guest->guest_code,
    ]);

    return redirect()->route('check-in.step2');
    }

    private function generateGuestCode()
    {
        do {
            // Format: GST-[TANGGAL]-[4 KARAKTER ACAK]
            // Contoh hasil: GST-20260805-K9L2
            $code = 'GST-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        } while (guests::where('guest_code', $code)->exists()); // Memastikan tidak ada duplikasi

        return $code;
    }

    public function step2()
    {
        // Ambil guest_id dari session
        $guestId = session('guest_id');

        if (!$guestId) {
            return redirect()->route('check-in.step1')->with('error', 'Silakan isi data identitas terlebih dahulu.');
        }

        $guest = guests::findOrFail($guestId);

        $pic = users::select('id', 'role')->get();
        $branches = branches::select('code', 'name')->get();
        $visitPurposes = visit_purposes::select('id', 'name')->get();
        $products = products::select('code', 'name')->get();
        $leadSources = lead_sources::select('id', 'name')->get();

        // Mengarah ke file: resources/views/check-in/step2.blade.php
        return view('check-in.step2', compact('guest', 'pic', 'branches', 'visitPurposes', 'products', 'leadSources'));
    }
}
