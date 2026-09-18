<?php

use Illuminate\Support\Facades\Route;

Route::prefix('penutupan')->name('penutupan.')->group(function () {
    Route::view('input-data', 'client.penutupan.input-data')
        ->name('input');

    Route::view('list-data', 'client.penutupan.list-data')
        ->name('list');

    Route::view('terbit-polis', 'client.penutupan.terbit-polis')
        ->name('terbit-polis');

    Route::view('rekap', 'client.penutupan.rekap')
        ->name('rekap');

    Route::get('detail/{id}', function (string $id) {
        return view('client.penutupan.detail', compact('id'));
    })->name('detail');

    Route::get('update/{id}', function (string $id) {
        return view('client.penutupan.update-data', compact('id'));
    })->name('update');
});
