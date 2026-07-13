<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAccessToken;
use App\Http\Middleware\CheckRoleUser;
use App\Http\Middleware\RedirectIfAccessTokenExist;

Route::get('/', function () {
    return view('home');
})->name('');

// Halaman login — jika sudah punya token, langsung ke dashboard client
Route::get('/login', function () {
    return view('auth.login');
})->middleware(RedirectIfAccessTokenExist::class)->name('login');

Route::middleware([RedirectIfAccessTokenExist::class])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('');
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});

Route::middleware([CheckAccessToken::class])->group(function () {
    require __DIR__ . '/tib.php';
    require __DIR__ . '/client.php';
});
