<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }
}
