<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', EnsureAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // PBI-04 dan PBI-05: daftar (dengan cari dan filter) dan detail akun
        Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('/accounts/{user}', [AccountController::class, 'show'])->name('accounts.show');

        // PBI-06: verifikasi akun penyelenggara
        Route::get('/documents/{document}', [AccountController::class, 'document'])->name('documents.show');
        Route::post('/accounts/{user}/approve', [AccountController::class, 'approve'])->name('accounts.approve');
        Route::post('/accounts/{user}/reject', [AccountController::class, 'reject'])->name('accounts.reject');
    });