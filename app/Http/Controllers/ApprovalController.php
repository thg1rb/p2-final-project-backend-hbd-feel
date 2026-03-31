<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\UserRole;
use App\Http\Requests\StoreApprovalRequest;
use App\Jobs\SendApplicationReviewedEmail;
use App\Models\Application;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function store(StoreApprovalRequest $request)
    {
        $application = Application::findOrFail($request->application_id);
        $user = Auth::user();

        if ($this->isRejected($application->status)) {
            return back()->with('error', 'ไม่สามารถอนุมัติใบสมัครที่ถูกปฏิเสธแล้ว');
        }

        if (!$this->isValidApprover($user->role, $application->level, $application->status)) {
            return back()->with('error', 'ไม่มีสิทธิ์อนุมัติในระดับนี้');
        }

        if (!$this->canUserApprove($application->id, $user->id, $user->role)) {
            return back()->with('error', 'คุณได้รับการอนุมัติใบสมัครนี้แล้ว');
        }

        $nextStatus = $this->getNextStatus($application->level, $user->role, $request->status);
        $application->update($nextStatus);

        Approval::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'reason' => $request->reason ?: null,
            'status' => $request->status,
        ]);

        SendApplicationReviewedEmail::dispatch(
            $application,
            $user,
            $request->status,
            $request->reason
        );

        $message = $request->status === 'APPROVED' ? 'อนุมัติสำเร็จ' : 'ปฏิเสธสำเร็จ';
        return back()->with('success', $message);
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
        return !Approval::where('application_id', $applicationId)
            ->where('user_id', $userId)
            ->exists();
    }

    private function getNextStatus(RoleLevel $currentLevel, UserRole $userRole, string $approvalStatusValue): array
    {
        $approvalStatus = ApprovalStatus::from($approvalStatusValue);
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
}
