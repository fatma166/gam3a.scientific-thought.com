<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['slug', 'name', 'kind', 'content', 'sort_order', 'is_active'];
    protected $casts = ['content' => 'array', 'sort_order' => 'integer', 'is_active' => 'boolean'];
}
