<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\VisitsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\GuestCategoriesController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\VisitPurposesController;
use App\Http\Middleware\CheckUserLogin;
use Illuminate\Support\Facades\Route;

// Route untuk user belum login (guest)
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'login')->name('login.proses');
        // Route::get('/register', function () {return view('auth.register');})->name('register');

        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
    });
});

// Route untuk user yang sudah login (auth)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //1. chek in route sementara front end 
    Route::get('/check-in', function () {
    return view('check-in.index'); // sesuaikan dengan nama file view Blade Anda
})->name('check-in.index');

//Route untuk MEMPROSES/MENYIMPAN data form (fungsi store)
Route::post('/check-in', [VisitsController::class, 'store'])->name('visit.checkin');

// 2. Halaman Daftar Kunjungan (Arsip & Riwayat Kunjungan Tamu)
Route::get('/kunjungan', function () {
    return view('kunjungan.index');
});

// 3. Halaman Database Tamu (Arsip & Riwayat Master Tamu)
Route::get('/database-tamu', function () {
    return view('tamu.index');
});
// Route untuk melihat detail riwayat kunjungan tamu berdasarkan ID
Route::get('/database-tamu/{id}', function ($id) {
    return view('tamu.detail', ['id' => $id]);
});
//route lead dan follow up
Route::get('/leads', function () {
    return view('leads.index');
});

//route laporan
Route::get('/laporan', function () {
    return view('laporan.index');
});

//route master data
Route::get('/master-data', function () {
    return view('master.index');
});

//route pengguna
Route::get('/pengguna', function () {
    return view('pengguna.index');
});


});
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/branches', BranchesController::class);
    Route::resource('/products', ProductsController::class);
    Route::resource('/lead-sources', LeadSourcesController::class);
    Route::resource('/visit-purposes', VisitPurposesController::class);
    Route::resource('/guest-categories', GuestCategoriesController::class);


    Route::group(['middleware' => [CheckUserLogin::class.':owner']], function () {
        // Route khusus untuk user dengan level 1 (admin)
    });
// });


// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Route::get('/dashboard', function () {
//     return view('dashboard.index');
// })->name('dashboard');

/// --- RUTE TES TAMPILAN FRONTEND (MULTI-STEP) ---
// Route Tahap 1
Route::get('/check-in/step-1', [VisitsController::class, 'step1'])->name('check-in.step1');
Route::post('/check-in/step-1', [VisitsController::class, 'storeStep1'])->name('check-in.store-step1');

// Route Tahap 2 (WAJIB ADA ->name('check-in.step2'))
Route::get('/check-in/step-2', [VisitsController::class, 'step2'])->name('check-in.step2');
Route::post('/check-in/step-2', [VisitsController::class, 'storeStep2'])->name('check-in.store-step2');

Route::get('/check-in/step-3', [VisitsController::class, 'step3'])->name('check-in.step3');
Route::post('/check-in/step-3', [VisitsController::class, 'storeFinal'])->name('check-in.store-step3');

Route::get('/check-in/step-4/{id}', [VisitsController::class, 'step4'])->name('check-in.step4');