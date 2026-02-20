<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Award extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'form_path',
        'requirements',
    ];

    protected $casts = [
        'requirements' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_award')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_award')
            ->withTimestamps();
    }

    public function awardRegistrations(): HasMany
    {
        return $this->HasMany(AwardRegistration::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'award_id');
    }
}
