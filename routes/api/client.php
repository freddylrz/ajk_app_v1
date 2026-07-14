<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Client\DeclarationController;
use App\Http\Controllers\Api\V1\Client\ClaimController;

Route::prefix('declaration')->group(function () {
    Route::get('/list', [DeclarationController::class, 'list']);
    Route::get('/asset', [DeclarationController::class, 'asset']);
    Route::post('/premium-calculation', [DeclarationController::class, 'premiumCalculation']);
    Route::post('/insert', [DeclarationController::class, 'insert']);
    Route::post('/update', [DeclarationController::class, 'update']);
    Route::get('/detail', [DeclarationController::class, 'detail']);
    Route::post('/validation', [DeclarationController::class, 'validation']);
});

Route::prefix('claim')->group(function () {
    Route::get('/list', [ClaimController::class, 'list']);
    Route::get('/asset', [ClaimController::class, 'asset']);
    Route::post('/insert', [ClaimController::class, 'insert']);
    Route::post('/update', [ClaimController::class, 'update']);
    Route::get('/detail', [ClaimController::class, 'detail']);
    Route::post('/validation', [ClaimController::class, 'validation']);
});