<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest; // contoh model tamu

class CheckInController extends Controller
{
    // Menampilkan halaman form
    public function index()
    {
        return view('check-in.index'); // sesuaikan letak file blade Anda
    }

    // // Fungsi store untuk menyimpan data tamu
    // public function store(Request $request)
    // {
    //     // 1. Validasi input dari form frontend Anda
    //     $request->validate([
    //         'nama_tamu' => 'required|string|max:255',
    //         'keperluan' => 'required|string',
    //         'pic_tujuan' => 'required|string',
    //     ]);

    //     // 2. Simpan ke database
    //     Guest::create([
    //         'name' => $request->nama_tamu,
    //         'purpose' => $request->keperluan,
    //         'pic' => $request->pic_tujuan,
    //         'check_in_at' => now(),
    //     ]);

    //     // 3. Redirect kembali dengan pesan sukses
    //     return redirect()->route('check-in.index')->with('success', 'Check-in berhasil disimpan!');
    // }
}