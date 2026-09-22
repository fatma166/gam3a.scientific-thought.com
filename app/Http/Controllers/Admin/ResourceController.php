<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionRule;
use App\Models\CalculatorRule;
use App\Models\CertificateTrack;
use App\Models\EquivalencyCenter;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    private array $resources = [
        'site-content' => ['label' => 'محتوى الموقع والخدمات', 'model' => \App\Models\SiteContent::class],
        'universities' => ['label' => 'الجامعات', 'model' => University::class],
        'faculties' => ['label' => 'الكليات', 'model' => Faculty::class],
        'programs' => ['label' => 'البرامج', 'model' => Program::class],
        'certificate-tracks' => ['label' => 'الشهادات', 'model' => CertificateTrack::class],
        'admission-rules' => ['label' => 'شروط القبول', 'model' => AdmissionRule::class],
        'calculator-rules' => ['label' => 'قواعد الحاسبة', 'model' => CalculatorRule::class],
        'equivalency-centers' => ['label' => 'أماكن المعادلة', 'model' => EquivalencyCenter::class],
    ];

    public function index(string $resource)
    {
        $config = $this->resource($resource);
        $items = $config['model']::latest()->paginate(20);

        return view('admin.resources.index', compact('resource', 'config', 'items'));
    }

    public function create(string $resource)
    {
        $config = $this->resource($resource);

        return view('admin.resources.form', compact('resource', 'config'));
    }

    public function store(string $resource, Request $request)
    {
        $model = $this->resource($resource)['model'];
        $request->merge($this->payload($request));
        $model::create(app(\App\Http\Controllers\Api\AdminResourceController::class)->validated($resource, $request));

        return redirect("/admin/resources/{$resource}")->with('success', 'تمت الإضافة.');
    }

    public function edit(string $resource, int $id)
    {
        $config = $this->resource($resource);
        $item = $config['model']::findOrFail($id);

        return view('admin.resources.form', compact('resource', 'config', 'item'));
    }

    public function update(string $resource, int $id, Request $request)
    {
        $model = $this->resource($resource)['model'];
        $request->merge($this->payload($request));
        $model::findOrFail($id)->update(app(\App\Http\Controllers\Api\AdminResourceController::class)->validated($resource, $request, true));

        return redirect("/admin/resources/{$resource}")->with('success', 'تم الحفظ.');
    }

    public function destroy(string $resource, int $id)
    {
        $model = $this->resource($resource)['model'];
        $model::findOrFail($id)->delete();

        return back()->with('success', 'تم الحذف.');
    }

    private function resource(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);

        return $this->resources[$resource];
    }

    private function payload(Request $request): array
    {
        $payload = $request->except(['_token', '_method']);

        foreach ($payload as $key => $value) {
            if ($value === '') {
                $payload[$key] = null;
            } elseif (in_array($key, ['content', 'requirements', 'required_subjects', 'formula', 'inputs_schema', 'result_schema', 'required_documents', 'meta'], true) && is_string($value)) {
                try {
                    $payload[$key] = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException) {
                    throw \Illuminate\Validation\ValidationException::withMessages([$key => 'صيغة JSON غير صحيحة. لم يتم حفظ التعديل.']);
                }
            } elseif ($key === 'is_active') {
                $payload[$key] = (bool) $value;
            }
        }

        $payload['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : ($payload['is_active'] ?? true);

        return $payload;
    }
}
