<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
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
        'status',
        'grade',
        'year'
    ];

    protected $casts = [
        'documents' => 'array',
        'status' => ApplicationStatus::class
    ];

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
