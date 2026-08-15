<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\BranchesApiController;
use App\Http\Controllers\Api\GuestCategoriesApiController;
use App\Http\Controllers\Api\LeadSourcesApiController;
use App\Http\Controllers\Api\ProductsApiController;
use App\Http\Controllers\Api\CheckInApiController;
use App\Http\Controllers\api\LaporanController;
use App\Http\Controllers\Api\ManagerApiController;
use App\Http\Controllers\Api\OwnerApiController;
use App\Http\Controllers\Api\PicApiController;
use App\Http\Controllers\Api\VisitPurposesApiController;
use App\Http\Controllers\Api\SecurityApiController;

// ================= AUTH (tidak butuh token) =================
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);

// ================= SEMUA ROUTE DI BAWAH INI WAJIB BAWA TOKEN =================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    // ---------- User Management & Master Data: cuma admin ----------
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserApiController::class, 'index']);
        Route::post('/users', [UserApiController::class, 'store']);
        Route::post('/users/{id}/approve', [UserApiController::class, 'approve']);
        Route::post('/users/{id}/deactivate', [UserApiController::class, 'deactivate']);
        Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
        Route::put('/users/{id}', [UserApiController::class, 'update']);

        // ---------- Master Data ----------
        Route::apiResource('branches', BranchesApiController::class)->names([
            'index'   => 'api.branches.index',
            'store'   => 'api.branches.store',
            'show'    => 'api.branches.show',
            'update'  => 'api.branches.update',
            'destroy' => 'api.branches.destroy',
        ]);

        Route::apiResource('products', ProductsApiController::class)->names([
            'index'   => 'api.products.index',
            'store'   => 'api.products.store',
            'show'    => 'api.products.show',
            'update'  => 'api.products.update',
            'destroy' => 'api.products.destroy',
        ]);

        Route::apiResource('visit-purposes', VisitPurposesApiController::class)->names([
            'index'   => 'api.visit-purposes.index',
            'store'   => 'api.visit-purposes.store',
            'show'    => 'api.visit-purposes.show',
            'update'  => 'api.visit-purposes.update',
            'destroy' => 'api.visit-purposes.destroy',
        ]);

        Route::apiResource('lead-sources', LeadSourcesApiController::class)->names([
            'index'   => 'api.lead-sources.index',
            'store'   => 'api.lead-sources.store',
            'show'    => 'api.lead-sources.show',
            'update'  => 'api.lead-sources.update',
            'destroy' => 'api.lead-sources.destroy',
        ]);

        Route::apiResource('guest-categories', GuestCategoriesApiController::class)->names([
            'index'   => 'api.guest-categories.index',
            'store'   => 'api.guest-categories.store',
            'show'    => 'api.guest-categories.show',
            'update'  => 'api.guest-categories.update',
            'destroy' => 'api.guest-categories.destroy',
        ]);
    });

Route::middleware('auth:sanctum')->prefix('pic')->group(function () {
    Route::get('/dashboard', [PicApiController::class, 'dashboard']);
    Route::get('/followup', [PicApiController::class, 'followupIndex']);
    Route::get('/riwayat', [PicApiController::class, 'riwayat']);
    Route::get('/leads', [PicApiController::class, 'leadsIndex']);
    Route::post('/leads/{id}/follow-up', [PicApiController::class, 'storeLeadFollowUp']);
 
    Route::post('/visits/{id}/status', [PicApiController::class, 'updateStatus']);
    Route::post('/visits/{id}/start-meeting', [PicApiController::class, 'startMeeting']);
    Route::post('/visits/{id}/complete-meeting', [PicApiController::class, 'completeMeeting']);
    // Route::post('/leads/{leadId}/follow-up', [PicApiController::class, 'updateFollowUp']);
});
 
});

    Route::middleware('auth:sanctum')->prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerApiController::class, 'dashboard']);
    Route::get('/kunjungan', [ManagerApiController::class, 'kunjungan']);
    Route::get('/leads', [ManagerApiController::class, 'leadsPipeline']);
    Route::get('/laporan', [ManagerApiController::class, 'laporan']);
});

Route::prefix('check-in')->group(function () {
    // 1. Ambil data dropdown/master data untuk frontend
    Route::get('/form-data', [CheckInApiController::class, 'getFormData']);

    // 2. Endpoint opsional jika butuh validasi Step 1 secara terpisah
    Route::post('/validate-step1', [CheckInApiController::class, 'validateStep1']);

    // 3. Submit utama check-in (dikirim dari frontend saat final step)
    Route::post('/', [CheckInApiController::class, 'store']);

    // 4. Detail Kunjungan (Halaman Sukses / Bukti Check-In)
    Route::get('/{id}', [CheckInApiController::class, 'show']);
});

    // ---------- Security ----------
// ---------- Security ----------
    Route::get('/security/dashboard', [SecurityApiController::class, 'dashboard']);
    Route::post('/security/check-in/{id}', [SecurityApiController::class, 'checkIn']);
    Route::post('/security/check-out/{id}', [SecurityApiController::class, 'checkOut']);

    Route::prefix('check-in')->group(function () {
    // 1. Ambil data dropdown/master data untuk frontend
    Route::get('/form-data', [CheckInApiController::class, 'getFormData']);

    // 2. Endpoint opsional jika butuh validasi Step 1 secara terpisah
    Route::post('/validate-step1', [CheckInApiController::class, 'validateStep1']);

    // 3. Submit utama check-in (dikirim dari frontend saat final step)
    Route::post('/', [CheckInApiController::class, 'store']);

    // 4. Detail Kunjungan (Halaman Sukses / Bukti Check-In)
    Route::get('/{id}', [CheckInApiController::class, 'show']);
});

Route::middleware('auth:sanctum')->prefix('owner')->group(function () {
    Route::get('/dashboard', [OwnerApiController::class, 'dashboard']);
    Route::get('/produk-diminati', [OwnerApiController::class, 'produkDiminati']);
    Route::get('/kategori-tamu', [OwnerApiController::class, 'kategoriTamu']);
    Route::get('/activity-log', [OwnerApiController::class, 'activityLog']);
    Route::get('/leads', [OwnerApiController::class, 'leads']);
    Route::get('/laporan', [OwnerApiController::class, 'laporan']);
Route::get('/laporan/export-excel', [OwnerApiController::class, 'exportExcel']);
Route::get('/laporan/export-pdf', [OwnerApiController::class, 'exportPdf']);
// routes/api.php
Route::get('/owner/laporan/download/{filename}', [LaporanController::class, 'downloadFile']);

});



Route::get('/owner/laporan/download/{filename}', [OwnerApiController::class, 'downloadLaporan'])
    ->name('laporan.download')   // 👈 INI namanya "laporan.download"
    ->middleware('signed');


