<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Semua route untuk user CLIENT (operator cabang) didefinisikan di sini.
| Tanpa controller: halaman statis pakai Route::view, halaman detail pakai
| closure agar bisa meneruskan {id} ke view. Logika data ada di JS.
|
| Konvensi:
|   - Prefix URL   : /client/...
|   - Prefix name  : client....
|   - View         : resources/views/client/...
|   - JS Halaman   : public/assets/js/client/...
|
| Route admin dibuat terpisah di routes/admin.php dengan pola yang sama.
|
*/

Route::prefix('client')->name('client.')
    ->group(function () {

        // ── Dashboard ─────────────────────────────────────────────
        Route::view('/dashboard', 'home')
            ->name('dashboard');

        // ── Simulasi Hitung Premi ─────────────────────────────────
        Route::view('/simulasi-premi', 'client.simulasi-premi')
            ->name('simulasi-premi');

        // ── Penutupan ─────────────────────────────────────────────
        Route::prefix('penutupan')->name('penutupan.')->group(function () {
            Route::view('/input-data', 'client.penutupan.input-data')
                ->name('input');
            Route::view('/list-data', 'client.penutupan.list-data')
                ->name('list');
            Route::view('/terbit-polis', 'client.penutupan.terbit-polis')
                ->name('terbit-polis');
            Route::get('/detail/{id}', function (string $id) {
                return view('client.penutupan.detail', ['id' => $id]);
            })->name('detail');
            Route::get('/update/{id}', function (string $id) {
                return view('client.penutupan.update-data', ['id' => $id]);
            })->name('update');
        });

        // ── Klaim ─────────────────────────────────────────────────
        Route::prefix('klaim')->name('klaim.')->group(function () {
            Route::view('/input-data', 'client.klaim.input-data')
                ->name('input-data');
            Route::view('/data', 'client.klaim.data-klaim')
                ->name('data');
            Route::get('/detail/{id}', function (string $id) {
                return view('client.klaim.detail', ['id' => $id]);
            })->name('detail');
        });
    });
