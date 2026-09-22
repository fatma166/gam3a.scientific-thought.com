<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalculatorRule;
use App\Models\EquivalencyCenter;
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
}
