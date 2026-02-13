<?php

use App\Http\Controllers\Api\ApplicationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/applications', [ApplicationController::class, 'index']);

Route::get('/test-minio', function () {
    try {
        $fileName = 'test-' . time() . '.txt';
        $content = 'Hello from Laravel Sail at ' . now();
        $status = Storage::disk('s3')->put($fileName, $content);

        if ($status) {
            $bucket = config('filesystems.disks.s3.bucket');
            $endpoint = config('filesystems.disks.s3.endpoint');
            $url = $endpoint . '/' . $bucket . '/' . $fileName;

            return response()->json([
                'message' => 'Upload Success!',
                'file_name' => $fileName,
                'url' => $url,
                'bucket' => $bucket
            ]);
        }
        return "Upload failed without error.";
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error found!',
            'error' => $e->getMessage()
        ], 500);
    }
});
