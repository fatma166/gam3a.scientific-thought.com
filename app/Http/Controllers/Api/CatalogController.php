<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CertificateTrack;
use App\Models\Program;
use App\Models\University;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function universities(Request $request)
    {
        return University::where('is_active', true)
            ->withCount(['programs' => fn ($q) => $q->where('programs.is_active', true)->where('faculties.is_active', true)])
            ->when($request->query('q'), fn ($q, $v) => $q->where('name', 'ilike', '%'.$v.'%'))
            ->when($request->query('city'), fn ($q, $v) => $q->where('city', $v))
            ->when($request->query('type'), fn ($q, $v) => $q->where('type', $v))
            ->orderBy('name')->paginate(min(100, max(1, (int) $request->query('per_page', 20))));
    }

    public function university(string $slug)
    {
        return University::where('is_active', true)->where('slug', $slug)
            ->with(['faculties' => fn ($q) => $q->where('is_active', true),
                'faculties.programs' => fn ($q) => $q->where('is_active', true),
                'faculties.programs.admissionRules' => fn ($q) => $q->where('is_active', true)])
            ->firstOrFail();
    }

    public function programs(Request $request)
    {
        return Program::where('is_active', true)
            ->whereHas('faculty', fn ($q) => $q->where('is_active', true)->whereHas('university', fn ($u) => $u->where('is_active', true)))
            ->with(['faculty.university', 'admissionRules' => fn ($q) => $q->where('is_active', true)])
            ->when($request->query('q'), fn ($q, $v) => $q->where('name', 'ilike', '%'.$v.'%'))
            ->when($request->query('university'), fn ($q, $v) => $q->whereHas('faculty.university', fn ($u) => $u->where('slug', $v)))
            ->orderBy('name')->paginate(min(100, max(1, (int) $request->query('per_page', 20))));
    }

    public function certificateTracks()
    {
        return CertificateTrack::where('is_active', true)->orderBy('name')->get();
    }
}
