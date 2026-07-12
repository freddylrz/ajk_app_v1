<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Client\DeclarationController;

Route::prefix('declaration')->group(function () {
    Route::get('/list', [DeclarationController::class, 'list']);
    Route::get('/asset', [DeclarationController::class, 'asset']);
    Route::post('/premi-calculation', [DeclarationController::class, 'premiCalculation']);
    Route::post('/insert', [DeclarationController::class, 'insert']);
    Route::post('/update', [DeclarationController::class, 'update']);
    Route::get('/detail', [DeclarationController::class, 'detail']);
    Route::post('/validation', [DeclarationController::class, 'validation']);
});

Route::prefix('claim')->group(function () {

});