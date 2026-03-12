<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    /** @use HasFactory<\Database\Factories\FacultyFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'campus'
    ];

    protected $casts = [
        'campus' => \App\Enums\CampusType::class,
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function department()
    {
        return $this->hasMany(Department::class);
    }
}
