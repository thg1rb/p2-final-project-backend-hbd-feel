<?php

namespace App\Jobs;

use App\Mail\ApplicationReviewed;
use App\Models\Application;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendApplicationReviewedEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Application $application, public User $approver, public string $status, public string $reason)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $studentEmail = $this->application->user->email;

        Mail::to($studentEmail)->send(
            new ApplicationReviewed(
                $this->application,
                $this->approver,
                $this->status,
                $this->reason
            )
        );
    }
}
