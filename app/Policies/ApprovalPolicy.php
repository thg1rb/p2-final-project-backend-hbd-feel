<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Approval;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApprovalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Approval $approval): bool
    {

        $application = Application::query()->where("id", $approval->application_id)->first();
        $event = Event::query()->where("id", $application->event_id)->first();
        $applicant = User::query()->where("student_id", $application->student_id)->first();

        if ($user->isStudent()) return $user->id == $application->id;

        if (!$user->isStudent() && ($user->role == UserRole::BOARD || $user->role == UserRole::NISIT_DEV))
            return $user->campus->value == $event->campus;

        if (!$user->isStudent() && ($user->role == UserRole::DEAN || $user->role == UserRole::ASSO_DEAN))
            return $user->campus->value == $event->campus && $user->faculty == $applicant->faculty;

        if (!$user->isStudent() && $user->role == UserRole::DEPT_HEAD)
            return $user->campus->value == $event->campus && $user->department == $applicant->department;

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->isStudent();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Approval $approval): bool
    {
        return $this->check($user, $approval);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Approval $approval): bool
    {
        return $this->check($user, $approval);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Approval $approval): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Approval $approval): bool
    {
        return false;
    }

    private function check(User $user, Approval $approval): bool {
        if ($user->isStudent()) return false;

        $application = Application::query()->where("id", $approval->application_id)->first();
        $event = Event::query()->where("id", $application->event_id)->first();
        $applicant = User::query()->where("student_id", $application->student_id)->first();

        if (!$user->isStudent() && ($user->role == UserRole::BOARD || $user->role == UserRole::NISIT_DEV))
            return $user->campus->value == $event->campus;

        if (!$user->isStudent() && ($user->role == UserRole::DEAN || $user->role == UserRole::ASSO_DEAN))
            return $user->campus->value == $event->campus && $user->faculty == $applicant->faculty;

        if (!$user->isStudent() && $user->role == UserRole::DEPT_HEAD)
            return $user->campus->value == $event->campus && $user->department == $applicant->department;

        return false;
    }
}
