<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
class BehaviorAwardRegistration extends Model
{
    //
    use HasFactory;
    protected $table = 'behavior_award_registrations';
    protected $fillable = [
        'approver',
    ];

    public function awardRegistration(): MorphOne
    {
        return $this->morphOne(AwardRegistration::class, 'awardable');
    }

}
