<?php

use Illuminate\Support\Facades\Route;

Route::prefix('tib')->name('tib.')->group(function () {

    // ── Dashboard ─────────────────────────────────────────────
    Route::view('/dashboard', 'home')
        ->name('dashboard');

    // ── Utilities ─────────────────────────────────────────────
    Route::prefix('utilities')->name('utilities.')->group(function () {
        Route::view('/list-branch', 'tib.utilities.branch-management')
            ->name('list-branch');

        Route::view('/list-user', 'tib.utilities.user-management')
            ->name('list-user');
    });

    // ── Penutupan ─────────────────────────────────────────────
    Route::prefix('penutupan')->name('penutupan.')->group(function () {
        Route::view('/list-data', 'client.penutupan.list-data');
        Route::view('/terbit-polis', 'client.penutupan.terbit-polis')
            ->name('terbit-polis');
        Route::get('/detail/{id}', function (string $id) {
            return view('client.penutupan.detail', ['id' => $id]);
        })->name('detail');
    });

    // ── Klaim ─────────────────────────────────────────────────
    Route::prefix('klaim')->name('klaim.')->group(function () {
        Route::view('/data', 'client.klaim.data-klaim')
            ->name('data');
        Route::get('/detail/{id}', function (string $id) {
            return view('client.klaim.detail', ['id' => $id]);
        })->name('detail');
    });
});
