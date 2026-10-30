<?php

use App\Http\Controllers\BeasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');