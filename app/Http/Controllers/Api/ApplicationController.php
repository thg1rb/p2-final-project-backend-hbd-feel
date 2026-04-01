<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\Status;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationIndexResource;
use App\Models\Application;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Award;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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


        return response()->json($applications);
    }

    public function getApplicationById($id)
    {
        $application = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->findOrFail($id);
        Gate::authorize('view', $application);

        return response()->json($application);
    }

    public function getApplicationCountByStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $level = $user->role->level()->value;

        // Base query ensuring campus and user visibility permissions
        $baseQuery = Application::visibleFor($user)
            ->whereCampus($user->campus->value);

        if ($user->role->value === UserRole::NISIT) {
            return response()->json([
                // Pending: Application is not rejected and the associated event is still OPENED.
                'pending' => (clone $baseQuery)
                    ->whereHas('event', function ($query) {
                        $query->where('status', Status::OPENED->value);
                    })
                    ->where('applications.status', '!=', ApprovalStatus::REJECTED->value)
                    ->count(),

                // Approved: Application reached level 5 (BOARD) and the event is now CLOSED.
                'approved' => (clone $baseQuery)
                    ->whereHas('event', function ($query) {
                        $query->where('status', Status::CLOSED);
                    })
                    ->where('applications.level', RoleLevel::BOARD->value)
                    ->count(),

                // Rejected: Any application that has been rejected across any level or event.
                'rejected' => (clone $baseQuery)
                    ->where('applications.status', ApprovalStatus::REJECTED->value)
                    ->count(),
            ]);
        }

        // Default logic for Staff/Admin roles, typically filtered by the currently OPENED event cycle.
        $staffBaseQuery = (clone $baseQuery)->whereEventStatus(Status::OPENED->value, $user);

        return response()->json([
            'pending' => (clone $staffBaseQuery)->filterByStatus('PENDING', $level)->count(),
            'approved' => (clone $staffBaseQuery)->filterByStatus('APPROVED', $level)->count(),
            'rejected' => (clone $staffBaseQuery)->filterByStatus('REJECTED', $level)->count(),
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
        return response()->json([
            'applications' => $applications,
            'current_event' => $currEvent,
            'student' => $user,
        ]);
    }
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Application::class);
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
                'year' => ['required', 'integer'],
                'grade' => ['required', 'numeric'],
                'path' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
                'documents' => ['nullable', 'array'],
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
                'student_id' => Auth::user()->student_id,
                'award_id' => $validated['award_id'],
                'event_id' => $event->id,
                'year' => $validated['year'],
                'grade' => $validated['grade'],
                'path' => $mainPath,
                'documents' => $storedDocuments,
                'status' => ApprovalStatus::APPROVED,
                'level' => RoleLevel::NISIT
            ]);

            return response()->json([
                'message' => 'Application submitted successfully',
                'data' => $application->load(['user', 'event', 'award'])
            ], 201);
        });
    }
    public function getAwardWinnersByYear(Request $request)
    {
        $year = $request->query('year');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        // 1. Cache รายการปีทั้งหมดแยกตามวิทยาเขต (เก็บ 1 วัน)
        $years = Cache::remember("winner_years_{$campus}", now()->addDay(), function () use ($campus) {
            return Event::where('campus', $campus)
                ->where('status', Status::CLOSED)
                ->select('academic_year')
                ->distinct()
                ->orderByDesc('academic_year')
                ->pluck('academic_year');
        });

        // 2. Cache รายการเทอมของปีนั้นๆ (เก็บ 1 วัน)
        $semesters = Cache::remember("winner_semesters_{$campus}_{$year}", now()->addDay(), function () use ($year, $campus) {
            return Event::where('academic_year', $year)
                ->where('campus', $campus)
                ->where('status', Status::CLOSED)
                ->select('semester')
                ->distinct()
                ->pluck('semester');
        });

        // 3. Cache ข้อมูลผลรางวัล (ตัวนี้สำคัญที่สุด เพราะ Query หนัก)
        // ใช้ Key ที่ระบุถึง วิทยาเขต+ปี+เทอม
        $cacheKey = "winner_results_{$campus}_{$year}_{$semester}";

        $winnerData = Cache::remember($cacheKey, now()->addDay(), function () use ($year, $semester, $campus) {
            $event = Event::where('academic_year', $year)
                ->where('semester', $semester)
                ->where('campus', $campus)
                ->where('status', Status::CLOSED)
                ->first();

            $applications = Application::with([
                'award:id,name,campus',
                'user:id,student_id,firstName,lastName,faculty_id',
                'user.faculty:id,name',
                'event:id,academic_year,semester,campus,status'
            ])
                ->where('status', ApprovalStatus::APPROVED)
                ->where('level', RoleLevel::BOARD)
                ->whereHas('award', function ($q) use ($campus) {
                    $q->where('campus', $campus);
                })
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

            return [
                'pdf_path' => $event?->path,
                'categories' => $categories
            ];
        });

        return response()->json([
            'year' => $year,
            'semester' => $semester,
            'campus' => $campus,
            'pdf_path' => $winnerData['pdf_path'],
            'years' => $years,
            'semesters' => $semesters,
            'categories' => $winnerData['categories']
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
            Gate::authorize('update', $application);

            $isOwner = $application->student_id === $user->student_id;

            if (!$isOwner) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $now = now();
            if (
                !$application->event
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
                'year' => ['required', 'integer', 'min:1', 'max:4'],
                'grade' => ['required', 'numeric', 'min:0', 'max:4'],
                'path' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
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
                'data' => $application->load(['user', 'event', 'award'])
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
                Gate::authorize('delete', $application);
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
