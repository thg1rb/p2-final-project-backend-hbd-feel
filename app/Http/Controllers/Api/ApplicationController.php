<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;

class ApplicationController extends Controller
{
    public function getAllApplications(Request $request)
    {
        $query = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department']);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('firstName', 'like', "%{$searchTerm}%")
                    ->orWhere('lastName', 'like', "%{$searchTerm}%")
                    ->orWhere('student_id', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $page = max(1, (int) ($request->input('page') ?? 1));
        $pageSize = min(100, max(1, (int) ($request->input('page_size') ?? 10)));

        $applications = $query->paginate(perPage: $pageSize, page: $page)->withQueryString();

        return response()->json($applications);
    }

    public function getApplicationById($id)
    {
        $applications = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->findOrFail($id);

        return response()->json($applications);
    }

    public function getApplicationCountByStatus(Request $request): JsonResponse
    {  
        $status = $request->input('status');
        $count = Application::where('status', $status)->count();
        
        return response()->json($count);
    }
}
