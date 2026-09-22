<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'city',
        'type',
        'acceptance_label',
        'image_url',
        'description',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }

    public function programs()
    {
        return $this->hasManyThrough(Program::class, Faculty::class);
    }
}
