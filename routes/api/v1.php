<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

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