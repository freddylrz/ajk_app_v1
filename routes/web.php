<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAccessToken;
use App\Http\Middleware\CheckRoleUser;
use App\Http\Middleware\RedirectIfAccessTokenExist;

Route::get('/', function () {
    return view('home');
})->name('');


// Route::middleware([RedirectIfAccessTokenExist::class])->group(function () {


// });

// Route::middleware([CheckAccessToken::class])->group(function () {
//     
// });
