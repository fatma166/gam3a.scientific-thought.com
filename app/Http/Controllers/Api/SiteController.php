<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Models\University;
use App\Models\Program;
use App\Models\CertificateTrack;

class SiteController extends Controller
{
    public function index()
    {
        return [
            'entries' => SiteContent::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'counts' => [
                'universities' => University::where('is_active', true)->count(),
                'programs' => Program::where('is_active', true)->whereHas('faculty', fn ($q) => $q->where('is_active', true)->whereHas('university', fn ($u) => $u->where('is_active', true)))->count(),
                'tracks' => CertificateTrack::where('is_active', true)->count(),
            ],
        ];
    }

    public function articles()
    {
        return SiteContent::where('kind', 'article')->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => array_merge($item->content, ['slug' => $item->slug, 'title' => $item->name]));
    }

    public function article(string $slug)
    {
        $item = SiteContent::where('kind', 'article')->where('is_active', true)->where('slug', $slug)->firstOrFail();
        return array_merge($item->content, ['slug' => $item->slug, 'title' => $item->name]);
    }
}
