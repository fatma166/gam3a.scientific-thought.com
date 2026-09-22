<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class EligibilityCalculator
{
    public function calculate(array $formula, array $schema, array $grades): array
    {
        $weights = $formula['weights'] ?? [];
        $mapping = $formula['grade_map'] ?? [];
        if (!$weights || array_filter($weights, fn ($w) => !is_numeric($w) || $w <= 0)) {
            throw ValidationException::withMessages(['calculator_rule_id' => 'يجب أن يحدد الأدمن مواد الحاسبة وأوزانها الموجبة.']);
        }
        if (array_diff(array_keys($grades), array_keys($weights))) {
            throw ValidationException::withMessages(['grades' => 'توجد مواد غير معتمدة في القاعدة.']);
        }
        $required = $schema['required_subjects'] ?? array_keys($weights);
        $normalized = [];
        foreach ($grades as $subject => $grade) {
            if (!is_scalar($grade) || is_bool($grade)) {
                throw ValidationException::withMessages(['grades' => 'درجة غير صحيحة.']);
            }
            $value = $mapping[(string) $grade] ?? $grade;
            if (!is_numeric($value) || $value < 0 || $value > 100) {
                throw ValidationException::withMessages(['grades.'.$subject => 'الدرجة يجب أن تكون من 0 إلى 100 أو تقديرًا معتمدًا.']);
            }
            $normalized[$subject] = (float) $value;
        }
        if (array_diff($required, array_keys($normalized)) || count($normalized) < max(1, $schema['min_subjects'] ?? 1)) {
            throw ValidationException::withMessages(['grades' => 'أكمل المواد المطلوبة والحد الأدنى لعدد المواد.']);
        }
        $sum = 0;
        $total = 0;
        foreach ($normalized as $subject => $grade) {
            $sum += $grade * $weights[$subject];
            $total += $weights[$subject];
        }
        return ['score' => round(max(0, min(100, $sum / $total + ($formula['bonus'] ?? 0))), 2), 'grades' => $normalized];
    }
}
