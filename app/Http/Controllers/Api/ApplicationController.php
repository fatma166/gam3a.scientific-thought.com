<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->applications()
            ->with(['certificateTrack', 'choices.program.faculty.university', 'documents'])
            ->latest()
            ->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $settings = \App\Models\SiteContent::where('slug', 'site-settings')->where('is_active', true)->first();
        abort_unless($settings && ($settings->content['academic_year'] ?? null) === $data['academic_year'], 422, 'السنة الأكاديمية غير متاحة.');
        abort_if(($data['status'] ?? 'draft') !== 'draft', 422, 'احفظ الطلب كمسودة ثم أرفق المستندات وأرسله.');
        $data['submitted_at'] = ($data['status'] ?? 'draft') === 'submitted' ? now() : null;

        $application = DB::transaction(function () use ($request, $data) {
            $choices = $data['choices'] ?? [];
            unset($data['choices']);

            $application = $request->user()->applications()->create($data);

            foreach ($choices as $choice) {
                $application->choices()->create($choice);
            }

            return $application;
        });

        return response()->json($application->load(['choices.program', 'documents']), 201);
    }

    public function show(Request $request, Application $application)
    {
        abort_unless($application->student_id === $request->user()->id || in_array($request->user()->role, ['admin', 'operator'], true), 403);

        return $application->load(['certificateTrack', 'choices.program.faculty.university', 'documents']);
    }

    public function update(Request $request, Application $application)
    {
        abort_unless($application->student_id === $request->user()->id, 403);
        abort_if($application->status !== Application::STATUS_DRAFT, 422, 'لا يمكن تعديل الطلب بعد الإرسال.');

        $data = $this->validated($request, partial: true);
        if (($data['status'] ?? null) === 'submitted') {
            $settings = \App\Models\SiteContent::where('slug', 'site-settings')->where('is_active', true)->first();
            $serviceId = $data['meta']['service_id'] ?? $application->meta['service_id'] ?? null;
            $service = \App\Models\SiteContent::where('kind', 'service')->where('is_active', true)->find($serviceId);
            abort_unless($service && $settings, 422, 'الخدمة غير متاحة.');
            $required = array_unique(array_merge($settings->content['required_documents'] ?? [], $service->content['required_documents'] ?? []));
            $uploaded = $application->documents()->whereNotIn('status', ['rejected', 'missing'])->pluck('type')->all();
            $missing = array_diff($required, $uploaded);
            if ($missing) throw \Illuminate\Validation\ValidationException::withMessages(['documents' => 'المستندات الناقصة: '.implode('، ', $missing)]);
            if (($service->content['requires_choices'] ?? false) && !$application->choices()->exists() && empty($data['choices'])) {
                throw \Illuminate\Validation\ValidationException::withMessages(['choices' => 'أضف رغبة واحدة على الأقل.']);
            }
        }
        DB::transaction(function () use ($application, $data) {
            $choices = $data['choices'] ?? null;
            unset($data['choices']);
            if (isset($data['meta'])) $data['meta'] = array_merge($application->meta ?? [], $data['meta']);
            if (($data['status'] ?? null) === 'submitted') $data['submitted_at'] = now();
            $application->update($data);
            if ($choices !== null) {
                $application->choices()->delete();
                $application->choices()->createMany($choices);
            }
        });

        return $application->fresh(['choices.program', 'documents']);
    }

    public function destroy(Request $request, Application $application)
    {
        abort_unless($application->student_id === $request->user()->id, 403);
        abort_if($application->status !== Application::STATUS_DRAFT, 422, 'يمكن حذف المسودات فقط.');

        $application->delete();

        return response()->noContent();
    }

    private function validated(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'certificate_track_id' => ['nullable', 'exists:certificate_tracks,id'],
            'academic_year' => [$required, 'string', 'max:20'],
            'status' => ['sometimes', 'in:draft,submitted'],
            'full_name' => [$required, 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:80'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'score' => ['nullable', 'numeric', 'between:0,100'],
            'meta' => ['nullable', 'array:service_id,question'],
            'meta.service_id' => [$partial ? 'sometimes' : 'required', \Illuminate\Validation\Rule::exists('site_contents', 'id')->where('kind', 'service')->where('is_active', true)],
            'meta.question' => ['nullable', 'string', 'max:4000'],
            'choices' => ['sometimes', 'array', 'max:10'],
            'choices.*.program_id' => ['required_with:choices', 'distinct', \Illuminate\Validation\Rule::exists('programs', 'id')->where('is_active', true)],
            'choices.*.rank' => ['required_with:choices', 'integer', 'distinct', 'between:1,10'],
            'choices.*.notes' => ['nullable', 'string'],
        ]);
    }
}
