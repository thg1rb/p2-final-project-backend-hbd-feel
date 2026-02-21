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
                'year'     => ['required', 'integer'],
                'grade'    => ['required', 'numeric'],
                'path'     => ['required', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
                'documents'=> ['required', 'array'],
            ];

            foreach ($requirements as $req) {
                $key = $req['id'];
                $rules["documents.$key"] = $req['required']
                    ? ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120']
                    : ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'];
            }

            $validated = $request->validate($rules);

            $applicationFile = $request->file('path');
            $appFileName = Str::uuid() . '.' . $applicationFile->getClientOriginalExtension();
            $mainPath = Storage::disk('s3')->putFileAs('applications', $applicationFile, $appFileName);

            if (!$mainPath) {
                return response()->json(['error' => 'Could not upload main file.'], 500);
            }

            $storedDocuments = [];
            foreach ($requirements as $req) {
                $key = $req['id'];

                if ($request->hasFile("documents.$key")) {
                    $file = $request->file("documents.$key");
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                    $storedPath = Storage::disk('s3')->putFileAs(
                        "documents",
                        $file,
                        $fileName
                    );

                    $storedDocuments[$key] = [
                        'file_path' => $storedPath
                    ];
                }
            }

            $application = Application::create([
//                'student_id' => "2502275062",
                'student_id' => auth()->user()->student_id,
                'award_id'   => $validated['award_id'],
                'event_id'   => $validated['event_id'],
                'year'       => $validated['year'],
                'grade'      => $validated['grade'],
                'path'       => $mainPath,
                'documents'  => $storedDocuments,
                'status'     => 'SUBMITTED'
            ]);

            return response()->json([
                'message' => 'Application submitted successfully',
                'data'    => $application->load(['user', 'event', 'award'])
            ], 201);
        });
    }

}
