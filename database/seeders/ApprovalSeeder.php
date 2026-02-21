<?php

namespace Database\Seeders;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Approval;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    public function run(): void
    {
        Application::all()->each(function ($application) {
            $level = $application->level;

            if ($level === RoleLevel::NISIT) {
                $application->update([
                    'status' => ApprovalStatus::APPROVED,
                ]);

                return;
            }

            $roleLevelToUserRole = [
                RoleLevel::DEPT_HEAD->value => UserRole::DEPT_HEAD,
                RoleLevel::ASSO_DEAN->value => UserRole::ASSO_DEAN,
                RoleLevel::DEAN->value => UserRole::DEAN,
                RoleLevel::NISIT_DEV->value => UserRole::NISIT_DEV,
                RoleLevel::BOARD->value => UserRole::BOARD,
            ];

            $finalStatus = null;

            for ($i = 1; $i <= $level->value; $i++) {
                $userRole = $roleLevelToUserRole[$i] ?? null;

                if (! $userRole) {
                    continue;
                }

                $approver = User::where('role', $userRole)
                    ->inRandomOrder()
                    ->first();

                if (! $approver) {
                    continue;
                }

                $rejectionChance = match ($i) {
                    1 => 0.05,
                    2 => 0.10,
                    3 => 0.15,
                    4 => 0.25,
                    5 => 0.40,
                    default => 0.20,
                };

                $approvalStatus = fake()->boolean((1 - $rejectionChance) * 100)
                    ? ApprovalStatus::APPROVED
                    : ApprovalStatus::REJECTED;

                Approval::create([
                    'application_id' => $application->id,
                    'user_id' => $approver->id,
                    'status' => $approvalStatus,
                    'reason' => fake()->paragraph(),
                ]);

                $finalStatus = $approvalStatus;

                if ($approvalStatus === ApprovalStatus::REJECTED) {
                    break;
                }
            }

            if ($finalStatus) {
                $application->update([
                    'status' => $finalStatus,
                ]);
            }
        });
    }
}
