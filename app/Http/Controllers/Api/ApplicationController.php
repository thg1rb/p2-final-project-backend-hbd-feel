<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
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
            'faculty_id' => 3,
            'department_id' => 14,
            'role' => UserRole::DEPT_HEAD,
        ]);
    }

    private function applyRoleFilter($query, RoleLevel $roleLevel)
    {
        return $query->where(function ($q) use ($roleLevel) {
            $q->where(function ($q) use ($roleLevel) {
                $q->where('level', $roleLevel->value)
                    ->where('status', ApprovalStatus::APPROVED->value);
            })
                ->orWhere('level', '>', $roleLevel->value);
        });
    }

    public function getAllApplications(Request $request)
    {
        $query = Application::with(['user', 'event', 'award', 'user.faculty', 'user.department']);

        // TODO: Using real user
        $user = $this->getMockUser();

        $level = $user->role->level()->value;
        $previousLevel = $level - 1;

        switch ($user->role) {
            case UserRole::DEPT_HEAD:
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
                break;

            case UserRole::ASSO_DEAN:
                $this->applyRoleFilter($query, RoleLevel::DEPT_HEAD)
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('faculty_id', $user->faculty_id);
                    });
                break;

            case UserRole::DEAN:
                $this->applyRoleFilter($query, RoleLevel::ASSO_DEAN)
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('faculty_id', $user->faculty_id);
                    });
                break;

            case UserRole::NISIT_DEV:
                $this->applyRoleFilter($query, RoleLevel::DEAN);
                break;

            case UserRole::BOARD:
                $this->applyRoleFilter($query, RoleLevel::NISIT_DEV);
                break;
        }

        // search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('firstName', 'like', "%{$searchTerm}%")
                    ->orWhere('lastName', 'like', "%{$searchTerm}%")
                    ->orWhere('student_id', 'like', "%{$searchTerm}%");
            });
        }

        // status filter
        if ($request->filled('status')) {
            if ($request->input('status') === 'PENDING') {
                $query->where('level', $previousLevel)
                    ->where('status', ApprovalStatus::APPROVED->value);
            } elseif ($request->input('status') === 'REJECTED') {
                $query->where('level', $level)
                    ->where('status', ApprovalStatus::REJECTED->value);
            } elseif ($request->input('status') === 'APPROVED') {
                $query->where(function ($q) use ($level) {
                    $q->where(function ($q2) use ($level) {
                        // current level must be approved
                        $q2->where('level', $level)
                            ->where('status', ApprovalStatus::APPROVED->value);
                    })
                        ->orWhere(function ($q2) use ($level) {
                            // any higher level means already approved at current level
                            $q2->where('level', '>', $level);

                        });

                });
            }
        }

        $page = max(1, (int) ($request->input('page') ?? 1));
        $pageSize = min(100, max(1, (int) ($request->input('page_size') ?? 10)));

        $applications = $query
            ->paginate(perPage: $pageSize, page: $page)
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
        $previousLevel = $level - 1;

        // IMPORTANT: create base query
        $query = Application::query();

        // apply same role filter as getAllApplications
        switch ($user->role) {
            case UserRole::DEPT_HEAD:
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
                break;
            case UserRole::ASSO_DEAN:
                $this->applyRoleFilter($query, RoleLevel::DEPT_HEAD)
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('faculty_id', $user->faculty_id);
                    });
                break;

            case UserRole::DEAN:
                $this->applyRoleFilter($query, RoleLevel::ASSO_DEAN)
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('faculty_id', $user->faculty_id);
                    });
                break;

            case UserRole::NISIT_DEV:
                $this->applyRoleFilter($query, RoleLevel::DEAN);
                break;

            case UserRole::BOARD:
                $this->applyRoleFilter($query, RoleLevel::NISIT_DEV);
                break;
        }

        $pending = (clone $query)
            ->where('level', $previousLevel)
            ->where('status', ApprovalStatus::APPROVED->value)
            ->count();

        $approved = (clone $query)
            ->where(function ($q) use ($level) {
                // current level must be APPROVED
                $q->where(function ($q2) use ($level) {
                    $q2->where('level', $level)
                        ->where('status', ApprovalStatus::APPROVED->value);
                })
                    // any higher level means already approved current level
                    ->orWhere('level', '>', $level);
            })
            ->count();

        $rejected = (clone $query)
            ->where('level', '=', $level)
            ->where('status', ApprovalStatus::REJECTED->value)
            ->count();

        return response()->json([
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
        ]);
    }
}
