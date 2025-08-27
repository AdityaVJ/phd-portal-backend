<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ScholarAuthController;
use App\Http\Controllers\SupervisorAuthController;
use Illuminate\Support\Facades\Route;

// Admin auth
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::post('/logout-all', [AdminAuthController::class, 'logoutAll']);
        Route::get('/sessions', [AdminAuthController::class, 'sessions']);
        Route::delete('/sessions/{id}', [AdminAuthController::class, 'revokeSession']);
    });
});

// Supervisor auth
Route::prefix('supervisor')->group(function () {
    Route::post('/login', [SupervisorAuthController::class, 'login']);
    Route::post('/refresh', [SupervisorAuthController::class, 'refresh']);
    Route::middleware('auth:sanctum,supervisor')->group(function () {
        Route::post('/logout', [SupervisorAuthController::class, 'logout']);
        Route::post('/logout-all', [SupervisorAuthController::class, 'logoutAll']);
        Route::get('/sessions', [SupervisorAuthController::class, 'sessions']);
        Route::delete('/sessions/{id}', [SupervisorAuthController::class, 'revokeSession']);
    });
});

// Scholar auth
Route::prefix('scholar')->group(function () {
    Route::post('/login', [ScholarAuthController::class, 'login']);
    Route::post('/refresh', [ScholarAuthController::class, 'refresh']);
    Route::middleware('auth:sanctum,scholar')->group(function () {
        Route::post('/logout', [ScholarAuthController::class, 'logout']);
        Route::post('/logout-all', [ScholarAuthController::class, 'logoutAll']);
        Route::get('/sessions', [ScholarAuthController::class, 'sessions']);
        Route::delete('/sessions/{id}', [ScholarAuthController::class, 'revokeSession']);
    });
});
