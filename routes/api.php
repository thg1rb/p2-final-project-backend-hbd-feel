<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\MinioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/applications', [ApplicationController::class, 'getAllApplications']);

Route::get('/application/{id}', [ApplicationController::class, 'getApplicationById']);

Route::get('/minio/download', [MinioController::class, 'getPreviewUrl']);

Route::get('/minio/list', function () {
    $bucket = env('AWS_BUCKET');
    $files = Storage::disk('s3')->allFiles();

    return response()->json([
        'bucket' => $bucket,
        'files' => $files,
        'count' => count($files)
    ]);
});
