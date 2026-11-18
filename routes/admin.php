<?php

use App\Http\Controllers\Admin\AccountController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('super_admin')
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

// SEMENTARA: login cepat untuk testing (hanya di APP_ENV=local).
// HAPUS blok ini setelah login sungguhan dari tim sudah di-merge.
if (app()->environment('local')) {
    Route::get('/dev/login-super-admin', function () {
        Auth::login(User::where('role', User::ROLE_SUPER_ADMIN)->firstOrFail());

        return redirect()->route('admin.accounts.index');
    });
}