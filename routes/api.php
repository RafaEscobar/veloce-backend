<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GasolineRefillController;
use App\Http\Controllers\Api\IssuePriorityController;
use App\Http\Controllers\Api\IssueStatusController;
use App\Http\Controllers\Api\MaintenanceController;
use App\Http\Controllers\Api\PendingIssueController;
use App\Http\Controllers\Api\ReminderController;
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
    Route::apiResource('maintenances', MaintenanceController::class)->except(['show']);
    Route::apiResource('pending-issues', PendingIssueController::class)->except(['show']);
    Route::apiResource('reminders', ReminderController::class)->except(['show']);
    Route::get('vehicle-types', [VehicleTypeController::class, 'index']);
    Route::get('vehicle-statuses', [VehicleStatusController::class, 'index']);
    Route::get('issue-statuses', [IssueStatusController::class, 'index']);
    Route::get('issue-priorities', [IssuePriorityController::class, 'index']);
});