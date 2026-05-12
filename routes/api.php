<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GasolineRefillController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\VehicleStatusController;
use App\Http\Controllers\Api\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:3,1');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('gasoline-refills', GasolineRefillController::class)->except(['show']);
    Route::get('vehicle-types', [VehicleTypeController::class, 'index']);
    Route::get('vehicle-statuses', [VehicleStatusController::class, 'index']);
});