<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
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

        if (!$this->isValidApprover($user->role, $application->status)) {
            abort(403, 'Wrong approver for current status');
        }

        if (!$this->canUserApprove($application->id, $user->id, $user->role)) {
            abort(403, 'User has already approved this application');
        }

        $currentStatus = $application->status;
        $newStatus = $this->getNextStatus($currentStatus, $user->role, $request->status, $application->id);
        $application->update(['status' => $newStatus]);

        Approval::create($request->validated());

        if ($user->role === UserRole::BOARD && $currentStatus === ApplicationStatus::APPROVED_NISIT_DEV) {
            $boardStatus = $this->calculateBoardStatus($currentStatus, $application->id);
            if ($boardStatus !== $application->refresh()->status) {
                $application->update(['status' => $boardStatus]);
            }
        }

        return response()->noContent(201);
    }

    private function isRejected(ApplicationStatus $status): bool
    {
        return str_starts_with($status->value, 'REJECTED_');
    }

    private function isValidApprover(UserRole $userRole, ApplicationStatus $currentStatus): bool
    {
        return match ($currentStatus) {
            ApplicationStatus::SUBMITTED => $userRole === UserRole::DEPT_HEAD,
            ApplicationStatus::APPROVED_DEPT_HEAD => $userRole === UserRole::ASSO_DEAN,
            ApplicationStatus::APPROVED_ASSO_DEAN => $userRole === UserRole::DEAN,
            ApplicationStatus::APPROVED_DEAN => $userRole === UserRole::NISIT_DEV,
            ApplicationStatus::APPROVED_NISIT_DEV => $userRole === UserRole::BOARD,
            default => false,
        };
    }

    private function canUserApprove(string $applicationId, string $userId, UserRole $userRole): bool
    {
        return !Approval::where('application_id', $applicationId)
            ->where('user_id', $userId)
            ->exists();
    }

    private function getNextStatus(ApplicationStatus $currentStatus, UserRole $userRole, string $approvalStatusValue, string $applicationId): ApplicationStatus
    {
        $approvalValue = $approvalStatusValue;

        return match ([$currentStatus, $userRole]) {
            [ApplicationStatus::SUBMITTED, UserRole::DEPT_HEAD] => $approvalValue === 'APPROVED'
                ? ApplicationStatus::APPROVED_DEPT_HEAD
                : ApplicationStatus::REJECTED_DEPT_HEAD,
            [ApplicationStatus::APPROVED_DEPT_HEAD, UserRole::ASSO_DEAN] => $approvalValue === 'APPROVED'
                ? ApplicationStatus::APPROVED_ASSO_DEAN
                : ApplicationStatus::REJECTED_ASSO_DEAN,
            [ApplicationStatus::APPROVED_ASSO_DEAN, UserRole::DEAN] => $approvalValue === 'APPROVED'
                ? ApplicationStatus::APPROVED_DEAN
                : ApplicationStatus::REJECTED_DEAN,
            [ApplicationStatus::APPROVED_DEAN, UserRole::NISIT_DEV] => $approvalValue === 'APPROVED'
                ? ApplicationStatus::APPROVED_NISIT_DEV
                : ApplicationStatus::REJECTED_NISIT_DEV,
            [ApplicationStatus::APPROVED_NISIT_DEV, UserRole::BOARD] => $this->calculateBoardStatus($currentStatus, $applicationId),
            default => $currentStatus,
        };
    }

    private function calculateBoardStatus(ApplicationStatus $currentStatus, string $applicationId): ApplicationStatus
    {
        $totalBoardUsers = User::where('role', UserRole::BOARD)->count();

        if ($totalBoardUsers === 0) {
            return $currentStatus;
        }

        $boardApprovals = Approval::where('application_id', $applicationId)
            ->whereHas('user', function ($query) {
                $query->where('role', UserRole::BOARD);
            })->get();

        $approvedCount = $boardApprovals->where('status', 'APPROVED')->count();
        $rejectedCount = $boardApprovals->where('status', 'REJECTED')->count();

        $threshold = $totalBoardUsers / 2;

        if ($approvedCount > $threshold) {
            return ApplicationStatus::APPROVED_BOARD;
        }

        if ($rejectedCount > $threshold) {
            return ApplicationStatus::REJECTED_BOARD;
        }

        if ($approvedCount === $rejectedCount && ($approvedCount > 0 || $rejectedCount > 0)) {
            return ApplicationStatus::REJECTED_BOARD;
        }

        return $currentStatus;
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
