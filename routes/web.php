<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PBI-07: Lihat Profil
// TODO: tambahkan ->middleware('auth') setelah fitur login tersedia.
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
