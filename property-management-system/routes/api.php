<?php

// use App\Http\Controllers\Api\Auth\LoginController;
// use App\Http\Controllers\Api\Auth\LogoutController;
// use App\Http\Controllers\Api\Auth\RegisterController;
// use App\Http\Controllers\Api\PropertyController;
// use App\Http\Controllers\Api\TenantController;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/register', [RegisterController::class, 'register']);
// Route::post('/login', [LoginController::class, 'login']);

//public listing if needed show properties
// Route::get('/public/properties', [PropertyController::class, 'publicListing']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [LogoutController::class, 'logout']);


//     //properties routes
//     Route::get('/properties', [PropertyController::class, 'index']);
//     Route::post('/properties', [PropertyController::class, 'store']);
//     Route::get('/properties/{id}', [PropertyController::class, 'show']);
//     Route::put('/properties/{id}', [PropertyController::class, 'update']);
//     Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);
//     Route::get('/properties/{id}/rent-distribution', [PropertyController::class, 'rentDistribution']);

//     //tenant routes
//     Route::get('/tenants', [TenantController::class, 'index']);
//     Route::get('/tenants/{id}', [TenantController::class, 'show']);
//     Route::post('/tenants', [TenantController::class, 'store']);
//     Route::put('/tenants/{id}', [TenantController::class, 'update']);
//     Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
//     Route::get('/tenants/{id}/rent', [TenantController::class, 'getMonthlyRent']);
// });

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/public/properties', [PropertyController::class,'publicListing']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);

    Route::apiResource('properties', PropertyController::class);
    Route::get('/properties/{id}/rent-distribution', [PropertyController::class, 'rentDistribution']);

    Route::apiResource('tenants', TenantController::class);
    Route::get('/tenants/{id}/rent', [TenantController::class, 'getMonthlyRent']);
});
