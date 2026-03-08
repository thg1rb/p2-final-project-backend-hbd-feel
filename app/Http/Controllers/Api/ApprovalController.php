<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApprovalRequest;
use App\Models\Application;
use App\Models\Approval;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getApprovalRequestByApplicationId($applicationId)
    {

        $approvals = Approval::with('user:id,firstName,lastName,role')
            ->where('application_id', $applicationId)
            ->get();

        if ($approvals->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($approvals);
    }

    public function getApprovalRequestByApplicationIdAndUserId($applicationId, $userId)
    {
        $approval = Approval::with('user:id,firstName,lastName,role')
            ->where('application_id', $applicationId)
            ->where('user_id', $userId)
            ->first();

        return response()->json($approval);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApprovalRequest $request)
    {
        $application = Application::findOrFail($request->application_id);
        $user = User::findOrFail($request->user_id);

        if ($this->isRejected($application->status)) {
            abort(403, 'Cannot approve a rejected application');
        }

        if (! $this->isValidApprover($user->role, $application->level, $application->status)) {
            abort(403, 'Wrong approver for current level');
        }

        if (! $this->canUserApprove($application->id, $user->id, $user->role)) {
            abort(403, 'User has already approved this application');
        }

        $nextStatus = $this->getNextStatus($application->level, $user->role, $request->status, $application->id, true);
        $application->update($nextStatus);

        Approval::create($request->validated());

        return response()->noContent(201);
    }

    private function isRejected(ApprovalStatus $status): bool
    {
        return $status === ApprovalStatus::REJECTED;
    }

    private function isValidApprover(UserRole $userRole, RoleLevel $currentLevel, ApprovalStatus $currentStatus): bool
    {
        if ($this->isRejected($currentStatus)) {
            return false;
        }

        $expectedApproverLevel = $currentLevel->value + 1;

        return $userRole->level()->value === $expectedApproverLevel;
    }

    private function canUserApprove(string $applicationId, string $userId, UserRole $userRole): bool
    {
        return ! Approval::where('application_id', $applicationId)
            ->where('user_id', $userId)
            ->exists();
    }

    private function getNextStatus(RoleLevel $currentLevel, UserRole $userRole, string $approvalStatusValue, string $applicationId, bool $includeCurrentVote = false): array
    {
        $approvalStatus = ApprovalStatus::from($approvalStatusValue);

        if ($userRole === UserRole::BOARD && $currentLevel === RoleLevel::NISIT_DEV) {
            return $this->calculateBoardStatus($currentLevel, $applicationId, $approvalStatus, $includeCurrentVote);
        }

        $nextLevel = RoleLevel::from($currentLevel->value + 1);

        if ($approvalStatus === ApprovalStatus::REJECTED) {
            return [
                'level' => $nextLevel,
                'status' => ApprovalStatus::REJECTED,
            ];
        }

        return [
            'level' => $nextLevel,
            'status' => ApprovalStatus::APPROVED,
        ];
    }

    private function calculateBoardStatus(RoleLevel $currentLevel, string $applicationId, ApprovalStatus $currentVoteStatus, bool $includeCurrentVote): array
    {
        $totalBoardUsers = User::where('role', UserRole::BOARD)->count();

        if ($totalBoardUsers === 0) {
            return [
                'level' => $currentLevel,
                'status' => ApprovalStatus::APPROVED,
            ];
        }

        $boardApprovals = Approval::where('application_id', $applicationId)
            ->whereHas('user', function ($query) {
                $query->where('role', UserRole::BOARD);
            })->get();

        $approvedCount = $boardApprovals->where('status', 'APPROVED')->count();
        $rejectedCount = $boardApprovals->where('status', 'REJECTED')->count();

        if ($includeCurrentVote) {
            if ($currentVoteStatus === ApprovalStatus::APPROVED) {
                $approvedCount++;
            } else {
                $rejectedCount++;
            }
        }

        $threshold = $totalBoardUsers / 2;

        if ($approvedCount > $threshold) {
            return [
                'level' => RoleLevel::BOARD,
                'status' => ApprovalStatus::APPROVED,
            ];
        }

        if ($rejectedCount > $threshold) {
            return [
                'level' => RoleLevel::NISIT_DEV,
                'status' => ApprovalStatus::REJECTED,
            ];
        }

        if ($approvedCount === $rejectedCount && ($approvedCount > 0 || $rejectedCount > 0)) {
            return [
                'level' => RoleLevel::NISIT_DEV,
                'status' => ApprovalStatus::REJECTED,
            ];
        }

        return [
            'level' => $currentLevel,
            'status' => ApprovalStatus::APPROVED,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Approval $approval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approval $approval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approval $approval)
    {
        //
    }
}
