<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\Client\DeclarationController;

// Publik — tidak butuh login, dipakai oleh halaman Simulasi Hitung Premi versi publik.
Route::prefix('public/declaration')->group(function () {
    Route::post('/premium-calculation', [DeclarationController::class, 'premiumCalculation']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([
        'DecryptSanctumToken',
        'auth:sanctum',
        'SessionExpired'
    ])->group(function () {
        Route::get('/user-info', [AuthController::class, 'userInfo']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware([
    'DecryptSanctumToken',
    'auth:sanctum',
    'SessionExpired',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    Route::prefix('client')->group(function () {
        require __DIR__ . '/client.php';
    });
    
    Route::prefix('admin')->group(function () {
        require __DIR__ . '/admin.php';
    });
});