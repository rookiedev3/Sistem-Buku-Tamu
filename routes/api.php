<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\BranchesApiController;
use App\Http\Controllers\Api\GuestCategoriesApiController;
use App\Http\Controllers\Api\LeadSourcesApiController;
use App\Http\Controllers\Api\ProductsApiController;
use App\Http\Controllers\Api\VisitPurposesApiController;
use App\Http\Controllers\Api\SecurityApiController;

// ================= AUTH (tidak butuh token) =================
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

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
});

    // ---------- Security ----------
// ---------- Security ----------
    Route::get('/security/dashboard', [SecurityApiController::class, 'dashboard']);
    Route::post('/security/check-in/{id}', [SecurityApiController::class, 'checkIn']);
    Route::post('/security/check-out/{id}', [SecurityApiController::class, 'checkOut']);