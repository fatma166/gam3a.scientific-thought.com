<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_MISSING_DOCUMENTS = 'missing_documents';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'student_id',
        'certificate_track_id',
        'academic_year',
        'status',
        'full_name',
        'passport_number',
        'nationality',
        'score',
        'meta',
        'submitted_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'meta' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function certificateTrack()
    {
        return $this->belongsTo(CertificateTrack::class);
    }

    public function choices()
    {
        return $this->hasMany(ApplicationChoice::class)->orderBy('rank');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
