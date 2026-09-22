<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['university_id', 'name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
