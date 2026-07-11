<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAccessToken;
use App\Http\Middleware\CheckRoleUser;
use App\Http\Middleware\RedirectIfAccessTokenExist;

Route::get('/', function () {
    return view('home');
})->name('');

/*
|--------------------------------------------------------------------------
| Route per role
|--------------------------------------------------------------------------
| Setiap role punya file route sendiri agar rapi dan tidak saling tabrak.
| - routes/client.php → halaman user client  (prefix /client, name client.*)
| - routes/admin.php  → halaman user admin   (prefix /admin,  name admin.*)
*/
require __DIR__ . '/client.php';


// Route::middleware([RedirectIfAccessTokenExist::class])->group(function () {


// });

// Route::middleware([CheckAccessToken::class])->group(function () {
//     
// });
