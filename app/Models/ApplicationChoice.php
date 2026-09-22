<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationChoice extends Model
{
    protected $fillable = ['application_id', 'program_id', 'rank', 'status', 'notes'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
