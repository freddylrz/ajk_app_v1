<?php

use Illuminate\Support\Facades\Route;

Route::prefix('klaim')->name('klaim.')->group(function () {
    Route::view('input-data', 'client.klaim.input-data')
        ->name('input-data');

    Route::view('data', 'client.klaim.data-klaim')
        ->name('data');

    Route::view('rekap', 'client.klaim.rekap')
        ->name('rekap');

    Route::get('detail/{id}', function (string $id) {
        return view('client.klaim.detail', compact('id'));
    })->name('detail');
});
