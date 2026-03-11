<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MinioController extends Controller
{
    public static function getFile(Request $request)
    {
        $path = $request->query('path');

        if (!$path || !Storage::disk('s3')->exists($path)) {
            abort(404);
        }

        return Storage::disk('s3')->response($path);
    }

    public static function uploadFile(Request $request) : string | null {
        $request->validate([
            'file' => 'required|mimes:pdf|max:10240',
            'folder' => 'nullable|string'
        ]);
        try {
            $file = $request->file('file');
            $folder = $request->input('folder', 'event');

            $path = $file->store($folder, 's3');

            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
}
