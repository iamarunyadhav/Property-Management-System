<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);

    //tenant routes
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::get('/tenants/{tenant_id}', [TenantController::class, 'show']);
    Route::post('/tenants', [TenantController::class, 'store']);
    Route::put('/tenants/{tenant_id}', [TenantController::class, 'update']);
    Route::delete('/tenants/{tenant_id}', [TenantController::class, 'destroy']);
});
