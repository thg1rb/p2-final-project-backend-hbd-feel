<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationPolicy
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
    public function view(User $user, Application $application): bool
    {
        return $this->check($user, $application);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application): bool
    {
        return $this->check($user, $application);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Application $application): bool
    {
        return $user->isStudent() && $user->student_id == $application->student_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Application $application): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Application $application): bool
    {
        return false;
    }

    private function check(User $user, Application $application): bool {
        $event = Event::query()->where("id", $application->event_id)->first();
        $applicant = User::query()->where("student_id", $application->student_id)->first();

        if ($user->isStudent() && $user->student_id == $application->student_id) return true;

        if (!$user->isStudent() && $user->role == UserRole::BOARD)
            return $user->campus->value == $event->campus;

        if (!$user->isStudent() && ($user->role == UserRole::DEAN || $user->role == UserRole::ASSO_DEAN))
            return $user->campus->value == $event->campus && $user->faculty == $applicant->faculty;

        if (!$user->isStudent() && $user->role == UserRole::DEPT_HEAD)
            return $user->campus->value == $event->campus && $user->department == $applicant->department;

        return false;
    }
}
