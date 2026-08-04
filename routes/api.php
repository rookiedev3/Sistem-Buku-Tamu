<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BranchesApiController;
use App\Http\Controllers\Api\ProductsApiController;
use App\Http\Controllers\Api\VisitPurposesApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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