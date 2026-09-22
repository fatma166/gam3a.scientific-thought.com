<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionRule extends Model
{
    protected $fillable = [
        'program_id',
        'certificate_track_id',
        'minimum_score',
        'required_subjects',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'minimum_score' => 'decimal:2',
        'required_subjects' => 'array',
        'is_active' => 'boolean',
    ];
}
