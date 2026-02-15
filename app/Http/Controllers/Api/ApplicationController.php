<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function getAllApplications()
    {
        $applications = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Applications retreived successfully',
            'data' => $applications
        ]);
    }

    public function getApplicationById($id)
    {
        $applications = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->findOrFail($id);

        return response()->json($applications);
    }
}
