<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTrack extends Model
{
    protected $fillable = ['name', 'slug', 'country', 'requirements', 'is_active'];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
    ];
}
