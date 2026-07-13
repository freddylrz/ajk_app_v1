<?php

use Illuminate\Support\Facades\Route;

Route::prefix('tib')->name('tib.')->group(function () {

    // ── Dashboard ─────────────────────────────────────────────
    Route::view('/dashboard', 'tib.home')
        ->name('dashboard');

    // ── Penutupan ─────────────────────────────────────────────
    Route::prefix('penutupan')->name('penutupan.')->group(function () {
        Route::view('/list-data', 'tib.penutupan.list')
            ->name('list-data');
        Route::get('/detail/{id}', function (string $id) {
            return view('tib.penutupan.detail', ['id' => $id]);
        })->name('detail');
    });

    // ── Klaim ─────────────────────────────────────────────────
    Route::prefix('klaim')->name('klaim.')->group(function () {
        Route::view('/list-data', 'tib.klaim.laporan-awal')
            ->name('list-data');
        Route::get('/detail/{id}', function (string $id) {
            return view('tib.klaim.detail', ['id' => $id]);
        })->name('detail');
    });
});
