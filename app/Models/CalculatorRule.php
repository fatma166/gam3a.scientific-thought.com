<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculatorRule extends Model
{
    protected $fillable = [
        'name',
        'certificate_track_id',
        'country',
        'rule_type',
        'formula',
        'inputs_schema',
        'result_schema',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'formula' => 'array',
        'inputs_schema' => 'array',
        'result_schema' => 'array',
        'is_active' => 'boolean',
    ];

    public function certificateTrack()
    {
        return $this->belongsTo(CertificateTrack::class);
    }
}
