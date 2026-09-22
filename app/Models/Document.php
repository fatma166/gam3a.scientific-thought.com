<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'application_id',
        'type',
        'original_name',
        'disk',
        'path',
        'mime_type',
        'size',
        'status',
        'review_notes',
    ];

    protected $casts = ['size' => 'integer'];
}
