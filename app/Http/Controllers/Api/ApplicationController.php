<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['user', 'event', 'award'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Applications retreived successfully',
            'data' => $applications
        ]);
    }
}
