<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\AwardReportController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MainDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\AwardRegistrationController;
use App\Http\Controllers\EndEventController;
use App\Http\Controllers\MinioController;

Route::get('/', [MainDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('main');

Route::middleware('auth')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');
    Route::get('/awards/create', [AwardController::class, 'create'])->name('awards.create');
    Route::post('/awards', [AwardController::class, 'store'])->name('awards.store');
    Route::get('/awards/{award}/edit', [AwardController::class, 'edit'])->name('awards.edit');
    Route::put('/awards/{award}', [AwardController::class, 'update'])->name('awards.update');
    Route::delete('/awards/{award}', [AwardController::class, 'destroy'])->name('awards.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/award-registrations', [\App\Http\Controllers\AwardRegistrationController::class, 'index'])->name('award-registrations');

    Route::get(
        'award-registrations/create',
        [AwardRegistrationController::class, 'create']
    )->name('award-registrations.create');

    Route::post(
        'award-registrations/store',
        [AwardRegistrationController::class, 'store']
    )->name('award-registrations.store');
});

Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToProvider'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('google.callback');



Route::get('/users', [
    UserController::class,
    'index'
])->name('users.index');

Route::get('/users/create', [
    UserController::class,
    'create'
])->name('users.create');

Route::resource('users', UserController::class);

Route::get('/report/{id}', [AwardReportController::class, 'show'])->name('report.show');
Route::get('/report', [AwardReportController::class, 'index'])->name('report.award-report');
Route::get('/report/edit/{id}', [AwardReportController::class, 'edit'])->name('report.edit');
Route::put('report/{application}', [AwardReportController::class, 'update'])->name('report.update');

Route::get('/file-preview', [MinioController::class, 'getFile'])->name('file.preview');

Route::get('/end-event/sign', [EndEventController::class, 'index'])->name('end-event.index');
Route::post('/end-event/upload-event', [EndEventController::class, 'uploadEvent'])->name('end-event.upload');
Route::get('/end-event/export-pdf', [EndEventController::class, 'exportPdf'])->name('end-event.pdf');

require __DIR__ . '/auth.php';
