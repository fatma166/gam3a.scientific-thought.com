<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'application_id',
        'provider',
        'provider_reference',
        'amount',
        'currency',
        'status',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];
}
