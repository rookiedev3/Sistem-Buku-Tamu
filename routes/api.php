<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BranchesApiController;

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