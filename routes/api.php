<?php

use App\Http\Controllers\AdminApiController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ScholarAuthController;
use App\Http\Controllers\SupervisorAuthController;
use Illuminate\Support\Facades\Route;

// Admin auth
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);
    Route::post('/forgot-password', [AdminAuthController::class, 'forgot']);
    Route::post('/reset-password', [AdminAuthController::class, 'reset']);
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::post('/logout-all', [AdminAuthController::class, 'logoutAll']);
        Route::get('/sessions', [AdminAuthController::class, 'sessions']);
        Route::delete('/sessions/{id}', [AdminAuthController::class, 'revokeSession']);
        Route::get('/list', [AdminApiController::class, 'getAllAdmins']);
        Route::get('/details/{admin}', [AdminApiController::class, 'getAdminDetails']);
        Route::get('/scholars', [AdminApiController::class, 'getAllScholars']);
        Route::get('/scholars/{scholar}', [AdminApiController::class, 'getAllScholars']);
        Route::get('/supervisors', [AdminApiController::class, 'getAllSupervisors']);
        Route::get('/supervisors/{supervisor}', [AdminApiController::class, 'getSupervisorDetails']);
    });
});

// Supervisor auth
Route::prefix('supervisor')->group(function () {
    Route::post('/login', [SupervisorAuthController::class, 'login']);
    Route::post('/refresh', [SupervisorAuthController::class, 'refresh']);
    Route::post('/forgot-password', [SupervisorAuthController::class, 'forgot']);
    Route::post('/reset-password', [SupervisorAuthController::class, 'reset']);
    Route::middleware('auth:supervisor')->group(function () {
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
    Route::post('/forgot-password', [ScholarAuthController::class, 'forgot']);
    Route::post('/reset-password', [ScholarAuthController::class, 'reset']);
    Route::middleware('auth:scholar')->group(function () {
        Route::post('/logout', [ScholarAuthController::class, 'logout']);
        Route::post('/logout-all', [ScholarAuthController::class, 'logoutAll']);
        Route::get('/sessions', [ScholarAuthController::class, 'sessions']);
        Route::delete('/sessions/{id}', [ScholarAuthController::class, 'revokeSession']);
    });
});
