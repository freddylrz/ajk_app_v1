<?php

use Illuminate\Support\Facades\Route;

Route::view('dashboard', 'client.dashboard.index')
    ->name('dashboard');
