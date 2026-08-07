<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\FrontOfficeController;
use App\Http\Controllers\GuestCategoriesController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitPurposesController;
use App\Http\Controllers\VisitsController;
use App\Http\Middleware\CheckUserLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'halamanUtama'])->name('halamaanUtama');

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
    // Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/pengguna', UserController::class)->names('user');

    Route::get('/pic/dashboard', [VisitsController::class, 'dashboardPic'])->name('pic.dashboard');
    Route::patch('/pic/visit/{id}/status', [VisitsController::class, 'updateStatus'])->name('pic.updateStatus');
    Route::post('/pic/visit/{id}/complete', [VisitsController::class, 'completeMeeting'])->name('pic.completeMeeting');

    Route::middleware('auth')->prefix('pic')->group(function () {
        // 👈 Tambahkan baris ini agar /pic langsung mengarah ke /pic/dashboard
        Route::get('/', function () {
            return redirect()->route('pic.dashboard');
        });
        Route::get('/dashboard', [FollowUpController::class, 'dashboardPic'])->name('pic.dashboard');
        Route::get('/riwayat', [FollowUpController::class, 'riwayatPic'])->name('pic.riwayat');
        // Route Lead & Follow Up PIC
        Route::get('/leads', [FollowUpController::class, 'leadsIndex'])->name('pic.leads');
        Route::get('/followup', [FollowUpController::class, 'followupIndex'])->name('pic.followup');
        Route::post('/leads/{visit_id}/followup', [FollowUpController::class, 'updateFollowUp'])->name('pic.leads.updateFollowUp');
        Route::patch('/visit/{id}/status', [FollowUpController::class, 'updateStatus'])->name('pic.updateStatus');
        Route::post('/visit/{id}/complete', [FollowUpController::class, 'completeMeeting'])->name('pic.completeMeeting');
        Route::patch('/pic/visit/{id}/start-meeting', [FollowUpController::class, 'startMeeting'])->name('pic.startMeeting');
    });

    // 1. chek in route sementara front end
    Route::get('/check-in', function () {
        return view('check-in.index'); // sesuaikan dengan nama file view Blade Anda
    })->name('check-in.index');

    // Route untuk MEMPROSES/MENYIMPAN data form (fungsi store)
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
    // route lead dan follow up
    Route::get('/leads', function () {
        return view('leads.index');
    });

    // route laporan
    Route::get('/laporan', function () {
        return view('laporan.index');
    });

    // route master data
    Route::get('/master-data', function () {
        return view('master.index');
    });

    // //route pengguna
    // Route::get('/pengguna', function () {
    //     return view('pengguna.index');
    // });

});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// route untuk master data (branches, products, lead sources, visit purposes, guest categories)
Route::resource('/branches', BranchesController::class);
Route::resource('/products', ProductsController::class);
Route::resource('/lead-sources', LeadSourcesController::class);
Route::resource('/visit-purposes', VisitPurposesController::class);
Route::resource('/guest-categories', GuestCategoriesController::class);

Route::group(['middleware' => [CheckUserLogin::class.':owner']], function () {
    // Route khusus untuk user dengan level 1 (admin)
});
// });

Route::middleware('auth')->prefix('security')->group(function () {
    Route::get('/dashboard-security', [SecurityController::class, 'dashboard'])->name('security.dashboard');
    Route::post('/visit/{id}/checkin', [SecurityController::class, 'checkIn'])->name('security.checkin');
    Route::post('/visit/{id}/checkout', [SecurityController::class, 'checkOut'])->name('security.checkout');
});

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Route::get('/dashboard', function () {
//     return view('dashboard.index');
// })->name('dashboard');

// / --- RUTE TES TAMPILAN FRONTEND (MULTI-STEP) ---
// Route Tahap 1
Route::get('/check-in/step-1', [VisitsController::class, 'step1'])->name('check-in.step1');
Route::post('/check-in/step-1', [VisitsController::class, 'storeStep1'])->name('check-in.store-step1');

// Route Tahap 2 (WAJIB ADA ->name('check-in.step2'))
Route::get('/check-in/step-2', [VisitsController::class, 'step2'])->name('check-in.step2');
Route::post('/check-in/step-2', [VisitsController::class, 'storeStep2'])->name('check-in.store-step2');

Route::get('/check-in/step-3', [VisitsController::class, 'step3'])->name('check-in.step3');
Route::post('/check-in/step-3', [VisitsController::class, 'storeFinal'])->name('check-in.store-step3');

Route::get('/check-in/step-4/{id}', [VisitsController::class, 'step4'])->name('check-in.step4');

// --- RUTE FRONT OFFICE ---
Route::get('/frontoffice/dashboard', [FrontOfficeController::class, 'dashboard'])->name('frontoffice.dashboard');
Route::post('/frontoffice/visit/{id}/checkin', [FrontOfficeController::class, 'checkIn'])->name('frontoffice.checkin');
Route::post('/frontoffice/visit/{id}/checkout', [FrontOfficeController::class, 'checkOut'])->name('frontoffice.checkout');
Route::post('/frontoffice/visit/manual', [FrontOfficeController::class, 'storeManual'])->name('frontoffice.storeManual');

Route::get('/frontoffice/history', [FrontOfficeController::class, 'history'])->name('frontoffice.history');

Route::get('/frontoffice/appointment', [FrontOfficeController::class, 'appointment'])->name('frontoffice.appointment');
Route::post('/frontoffice/appointment/store', [FrontOfficeController::class, 'storeAppointment'])->name('frontoffice.appointment.store');
Route::post('/frontoffice/appointment/{id}/status', [FrontOfficeController::class, 'updateAppointmentStatus'])->name('frontoffice.appointment.status');

Route::get('/frontoffice/pegawai', [FrontOfficeController::class, 'pegawai'])->name('frontoffice.pegawai');
Route::post('/frontoffice/pegawai/store', [FrontOfficeController::class, 'storePegawai'])->name('frontoffice.storePegawai');
Route::post('/frontoffice/pegawai/{id}/update', [FrontOfficeController::class, 'updatePegawai'])->name('frontoffice.updatePegawai');
Route::delete('/frontoffice/pegawai/{id}/delete', [FrontOfficeController::class, 'deletePegawai'])->name('frontoffice.deletePegawai');

// Route::prefix('pic')->group(function () {

//     // // Halaman Dashboard PIC
//     // Route::get('/dashboard', function () {
//     //     return view('pic.dashboard');
//     // })->name('pic.dashboard');

//     // Halaman Riwayat Kunjungan PIC
//     Route::get('/riwayat', function () {
//         return view('pic.riwayat');
//     })->name('pic.riwayat');

//     // Lead & Follow Up PIC
//     Route::get('/leads', function () {
//         return view('pic.leads');
//     })->name('pic.leads');

// });

// Group Route untuk Role Manager Operasional
Route::prefix('manager')->group(function () {

    // 1. Dashboard Monitoring Manager
    Route::get('/dashboard', function () {
        return view('manager.dashboard');
    })->name('manager.dashboard');

    // 2. Semua Kunjungan
    Route::get('/kunjungan', function () {
        return view('manager.kunjungan');
    })->name('manager.kunjungan');

    // 3. Pipeline Lead Tim
    Route::get('/leads', function () {
        return view('manager.leads');
    })->name('manager.leads');

    // 4. Laporan & Export Data
    Route::get('/laporan', function () {
        return view('manager.laporan');
    })->name('manager.laporan');

});

// // Group Route untuk Role Security
// Route::prefix('security')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('security.dashboard');
//     })->name('security.dashboard');
// });
