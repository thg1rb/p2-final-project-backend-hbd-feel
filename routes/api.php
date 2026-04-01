<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\Auth\AuthenticateController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\MinioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\EventController;

Route::middleware(['throttle:api', 'auth:sanctum'])->as('api.auth.')->group(function () {
    Route::get('/me', [AuthenticateController::class, 'me']);
    Route::post('/auth/change-password', [AuthenticateController::class, 'changePassword']);
    Route::post('/auth/update-user-details', [AuthenticateController::class, 'changeUserDetails']);
    Route::delete('revoke', [AuthenticateController::class, 'revoke'])->name('user.revoke');
});

Route::middleware(['throttle:api'])->group(function () {
    Route::post('/login', [AuthenticateController::class, 'login'])->name('user.login');
    Route::post('/auth/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/auth/reset-password', [PasswordResetController::class, 'resetPassword']);

    Route::get('/applications/result', [ApplicationController::class, 'getAwardWinnersByYear']);
});


Route::middleware(['throttle:api', 'auth:sanctum'])->group(function () {
    Route::get('/application/{id}', [ApplicationController::class, 'getApplicationById']);
    Route::get('/application/student/{id}', [ApplicationController::class, 'getApplicationByStudentId']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::put('/application/{id}', [ApplicationController::class, 'update']);
    Route::delete('/application/{id}', [ApplicationController::class, 'destroy']);

    Route::apiResource('/awards', AwardController::class)->names('api.awards');

    Route::get('/minio/download', [MinioController::class, 'getPreviewUrl']);
    Route::get('/minio/download-pdf', [MinioController::class, 'download']);
    Route::post('/minio/upload', [MinioController::class, 'uploadFile']);

    Route::middleware(["abilities:BOARD,ASSO_DEAN,DEPT_HEAD,DEAN"])->group(function () {
        Route::get('/applications', [ApplicationController::class, 'getAllApplications']);
        Route::get('/applications/count', [ApplicationController::class, 'getApplicationCountByStatus']);
        Route::get('/applications/count/inprogress', [ApplicationController::class, 'getApplicationCountInprogress']);
        Route::get('/applications/all', [ApplicationController::class, 'getAllApplicationsWithoutPaginate']);
        Route::get('/event/is-closed', [EventController::class, 'isClosed']);

        Route::post('/approvals', [ApprovalController::class, 'store']);
        Route::get('/approvals/{id}/{userId}', [ApprovalController::class, 'getApprovalRequestByApplicationIdAndUserId']);

        Route::post('/event/end-event/', [EventController::class, 'endEvent'])->name('event.end');
    });

    Route::get('/approvals/{id}', [ApprovalController::class, 'getApprovalRequestByApplicationId']);

});



//Route::apiResource('/awards', AwardController::class)->names('api.awards');

