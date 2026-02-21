<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
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
    ];

    protected $casts = [
        'documents' => 'array',
        'level' => RoleLevel::class,
        'status' => ApprovalStatus::class,
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
