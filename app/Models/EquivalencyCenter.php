<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquivalencyCenter extends Model
{
    protected $fillable = [
        'name',
        'country',
        'city',
        'authority',
        'address',
        'website_url',
        'phone',
        'email',
        'required_documents',
        'processing_notes',
        'is_active',
    ];

    protected $casts = [
        'required_documents' => 'array',
        'is_active' => 'boolean',
    ];
}
