<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MinioController extends Controller
{
    public function getFile(Request $request)
    {
        $path = $request->query('path');

        if (!$path || !Storage::disk('s3')->exists($path)) {
            abort(404);
        }

        return Storage::disk('s3')->response($path);
    }
}
