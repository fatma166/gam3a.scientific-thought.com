<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EligibilitySearchRule extends Model
{
    protected $fillable = [
        'name',
        'certificate_track_id',
        'program_id',
        'specialty_keyword',
        'minimum_score',
        'priority',
        'message',
        'is_active',
    ];

    protected $casts = [
        'minimum_score' => 'decimal:2',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    public function certificateTrack()
    {
        return $this->belongsTo(CertificateTrack::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
