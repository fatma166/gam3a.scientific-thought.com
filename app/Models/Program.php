<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'faculty_id',
        'name',
        'slug',
        'degree',
        'language',
        'duration_years',
        'tuition_amount',
        'tuition_currency',
        'is_active',
    ];

    protected $casts = [
        'duration_years' => 'integer',
        'tuition_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function admissionRules()
    {
        return $this->hasMany(AdmissionRule::class);
    }
}
