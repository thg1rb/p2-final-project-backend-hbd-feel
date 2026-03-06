<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\Auth\AuthenticateController;
use App\Http\Controllers\Api\MinioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\EventController;

Route::get('/application/{id}', [ApplicationController::class, 'getApplicationById']);
Route::get('/application/student/{id}', [ApplicationController::class, 'getApplicationByStudentId']);

Route::get('/minio/download', [MinioController::class, 'getPreviewUrl']);
Route::post('/minio/upload', [MinioController::class, 'uploadFile']);


Route::post('/approvals', [ApprovalController::class, 'store']);
Route::get('/approvals/{id}', [ApprovalController::class, 'getApprovalRequestByApplicationId']);
Route::get('/approvals/{id}/{userId}', [ApprovalController::class, 'getApprovalRequestByApplicationIdAndUserId']);

Route::post('/login', [AuthenticateController::class, 'login'])->name('user.login');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications', [ApplicationController::class, 'getAllApplications']);
    Route::get('/applications/count', [ApplicationController::class, 'getApplicationCountByStatus']);
    Route::get('/me', [AuthenticateController::class, 'me']);
    Route::get('/applications/count/inprogress', [ApplicationController::class, 'getApplicationCountInprogress']);
    Route::get('/applications/all', [ApplicationController::class, 'getAllApplicationsWithoutPaginate']);
    Route::post('/auth/change-password', [AuthenticateController::class, 'changePassword']);
});


Route::middleware(['throttle:api', 'auth:sanctum'])->as('api.')->group(function () {
    Route::delete('revoke', [AuthenticateController::class, 'revoke'])->name('user.revoke');
});

Route::apiResource('/awards', AwardController::class);

Route::post('/event/end-event/', [EventController::class, 'endEvent'])->name('event.end');
