<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'student_id',
        'event_id',
        'award_id',
        'path',
        'documents',
        'grade',
        'year',
        'level',
        'status',
        'campus',
    ];

    protected $casts = [
        'documents' => 'array',
        'level' => RoleLevel::class,
        'status' => ApprovalStatus::class,
    ];

    public function scopeWhereEventStatus($query, string $status, ?User $user = null)
    {
        if ($user && $user->role === UserRole::NISIT) {
            return $query;
        }

        return $query->whereHas('event', fn($q) => $q->where('status', $status));
    }

    public function scopeRoleLevelFilter($query, RoleLevel $roleLevel)
    {
        return $query->where(function ($q) use ($roleLevel) {
            $q->where(function ($q2) use ($roleLevel) {
                $q2->where('level', $roleLevel->value)
                    ->where('status', ApprovalStatus::APPROVED->value);
            })
                ->orWhere('level', '>', $roleLevel->value);
        });
    }

    public function scopeVisibleFor($query, User $user)
    {
        // $level = $user->role->level()->value;

        return match ($user->role) {
            UserRole::NISIT => $query->whereHas(
                'user',
                fn($q) => $q->where('student_id', $user->student_id)
            ),

            UserRole::DEPT_HEAD => $query->whereHas(
                'user',
                fn($q) => $q->where('department_id', $user->department_id)
            ),

            UserRole::ASSO_DEAN => $query->roleLevelFilter(RoleLevel::DEPT_HEAD)
                ->whereHas(
                    'user',
                    fn($q) => $q->where('faculty_id', $user->faculty_id)
                ),

            UserRole::DEAN => $query->roleLevelFilter(RoleLevel::ASSO_DEAN)
                ->whereHas(
                    'user',
                    fn($q) => $q->where('faculty_id', $user->faculty_id)
                ),

            UserRole::ADMIN => $query->roleLevelFilter(RoleLevel::DEAN),

            UserRole::BOARD => $query->roleLevelFilter(RoleLevel::ADMIN),

            UserRole::CHANCELLOR => $query->where('status', '!=', 'REJECTED'),

            default => $query,
        };
    }

    public function scopeFilterByStatus($query, string $status, int $level)
    {
        $previousLevel = $level - 1;

        return match ($status) {
            'PENDING' => $query->where('level', $previousLevel)
                ->where('status', ApprovalStatus::APPROVED->value),

            'REJECTED' => $query->where('level', $level)
                ->where('status', ApprovalStatus::REJECTED->value),

            'APPROVED' => $query->where(function ($q) use ($level) {
                $q->where(function ($q2) use ($level) {
                    $q2->where('level', $level)
                        ->where('status', ApprovalStatus::APPROVED->value);
                })
                    ->orWhere('level', '>', $level);
            }),

            default => $query,
        };
    }

    public function scopeSearch($query, ?string $search)
    {
        if (! $search) {
            return $query;
        }

        $words = collect(explode(' ', trim($search)))
            ->filter()
            ->values();

        if ($words->isEmpty()) {
            return $query;
        }

        return $query->where(function ($q) use ($words) {
            $q->whereHas('user', function ($userQ) use ($words) {
                foreach ($words as $word) {
                    $userQ->where(function ($innerQ) use ($word) {
                        $innerQ->where('firstName', 'like', "%{$word}%")
                            ->orWhere('lastName', 'like', "%{$word}%")
                            ->orWhere('student_id', 'like', "%{$word}%");
                    });
                }
            });

            foreach ($words as $word) {
                $q->orWhere('id', 'like', "%{$word}%");
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'student_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }
}
