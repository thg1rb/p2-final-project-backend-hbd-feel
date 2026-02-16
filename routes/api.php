<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\AwardRegistrationController;
use App\Http\Controllers\Api\MinioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::post('/approvals', [ApprovalController::class, 'store']);

Route::resource('/award-registrations', AwardRegistrationController::class);

Route::get('/applications', [ApplicationController::class, 'getAllApplications']);

Route::get('/application/{id}', [ApplicationController::class, 'getApplicationById']);

Route::get('/minio/download', [MinioController::class, 'getPreviewUrl']);