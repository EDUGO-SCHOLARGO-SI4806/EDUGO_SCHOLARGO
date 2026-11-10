<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// TODO: tambahkan ->middleware('auth') setelah fitur login tersedia.

// PBI-07: Lihat Profil
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

// PBI-08: Edit & Update Data Akademik
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
