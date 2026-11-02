<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\Student\ScholarshipDetailController;

// Student
Route::get('/scholarships/{scholarship}', [ScholarshipDetailController::class, 'show'])
    ->name('student.scholarships.show');