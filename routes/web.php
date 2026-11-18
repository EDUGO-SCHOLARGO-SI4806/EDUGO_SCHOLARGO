<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizerDocumentController;
use App\Http\Middleware\EnsureUserIsOrganizer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ScholarGO
|--------------------------------------------------------------------------
*/

// Beranda: Jika sudah login ➔ ke portal dokumen, jika belum ➔ ke halaman login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('organizer.documents.index');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autentikasi Pengguna (Login, Register, Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| FR-01 - Pengumpulan Bukti Legalitas Penyelenggara (Sprint 1 - Salsabila)
|--------------------------------------------------------------------------
| PBI-01 unggah dokumen | PBI-02 validasi | PBI-03 status & unggah ulang
*/
Route::prefix('organizer')
    ->name('organizer.')
    ->middleware(['auth', EnsureUserIsOrganizer::class])
    ->group(function () {
        Route::get('/documents', [OrganizerDocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/upload', [OrganizerDocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents/upload', [OrganizerDocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/resubmit', [OrganizerDocumentController::class, 'resubmit'])->name('documents.resubmit');
        Route::get('/documents/{document}/download', [OrganizerDocumentController::class, 'download'])->name('documents.download');
    });

/*
|--------------------------------------------------------------------------
| Login Pintas Dev untuk Demo (Environment "local")
|--------------------------------------------------------------------------
| Memudahkan demo langsung 4 status penyelenggara tanpa ketik password
*/
if (app()->environment('local')) {
    Route::get('/dev/login/{state}', function (Request $request, string $state) {
        $user = User::where('email', "penyelenggara.{$state}@scholargo.test")->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('organizer.documents.index');
    })->whereIn('state', ['baru', 'menunggu', 'ditolak', 'disetujui'])->name('dev.login');
}
