<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
use App\Enums\ApprovalStatus;
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
use Illuminate\Support\Carbon;
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
            ->whereCampus($user->campus->value)
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
            ->whereCampus($user->campus->value)
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
        $application = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->findOrFail($id);

        return response()->json($application);
    }

    public function getApplicationCountByStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $level = $user->role->level()->value;

        $baseQuery = Application::visibleFor($user)->whereEventStatus(Status::OPENED->value, $user)->whereCampus($user->campus->value);

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
            ->whereCampus($user->campus->value)
            ->where('applications.level', '<', 6)
            ->count();

        $totalRemaining = Application::visibleFor($user)
            ->whereEventStatus(Status::OPENED->value, $user)
            ->whereCampus($user->campus->value)
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
        $user = User::with(['faculty', 'department'])->get()->where('student_id', $id)->first();
        $currEvent = Event::get()->where('status', 'OPENED')->where('campus', $user->campus)->first();

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
                'year'     => ['required', 'integer'],
                'grade'    => ['required', 'numeric'],
                'path'     => ['required', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
                'documents' => ['required', 'array'],
            ];

            foreach ($requirements as $req) {
                $key = $req['id'];
                $rules["documents.$key"] = $req['required']
                    ? ['required', 'file', 'mimes:pdf,jpg,png', 'max:10240']
                    : ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:10240'];
            }

            $validated = $request->validate($rules);

            $applicationFile = $request->file('path');

            $appFileName = Str::uuid() . '.' . $applicationFile->getClientOriginalExtension();

            $mainPath = Storage::disk('s3')->putFileAs(
                'applications',
                $applicationFile,
                $appFileName
            );

            if (!$mainPath) {
                return response()->json([
                    'error' => 'Could not upload main file.'
                ], 500);
            }

            $storedDocuments = [];

            foreach ($requirements as $req) {

                $key = $req['id'];

                if ($request->hasFile("documents.$key")) {

                    $file = $request->file("documents.$key");

                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                    $storedPath = Storage::disk('s3')->putFileAs(
                        'documents',
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
    public function getAwardWinnersByYear(Request $request)
    {
        $year = $request->query('year');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        $event = Event::where('academic_year', $year)
            ->where('semester', $semester)
            ->where('campus', $campus)
            ->where('status', Status::CLOSED)
            ->first();

        $pdfPath = $event?->path;

        $applications = Application::with([
            'award:id,name,campus',
            'user:id,student_id,firstName,lastName,faculty_id',
            'user.faculty:id,name',
            'event:id,academic_year,semester,campus,status'
        ])
            ->whereHas('event', function ($q) use ($year, $semester, $campus) {
                $q->where('academic_year', $year)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    ->where('status', Status::CLOSED);
            })
            ->get();

        $categories = $applications
            ->groupBy(fn($app) => $app->award->name)
            ->map(function ($apps, $awardName) {
                return [
                    'name' => $awardName,
                    'students' => $apps->map(fn($app) => [
                        'name' => trim(($app->user?->firstName ?? '') . ' ' . ($app->user?->lastName ?? '')),
                        'faculty' => $app->user?->faculty?->name ?? '-'
                    ])
                ];
            })
            ->values();

        return response()->json([
            'year' => $year,
            'semester' => $semester,
            'campus' => $campus,

            'pdf_path' => $pdfPath,

            'years' => Event::select('academic_year')
                ->distinct()
                ->orderByDesc('academic_year')
                ->pluck('academic_year'),

            'semesters' => Event::where('academic_year', $year)
                ->select('semester')
                ->distinct()
                ->pluck('semester'),

            'categories' => $categories
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $application = Application::with('event')->findOrFail($id);

            $isOwner = $application->student_id === $user->student_id;

            if (!$isOwner) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $now = now();
            if (!$application->event
                || !$application->event->start_date
                || !$application->event->end_date
                || $now->lt($application->event->start_date)
                || $now->gt($application->event->end_date)
            ) {
                return response()->json(['message' => 'Cannot edit application outside event date range'], 400);
            }

            $award = Award::findOrFail($application->award_id);
            $requirements = $award->requirements ?? [];

            $rules = [
                'year'     => ['required', 'integer','min:1', 'max:4'],
                'grade'    => ['required', 'numeric', 'min:0', 'max:4'],
                'path'     => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
                'documents' => ['nullable', 'array'],
            ];

            foreach ($requirements as $req) {
                $key = $req['id'];
                $rules["documents.$key"] = ['nullable', 'file', 'mimes:pdf', 'max:5120'];
            }

            $validated = $request->validate($rules);
            $application->year = $validated['year'];
            $application->grade = $validated['grade'];

            if ($request->hasFile('path')) {
                $applicationFile = $request->file('path');
                $appFileName = Str::uuid() . '.' . $applicationFile->getClientOriginalExtension();

                if ($application->path) {
                    Storage::disk('s3')->delete($application->path);
                }

                $mainPath = Storage::disk('s3')->putFileAs('applications', $applicationFile, $appFileName);

                if (!$mainPath) {
                    return response()->json(['error' => 'Could not upload file.'], 500);
                }

                $application->path = $mainPath;
            }

            $storedDocuments = $application->documents ?? [];

            foreach ($requirements as $req) {
                $key = $req['id'];

                if ($request->hasFile("documents.$key")) {
                    $file = $request->file("documents.$key");
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                    if (isset($storedDocuments[$key]['file_path'])) {
                        Storage::disk('s3')->delete($storedDocuments[$key]['file_path']);
                    }

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

            $application->documents = $storedDocuments;
            $application->save();

            return response()->json([
                'message' => 'Application updated successfully',
                'data'    => $application->load(['user', 'event', 'award'])
            ], 200);
        });
    }

    public function destroy(Request $request, $id): JsonResponse
{
    try {
        return DB::transaction(function () use ($id, $request) {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $application = Application::find($id);
            if (!$application) {
                return response()->json(['message' => 'Application not found'], 404);
            }

            
            $isOwner = $application->student_id === $user->student_id;

            if (!$isOwner) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            if ($application->path) {
                Storage::disk('s3')->delete($application->path);
            }

            $documents = is_array($application->documents)
                ? $application->documents
                : json_decode($application->documents, true) ?? [];
            
            foreach ($documents as $doc) {
                if (isset($doc['file_path'])) {
                    Storage::disk('s3')->delete($doc['file_path']);
                }
            }

            $application->delete();

            return response()->json([
                'message' => 'Application deleted successfully'
            ], 200);
        });

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Internal Server Error',
            'error_detail' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
}
