<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    private function getMockUser(): User
    {
        return new User([
            'student_id' => null,
            'faculty_id' => 3,
            'department_id' => 14,
            'role' => UserRole::BOARD,
        ]);
    }

    public function getAllApplications(Request $request)
    {
        $user = $this->getMockUser();
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
            ->search($request->input('search'))
            ->when(
                $request->filled('status'),
                fn ($q) => $q->filterByStatus($request->input('status'), $level)
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
        $applications = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department'])->findOrFail($id);

        return response()->json($applications);
    }

    public function getApplicationCountByStatus(): JsonResponse
    {
        $user = $this->getMockUser();
        $level = $user->role->level()->value;

        $baseQuery = Application::visibleFor($user)->whereEventStatus(Status::OPENED->value, $user);

        return response()->json([
            'pending' => (clone $baseQuery)->filterByStatus('PENDING', $level)->count(),
            'approved' => (clone $baseQuery)->filterByStatus('APPROVED', $level)->count(),
            'rejected' => (clone $baseQuery)->filterByStatus('REJECTED', $level)->count(),
        ]);
    }
}
