<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'faculty_id'
    ];

    protected $casts = [
        'campus' => \App\Enums\CampusType::class,
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
