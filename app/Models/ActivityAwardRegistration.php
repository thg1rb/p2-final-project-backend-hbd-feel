<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ActivityAwardRegistration extends Model
{
    //
    use HasFactory;

    protected $table = 'activity_award_registrations';

    protected $fillable = [
        'activity_types',
        'award_date',
        'project_name',
        'team_name',
        'work_name',
        'award_name',
        'organizer',
    ];

    protected $casts = [
        'activity_types' => 'array',
        'award_date' => 'date',
    ];

    public function awardRegistration(): MorphOne
    {
        return $this->morphOne(AwardRegistration::class, 'awardable');
    }


}
