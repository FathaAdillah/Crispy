<?php

use App\Http\Controllers\KuisionerController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RespondenController;
use App\Http\Controllers\VariableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KuisionerController::class, 'index']);
Route::get('/questions', [KuisionerController::class, 'index']);
// Route::get('/question', [KuisionerController::class, 'index']);
Route::post('/submit-kuisioner', [KuisionerController::class, 'submit'])->name('submit-kuisioner');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');
    Route::resource('pertanyaan', PertanyaanController::class);
    Route::resource('variable', VariableController::class);
    Route::resource('responden', RespondenController::class);
    Route::resource('report', ReportController::class);
});
