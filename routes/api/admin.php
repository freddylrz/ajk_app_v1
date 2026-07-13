<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\UtilityController;
use App\Http\Controllers\Api\V1\Admin\DeclarationController;

Route::prefix('utility')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/list', [UtilityController::class, 'userList']);
        Route::get('/asset', [UtilityController::class, 'userAsset']);
        Route::post('/insert', [UtilityController::class, 'userInsert']);
        Route::post('/update', [UtilityController::class, 'userUpdate']);
        Route::post('/delete', [UtilityController::class, 'userDelete']);
    });

    Route::prefix('branch')->group(function () {
        Route::get('/list', [UtilityController::class, 'branchList']);
        Route::post('/insert', [UtilityController::class, 'branchInsert']);
        Route::post('/update', [UtilityController::class, 'branchUpdate']);
        Route::post('/delete', [UtilityController::class, 'branchDelete']);
    });
});

Route::prefix('declaration')->group(function () {
    Route::get('/list', [DeclarationController::class, 'list']);
    Route::get('/detail', [DeclarationController::class, 'detail']);
    Route::post('/validation', [DeclarationController::class, 'validation']);
});

Route::prefix('claim')->group(function () {

});