<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\ApprovalStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Approval;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workflowRoles = UserRole::cases();
        Application::all()->each(function ($application) use ($workflowRoles) {
            $stopAtStep = rand(1, 1);

            $finalStatus = ApplicationStatus::SUBMITTED;

            for ($i = 0; $i <= $stopAtStep; $i++) {
                $currentRole = $workflowRoles[$i];

                $approver = User::where('role', $currentRole)
                    ->inRandomOrder()
                    ->first();

                if (!$approver) continue;

                $isLastStep = ($i === $stopAtStep);
                $decision = $isLastStep
                    ? fake()->randomElement(['APPROVED', 'REJECTED'])
                    : 'APPROVED';

                Approval::factory()->create([
                    'application_id' => $application->id,
                    'user_id' => $approver->id,
                    'status' => $decision,
                ]);

                $finalStatus = $decision . '_' . $currentRole->value;

                if ($decision === 'REJECTED') {
                    break;
                }
            }
            $application->update([
                'status' => $finalStatus
            ]);
        });
    }
}
