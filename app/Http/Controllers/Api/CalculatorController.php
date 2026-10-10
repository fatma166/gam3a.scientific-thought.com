<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalculatorRule;
use App\Models\EquivalencyCenter;
use App\Models\EligibilitySearchRule;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function rules(Request $request)
    {
        return CalculatorRule::query()
            ->with('certificateTrack')
            ->where('is_active', true)
            ->when($request->query('certificate_track_id'), fn ($query, $id) => $query->where('certificate_track_id', $id))
            ->when($request->query('country'), fn ($query, $country) => $query->where('country', $country))
            ->get();
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'calculator_rule_id' => ['required', 'exists:calculator_rules,id'],
            'grades' => ['required', 'array'],
        ]);

        $rule = CalculatorRule::where('is_active', true)->findOrFail($data['calculator_rule_id']);
        abort_unless($rule->certificateTrack?->is_active, 422, 'مسار الشهادة غير متاح.');
        $result = app(\App\Services\EligibilityCalculator::class)->calculate($rule->formula ?? [], $rule->inputs_schema ?? [], $data['grades']);
        $programs = \App\Models\Program::where('is_active', true)
            ->whereHas('faculty', fn ($q) => $q->where('is_active', true)->whereHas('university', fn ($u) => $u->where('is_active', true)))
            ->with(['faculty.university', 'admissionRules' => fn ($q) => $q->where('is_active', true)->where('certificate_track_id', $rule->certificate_track_id)])
            ->get()->filter(function ($program) use ($result) {
                return $program->admissionRules->contains(function ($admission) use ($result) {
                    if ($admission->minimum_score === null || $result['score'] < $admission->minimum_score) return false;
                    foreach ($admission->required_subjects ?? [] as $key => $value) {
                        $subject = is_int($key) ? $value : $key;
                        $minimum = is_int($key) ? 0 : $value;
                        if (!is_string($subject) || !is_numeric($minimum) || !array_key_exists($subject, $result['grades']) || $result['grades'][$subject] < $minimum) return false;
                    }
                    return true;
                });
            })->values();

        return [
            'calculator_rule' => $rule,
            'equivalent_score' => $result['score'],
            'eligible_programs' => $programs,
            'rule_updated_at' => $rule->updated_at,
            'decision' => 'initial_estimate',
        ];
    }

    public function equivalencyCenters(Request $request)
    {
        return EquivalencyCenter::query()
            ->where('is_active', true)
            ->when($request->query('country'), fn ($query, $country) => $query->where('country', $country))
            ->when($request->query('city'), fn ($query, $city) => $query->where('city', $city))
            ->orderBy('country')
            ->orderBy('city')
            ->get();
    }

    public function searchEligibility(Request $request)
    {
        $data = $request->validate([
            'certificate_track_id' => ['required', 'exists:certificate_tracks,id'],
            'score' => ['required', 'numeric', 'between:0,100'],
            'specialty' => ['nullable', 'string', 'max:120'],
        ]);

        $specialty = trim((string) ($data['specialty'] ?? ''));

        $rules = EligibilitySearchRule::query()
            ->with(['certificateTrack', 'program.faculty.university'])
            ->where('is_active', true)
            ->where('certificate_track_id', $data['certificate_track_id'])
            ->where('minimum_score', '<=', $data['score'])
            ->when($specialty !== '', function ($query) use ($specialty) {
                $query->where(function ($inner) use ($specialty) {
                    $inner->whereNull('specialty_keyword')
                        ->orWhere('specialty_keyword', '')
                        ->orWhereRaw('LOWER(?) LIKE CONCAT(\'%\', LOWER(specialty_keyword), \'%\')', [$specialty])
                        ->orWhereHas('program', fn ($program) => $program->where('name', 'ilike', '%'.$specialty.'%'));
                });
            })
            ->orderByDesc('priority')
            ->orderByDesc('minimum_score')
            ->limit(12)
            ->get();

        return [
            'matches' => $rules,
            'summary' => $rules->isEmpty()
                ? 'لم نجد قواعد مطابقة حاليًا. جرّب تعديل المجموع أو اطلب استشارة.'
                : 'وجدنا '.$rules->count().' نتيجة مناسبة حسب القواعد المنشورة في لوحة التحكم.',
        ];
    }
}
