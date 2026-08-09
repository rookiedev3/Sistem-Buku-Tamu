<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontOfficeController;
use App\Http\Controllers\GuestCategoriesController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PicController;
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

        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });
});

// Route untuk user yang sudah login (auth)
Route::middleware('auth')->group(function () {
    // Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::resource('/pengguna', UserController::class)->names('user');

    // Route::get('/pic/dashboard', [VisitsController::class, 'dashboardPic'])->name('pic.dashboard');
    // Route::patch('/pic/visit/{id}/status', [VisitsController::class, 'updateStatus'])->name('pic.updateStatus');
    // Route::post('/pic/visit/{id}/complete', [VisitsController::class, 'completeMeeting'])->name('pic.completeMeeting');

    Route::middleware('auth')->prefix('pic')->group(function () {
        // 👈 Tambahkan baris ini agar /pic langsung mengarah ke /pic/dashboard
        Route::get('/', function () {
            return redirect()->route('pic.dashboard');
        });
        Route::get('/dashboard', [PicController::class, 'dashboardPic'])->name('pic.dashboard');
        Route::get('/riwayat', [PicController::class, 'riwayatPic'])->name('pic.riwayat');
        // Route Lead & Follow Up PIC
        Route::get('/leads', [PicController::class, 'leadsIndex'])->name('pic.leads');
        Route::get('/followup', [PicController::class, 'leadsIndex'])->name('pic.followup');
        Route::post('/leads/{visit_id}/followup', [PicController::class, 'updateFollowUp'])->name('pic.leads.updateFollowUp');
        Route::patch('/visit/{id}/status', [PicController::class, 'updateStatus'])->name('pic.updateStatus');
        Route::post('/visit/{id}/complete', [PicController::class, 'completeMeeting'])->name('pic.completeMeeting');
        Route::patch('/pic/visit/{id}/start-meeting', [PicController::class, 'startMeeting'])->name('pic.startMeeting');

        // baru
        Route::get('/pic/leads', [PicController::class, 'leadsIndex'])->name('pic.leads');
        Route::post('/pic/leads/{leadId}/follow-up', [PicController::class, 'updateFollowUp'])->name('pic.leads.updateFollowUp');
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
    Route::get('/laporan.index', function () {
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
    Route::resource('/pengguna', UserController::class)->names('user');
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
Route::get('/frontoffice/guests', [FrontOfficeController::class, 'guest'])->name('frontoffice.guest');
Route::post('/frontoffice/guests', [FrontOfficeController::class, 'store'])->name('frontoffice.store');
Route::patch('/frontoffice/guests/{guest}/toggle-vip', [FrontOfficeController::class, 'toggleVip'])->name('frontoffice.toggle-vip');

Route::post('/frontoffice/cancel/{id}', [FrontOfficeController::class, 'cancel'])->name('frontoffice.cancel');

Route::get('/frontoffice/history', [FrontOfficeController::class, 'history'])->name('frontoffice.history');

Route::get('/frontoffice/appointment', [FrontOfficeController::class, 'appointment'])->name('frontoffice.appointment');
Route::post('/frontoffice/appointment/store', [FrontOfficeController::class, 'storeAppointment'])->name('frontoffice.appointment.store');
Route::post('/frontoffice/appointment/{id}/status', [FrontOfficeController::class, 'updateAppointmentStatus'])->name('frontoffice.appointment.status');

// // Group Route untuk Role Manager Operasional
// Route::prefix('manager')->middleware('auth')->group(function () {

//     // 1. Dashboard Monitoring Manager (Diubah ke Controller)
//     Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

//     // 2. Semua Kunjungan
//     Route::get('/kunjungan', function () {
//         return view('manager.kunjungan');
//     })->name('manager.kunjungan');

//     // 3. Pipeline Lead Tim
//     Route::get('/leads', function () {
//         return view('manager.leads');
//     })->name('manager.leads');

//     // 4. Laporan & Export Data
//     Route::get('/laporan', function () {
//         return view('manager.laporan');
//     })->name('manager.laporan');

// });

Route::prefix('manager')->middleware('auth')->group(function () {

    // 1. Dashboard Monitoring Manager
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

    // 2. Semua Kunjungan
    Route::get('/kunjungan', [ManagerController::class, 'kunjungan'])->name('manager.kunjungan');

    // 3. Pipeline Lead Tim
    Route::get('/leads', [ManagerController::class, 'leadsPipeline'])->name('manager.leads');

    // 4. Laporan & Export Data
    Route::get('/laporan', [ManagerController::class, 'laporan'])->name('manager.laporan');
    Route::get('/laporan/export-excel', [ManagerController::class, 'exportExcel'])->name('manager.laporan.exportExcel');
    Route::get('/laporan/export-pdf', [ManagerController::class, 'exportPdf'])->name('manager.laporan.exportPdf');

});

Route::post('/frontoffice/notifications/read-all', [FrontOfficeController::class, 'markAllNotificationsRead'])
    ->name('frontoffice.notifications.readAll');

Route::post('/frontoffice/notifications/{id}/read', [FrontOfficeController::class, 'markNotificationRead'])
    ->name('frontoffice.notifications.read');

//     Route::middleware('auth')->prefix('security')->group(function () {
//     Route::get('/dashboard-security', [SecurityController::class, 'dashboard'])->name('security.dashboard');
// });

Route::middleware('auth')->prefix('security')->group(function () {
    Route::get('/dashboard-security', [SecurityController::class, 'dashboard'])->name('security.dashboard');
    Route::post('/visit/{id}/checkin', [SecurityController::class, 'checkIn'])->name('security.checkin');
    Route::post('/visit/{id}/checkout', [SecurityController::class, 'checkOut'])->name('security.checkout');
});

Route::middleware('auth')->prefix('owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');

    Route::get('/products/laporan/data', [ProductsController::class, 'laporan'])->name('products.laporan');
Route::resource('/products', ProductsController::class);

Route::get('/guest-categories/laporan/data', [GuestCategoriesController::class, 'laporan'])->name('guest-categories.laporan');
Route::resource('/guest-categories', GuestCategoriesController::class);
});
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
// Route::prefix('manager')->group(function () {

//     // 1. Dashboard Monitoring Manager
//     Route::get('/dashboard', function () {
//         return view('manager.dashboard');
//     })->name('manager.dashboard');

//     // 2. Semua Kunjungan
//     Route::get('/kunjungan', function () {
//         return view('manager.kunjungan');
//     })->name('manager.kunjungan');

//     // 3. Pipeline Lead Tim
//     Route::get('/leads', function () {
//         return view('manager.leads');
//     })->name('manager.leads');

//     // 4. Laporan & Export Data
//     Route::get('/laporan', function () {
//         return view('manager.laporan');
//     })->name('manager.laporan');

// });
// // 4. Laporan & Export Data
// Route::get('/laporan', function () {
//     return view('manager.laporan');
// })->name('manager.laporan');
// // });

// // Group Route untuk Role Security
// Route::prefix('security')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('security.dashboard');
//     })->name('security.dashboard');
// });
