<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Entry point route web untuk user CLIENT (operator cabang).
| Detail setiap fitur dipisahkan di routes/client agar penambahan halaman
| baru tidak membuat satu file route menjadi terlalu besar.
|
| Konvensi:
|   - Prefix URL   : /client/...
|   - Prefix name  : client....
|   - View         : resources/views/client/...
|   - JS Halaman   : resources/js/client/<fitur>/...
|
| File fitur:
|   - routes/client/dashboard.php
|   - routes/client/simulasi-premi.php
|   - routes/client/penutupan.php
|   - routes/client/klaim.php
|
*/

Route::prefix('client')->name('client.')->group(function () {
    require __DIR__.'/client/dashboard.php';
    require __DIR__.'/client/simulasi-premi.php';
    require __DIR__.'/client/penutupan.php';
    require __DIR__.'/client/klaim.php';
});
