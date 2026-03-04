<?php

namespace App\Models;

use App\Enums\Semester;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'status',
        'academic_year',
        'semester',
        'start_date',
        'end_date',
        'path'
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'event_award')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')->withTimestamps();
    }

    public function awardRegistrations(): HasMany
    {
        return $this->HasMany(AwardRegistration::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'event_id');
    }
}
