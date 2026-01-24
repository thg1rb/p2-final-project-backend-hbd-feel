<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InnovationAwardRegistration extends Model
{
    //
    use HasFactory;
    protected $table = 'innovation_award_registrations';

    protected $fillable = [
        'award_name'
    ];

    public function awardRegistration(): MorphOne
    {
        return $this->morphOne(AwardRegistration::class, 'awardable');
    }

}
