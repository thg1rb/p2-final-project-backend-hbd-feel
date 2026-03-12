<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MinioController extends Controller
{
    public function getPreviewUrl(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            return response()->json(['message' => 'Path is required'], 400);
        }

        try {
            $fileContent = Storage::disk('s3')->get($path);

            return response($fileContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadFile(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:pdf|max:10240',
            'folder' => 'nullable|string'
        ]);
        try {
            $file = $request->file('file');
            $folder = $request->input('folder', 'event');

            $path = $file->store($folder, 's3');

            return response()->json([
                'message' => 'Upload successful',
                'path' => $path,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function download(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            return response()->json(['message' => 'Path is required'], 400);
        }

        try {
            $fileContent = Storage::disk('s3')->get($path);

            return response($fileContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . basename($path) . '"');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
