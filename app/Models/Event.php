<?php

namespace App\Models;

use App\Enums\Semester;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "academic_year",
    ];

    protected function casts() : array {
        return [
            'status' => Status::class,
        ];
    }
}
