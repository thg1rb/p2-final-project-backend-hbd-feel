<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Notifications\ApiResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'firstName',
        'lastName',
        'username',
        'email',
        'password',
        'role',
        'faculty_id',
        'department_id',
        'campus'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'campus' => \App\Enums\CampusType::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRole::NISIT_DEV;
    }

    public function isStudent(): bool {
        return $this->role == UserRole::NISIT;
    }

    public function isUser(): bool
    {
        return $this->role != UserRole::NISIT_DEV;
    }

    public function getRedirectRoute(): string
    {
        if ($this->isAdmin()) {
            return route('main');
        }

        return route('award-registrations');
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'user_award')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function awardRegistrations()
    {
        return $this->hasMany(AwardRegistration::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'student_id', 'student_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ApiResetPasswordNotification($token));
    }
}
