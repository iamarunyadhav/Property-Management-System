<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/public/properties', [PropertyController::class,'publicListing']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);

    Route::apiResource('properties', PropertyController::class);
    Route::get('/properties/{id}/rent-distribution', [PropertyController::class, 'rentDistribution']);

    Route::apiResource('tenants', TenantController::class);
    Route::get('/tenants/{id}/rent', [TenantController::class, 'getMonthlyRent']);
});
