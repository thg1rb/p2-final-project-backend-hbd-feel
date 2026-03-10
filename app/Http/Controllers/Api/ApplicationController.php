<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
use App\Enums\Status;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationIndexResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Award;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function getAllApplicationsWithoutPaginate(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $level = $user->role->level()->value;

        $applications = Application::with([
            'user',
            'event',
            'award',
            'user.faculty',
            'user.department',
        ])
            ->visibleFor($user)
            ->whereEventStatus(Status::OPENED->value, $user)
            ->whereCampus($user->campus)
            ->search($request->input('search'))
            ->when(
                $request->filled('status'),
                fn($q) => $q->filterByStatus($request->input('status'), $level)
            )
            ->get();

        return response()->json($applications);
    }

    public function getAllApplications(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $level = $user->role->level()->value;

        $applications = Application::with([
            'user',
            'event',
            'award',
            'user.faculty',
            'user.department',
        ])
            ->visibleFor($user)
            ->whereEventStatus(Status::OPENED->value, $user)
            ->whereCampus($user->campus)
            ->search($request->input('search'))
            ->when(
                $request->filled('status'),
                fn($q) => $q->filterByStatus($request->input('status'), $level)
            )
            ->paginate(
                perPage: min(100, max(1, (int) $request->input('page_size', 10))),
                page: max(1, (int) $request->input('page', 1))
            )
            ->withQueryString();

        Log::info("LOG: ", $applications->toArray());

        return response()->json($applications);
    }

    public function getApplicationById($id)
    {
        $applications = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->findOrFail($id);

        return response()->json($applications);
    }

    public function getApplicationCountByStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $level = $user->role->level()->value;

        $baseQuery = Application::visibleFor($user)->whereEventStatus(Status::OPENED->value, $user)->whereCampus($user->campus);

        return response()->json([
            'pending' => (clone $baseQuery)->filterByStatus('PENDING', $level)->count(),
            'approved' => (clone $baseQuery)->filterByStatus('APPROVED', $level)->count(),
            'rejected' => (clone $baseQuery)->filterByStatus('REJECTED', $level)->count(),
        ]);
    }

    public function getApplicationCountInprogress(Request $request): JsonResponse
    {
        $user = $request->user();

        $totalInprogressRemaining = Application::visibleFor($user)
            ->whereEventStatus(Status::OPENED->value, $user)
            ->whereCampus($user->campus)
            ->where('applications.level', '<', 6)
            ->count();

        $totalRemaining = Application::visibleFor($user)
            ->whereEventStatus(Status::OPENED->value, $user)
            ->whereCampus($user->campus)
            ->count();

        Log::info($totalRemaining);

        return response()->json([
            'total' => $totalRemaining,
            'totalInprogress' => $totalInprogressRemaining
        ]);
    }

    public function getApplicationByStudentId($id)
    {
        $applications = Application::with(['user', 'event', 'award'])->where('student_id', $id)->latest()->get();
        $currEvent = Event::get()->where('status', 'OPENED')->first();
        $user = User::with(['faculty', 'department'])->get()->where('student_id', $id)->first();

        //        return response()->json($applications);
        return new ApplicationIndexResource([
            'applications' => $applications,
            'current_event' => $currEvent,
            'student' => $user,
        ]);
    }
    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {

            $event = Event::where('status', Status::OPENED)
                ->where('campus', Auth::user()->campus)
                ->first();

            if (!$event) {
                return response()->json([
                    'error' => 'No opened event available.'
                ], 400);
            }

            $studentId = Auth::user()->student_id;
            $alreadyApplied = Application::where('student_id', $studentId)
                ->where('event_id', $event->id)
                ->exists();

            if ($alreadyApplied) {
                return response()->json([
                    'error' => 'You have already applied for this event.',
                    $event->id
                ], 400);
            }

            $award = Award::findOrFail($request->award_id);
            $requirements = $award->requirements ?? [];

            $rules = [
                'award_id' => ['required', 'exists:awards,id'],
                //                'event_id' => ['required', 'exists:events,id'],
                'year'     => ['required', 'integer'],
                'grade'    => ['required', 'numeric'],
                'path'     => ['required', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
                'documents' => ['required', 'array'],
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
                        'file_path' => $storedPath,
                    ];
                }
            }

            $application = Application::create([
                'student_id' => auth()->user()->student_id,
                'award_id'   => $validated['award_id'],
                'event_id'   => $event->id,
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
