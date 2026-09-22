<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdmissionRule;
use App\Models\CalculatorRule;
use App\Models\CertificateTrack;
use App\Models\EquivalencyCenter;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Http\Request;

class AdminResourceController extends Controller
{
    private array $resources = [
        'site-content' => \App\Models\SiteContent::class,
        'universities' => University::class,
        'faculties' => Faculty::class,
        'programs' => Program::class,
        'certificate-tracks' => CertificateTrack::class,
        'admission-rules' => AdmissionRule::class,
        'calculator-rules' => CalculatorRule::class,
        'equivalency-centers' => EquivalencyCenter::class,
    ];

    public function index(string $resource, Request $request)
    {
        $model = $this->model($resource);

        return $model::query()->latest()->paginate((int) $request->query('per_page', 25));
    }

    public function store(string $resource, Request $request)
    {
        $model = $this->model($resource);
        $data = $this->validated($resource, $request);

        return response()->json($model::create($data), 201);
    }

    public function show(string $resource, int $id)
    {
        return $this->model($resource)::findOrFail($id);
    }

    public function update(string $resource, int $id, Request $request)
    {
        $item = $this->model($resource)::findOrFail($id);
        $item->update($this->validated($resource, $request, partial: true));

        return $item->fresh();
    }

    public function destroy(string $resource, int $id)
    {
        $this->model($resource)::findOrFail($id)->delete();

        return response()->noContent();
    }

    private function model(string $resource): string
    {
        abort_unless(isset($this->resources[$resource]), 404);

        return $this->resources[$resource];
    }

    public function validated(string $resource, Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return match ($resource) {
            'site-content' => $request->validate([
                'slug' => [$required, 'string', 'max:160', 'regex:/^[a-z0-9-]+$/'],
                'name' => [$required, 'string', 'max:255'],
                'kind' => [$required, 'in:settings,page,service,article,faq'],
                'content' => [$required, 'array'],
                'content.title' => ['sometimes', 'string', 'max:255'],
                'content.description' => ['sometimes', 'string', 'max:10000'],
                'content.content' => ['sometimes', 'string', 'max:100000'],
                'content.required_documents' => ['sometimes', 'array'],
                'content.required_documents.*' => ['string', 'max:80'],
                'content.requires_choices' => ['sometimes', 'boolean'],
                'content.features' => ['sometimes', 'array'],
                'content.features.*' => ['string'],
                'content.academic_year' => ['sometimes', 'string', 'max:20'],
                'sort_order' => ['sometimes', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'universities' => $request->validate([
                'slug' => [$required, 'string', 'max:160'],
                'name' => [$required, 'string', 'max:255'],
                'city' => [$required, 'string', 'max:120'],
                'type' => [$required, 'string', 'max:80'],
                'acceptance_label' => ['nullable', 'string'],
                'image_url' => ['nullable', 'string'],
                'description' => ['nullable', 'string'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'faculties' => $request->validate([
                'university_id' => [$required, 'exists:universities,id'],
                'slug' => [$required, 'string', 'max:160'],
                'name' => [$required, 'string', 'max:255'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'programs' => $request->validate([
                'faculty_id' => [$required, 'exists:faculties,id'],
                'slug' => [$required, 'string', 'max:160'],
                'name' => [$required, 'string', 'max:255'],
                'degree' => ['sometimes', 'string'],
                'language' => ['sometimes', 'string'],
                'duration_years' => ['nullable', 'integer'],
                'tuition_amount' => ['nullable', 'numeric'],
                'tuition_currency' => ['sometimes', 'string', 'size:3'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'certificate-tracks' => $request->validate([
                'slug' => [$required, 'string', 'max:160'],
                'name' => [$required, 'string', 'max:255'],
                'country' => ['nullable', 'string'],
                'requirements' => ['nullable', 'array'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'admission-rules' => $request->validate([
                'program_id' => [$required, 'exists:programs,id'],
                'certificate_track_id' => [$required, 'exists:certificate_tracks,id'],
                'minimum_score' => ['nullable', 'numeric', 'between:0,100'],
                'required_subjects' => ['nullable', 'array'],
                'notes' => ['nullable', 'string'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'calculator-rules' => $request->validate([
                'name' => [$required, 'string', 'max:255'],
                'certificate_track_id' => ['nullable', 'exists:certificate_tracks,id'],
                'country' => ['nullable', 'string'],
                'rule_type' => ['sometimes', 'string'],
                'formula' => ['nullable', 'array'],
                'formula.weights' => ['required', 'array', 'min:1'],
                'formula.weights.*' => ['required', 'numeric', 'gt:0'],
                'formula.bonus' => ['sometimes', 'numeric', 'between:-100,100'],
                'formula.grade_map' => ['sometimes', 'array'],
                'formula.grade_map.*' => ['numeric', 'between:0,100'],
                'inputs_schema' => ['nullable', 'array'],
                'inputs_schema.required_subjects' => ['sometimes', 'array'],
                'inputs_schema.required_subjects.*' => ['string'],
                'inputs_schema.min_subjects' => ['sometimes', 'integer', 'min:1'],
                'result_schema' => ['nullable', 'array'],
                'notes' => ['nullable', 'string'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
            'equivalency-centers' => $request->validate([
                'name' => [$required, 'string', 'max:255'],
                'country' => [$required, 'string', 'max:120'],
                'city' => ['nullable', 'string'],
                'authority' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
                'website_url' => ['nullable', 'string'],
                'phone' => ['nullable', 'string'],
                'email' => ['nullable', 'email'],
                'required_documents' => ['nullable', 'array'],
                'processing_notes' => ['nullable', 'string'],
                'is_active' => ['sometimes', 'boolean'],
            ]),
        };
    }
}
