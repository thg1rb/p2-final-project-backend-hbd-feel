<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AwardRegistration extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'award_id',
        'event_id',
        'first_name',
        'last_name',
        'academic_year',
        'status',
//        'award_type',
        'awardable_id',     // เพิ่ม
        'awardable_type',   // เพิ่ม
    ];

    public function award() : BelongsTo
    {
        return $this->belongsTo(Award::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }


    public function awardable(): MorphTo
    {
        return $this->morphTo();
    }


}
