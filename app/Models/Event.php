<?php

namespace App\Models;

use App\Enums\Semester;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
