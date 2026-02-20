<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Award;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public function store(Request $request): JsonResponse
    {
            return DB::transaction(function () use ($request) {
                $award = Award::findOrFail($request->award_id);
                $requirements = $award->requirements ?? [];

                $rules = [
                    'award_id' => ['required', 'exists:awards,id'],
                    'event_id' => ['required', 'exists:events,id'],
                    'year' => ['required', 'integer'],
                    'grade' => ['required', 'numeric'],
                    'path' => ['required', 'string'],
                    'documents' => ['required', 'array'],
                ];

                foreach ($requirements as $req) {
                    $key = $req['id'];
                    $rules["documents.$key"] = $req['required'] ? ['required', 'string'] : ['nullable', 'string'];
                }

                $validated = $request->validate($rules);

                $application = \App\Models\Application::create([
                    'student_id' => auth()->user()->student_id ,
                    'award_id' => $validated['award_id'],
                    'event_id' => $validated['event_id'],
                    'year' => $validated['year'],
                    'grade' => $validated['grade'],
                    'path' => $validated['path'],
                    'documents' => $validated['documents']
                ]);


                return response()->json([
                    'message' => 'Application submitted successfully',
                    'data'    => $application->load(['user','event','award'])
                ], 201);
            });

    }

}
