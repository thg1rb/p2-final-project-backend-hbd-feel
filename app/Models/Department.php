<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Department::class);
    }
}
