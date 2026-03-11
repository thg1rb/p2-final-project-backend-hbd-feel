<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\Auth\AuthenticateController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\MinioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\EventController;

Route::get('/application/{id}', [ApplicationController::class, 'getApplicationById']);
Route::get('/application/student/{id}', [ApplicationController::class, 'getApplicationByStudentId']);
Route::get('/applications/result', [ApplicationController::class, 'getAwardWinnersByYear']);

Route::get('/minio/download', [MinioController::class, 'getPreviewUrl']);
Route::get('/minio/download-pdf', [MinioController::class, 'download']);
Route::post('/minio/upload', [MinioController::class, 'uploadFile']);


Route::post('/approvals', [ApprovalController::class, 'store']);
Route::get('/approvals/{id}', [ApprovalController::class, 'getApprovalRequestByApplicationId']);
Route::get('/approvals/{id}/{userId}', [ApprovalController::class, 'getApprovalRequestByApplicationIdAndUserId']);

Route::post('/login', [AuthenticateController::class, 'login'])->name('user.login');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/awards', AwardController::class)->names('api.awards');
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications', [ApplicationController::class, 'getAllApplications']);
    Route::get('/applications/count', [ApplicationController::class, 'getApplicationCountByStatus']);
    Route::get('/applications/count/inprogress', [ApplicationController::class, 'getApplicationCountInprogress']);
    Route::get('/applications/all', [ApplicationController::class, 'getAllApplicationsWithoutPaginate']);
});




Route::middleware(['throttle:api', 'auth:sanctum'])->as('api.auth.')->group(function () {
    Route::get('/me', [AuthenticateController::class, 'me']);
    Route::post('/auth/change-password', [AuthenticateController::class, 'changePassword']);
    Route::post('/auth/update-user-details', [AuthenticateController::class, 'changeUserDetails']);
    Route::delete('revoke', [AuthenticateController::class, 'revoke'])->name('user.revoke');
});

Route::middleware(['throttle:api'])->group(function () {
    Route::post('/auth/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/auth/reset-password', [PasswordResetController::class, 'resetPassword']);
});

//Route::apiResource('/awards', AwardController::class)->names('api.awards');

Route::post('/event/end-event/', [EventController::class, 'endEvent'])->name('event.end');
