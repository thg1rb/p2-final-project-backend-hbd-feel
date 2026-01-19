<?php

use App\Http\Controllers\AwardReportController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MainDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainDashboardController::class, 'index'])->name('main.dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get(
    '/events',
    [EventController::class, 'index']
)->middleware(['auth', 'verified'])->name('events.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/report', [AwardReportController::class, 'index'])->name('report.award-report');

require __DIR__ . '/auth.php';
