<?php
namespace Tests\Feature;

use App\Models\{User, University, Faculty, Program, CertificateTrack, CalculatorRule, AdmissionRule, SiteContent, Application};
use App\Services\EligibilityCalculator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminDrivenPlatformTest extends TestCase
{
    use DatabaseTransactions;

    private function user(string $role = 'student'): User
    {
        return User::create(['name' => 'Test', 'email' => uniqid('review-').'@example.test', 'password' => 'test-password-123', 'role' => $role]);
    }

    public function test_admin_content_changes_are_public_and_unpublished_entries_are_hidden(): void
    {
        $this->actingAs($this->user('admin'), 'sanctum');
        $id = $this->postJson('/api/admin/site-content', ['slug' => uniqid('test-'), 'name' => 'Test service', 'kind' => 'service', 'content' => ['description' => 'Before'], 'is_active' => true])->assertCreated()->json('id');
        $this->patchJson('/api/admin/site-content/'.$id, ['content' => ['description' => 'After']])->assertOk();
        $this->getJson('/api/site')->assertJsonFragment(['description' => 'After']);
        $this->patchJson('/api/admin/site-content/'.$id, ['is_active' => false])->assertOk();
        $this->getJson('/api/site')->assertJsonMissing(['description' => 'After']);
    }

    public function test_students_cannot_modify_admin_resources(): void
    {
        $this->actingAs($this->user(), 'sanctum');
        $this->postJson('/api/admin/site-content', [])->assertForbidden();
    }

    public function test_weighted_calculation_uses_only_submitted_weights_and_rejects_unknown_subjects(): void
    {
        $calculator = new EligibilityCalculator();
        $result = $calculator->calculate(['weights' => ['math' => 2, 'english' => 1]], ['required_subjects' => ['math']], ['math' => 90]);
        $this->assertEquals(90, $result['score']);
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $calculator->calculate(['weights' => ['math' => 1]], [], ['injected' => 100]);
    }

    public function test_calculator_matches_published_admission_rules_and_rejects_disabled_calculator(): void
    {
        $track = CertificateTrack::create(['name' => 'Test track', 'slug' => uniqid('track-')]);
        $university = University::create(['name' => 'Test university', 'slug' => uniqid('uni-'), 'city' => 'Test', 'type' => 'Public']);
        $faculty = Faculty::create(['university_id' => $university->id, 'name' => 'Test faculty', 'slug' => uniqid('faculty-')]);
        $program = Program::create(['faculty_id' => $faculty->id, 'name' => 'Test program', 'slug' => uniqid('program-')]);
        $rule = CalculatorRule::create(['name' => 'Test rule', 'certificate_track_id' => $track->id, 'formula' => ['weights' => ['math' => 1]], 'inputs_schema' => ['required_subjects' => ['math']]]);
        AdmissionRule::create(['program_id' => $program->id, 'certificate_track_id' => $track->id, 'minimum_score' => 80, 'required_subjects' => ['math' => 85]]);
        $this->postJson('/api/calculate-equivalency', ['calculator_rule_id' => $rule->id, 'grades' => ['math' => 90]])->assertOk()->assertJsonPath('equivalent_score', 90)->assertJsonPath('eligible_programs.0.id', $program->id);
        $this->postJson('/api/calculate-equivalency', ['calculator_rule_id' => $rule->id, 'grades' => ['math' => 82]])->assertOk()->assertJsonCount(0, 'eligible_programs');
        $this->postJson('/api/calculate-equivalency', ['calculator_rule_id' => $rule->id, 'grades' => ['math' => 101]])->assertUnprocessable();
        $rule->update(['is_active' => false]);
        $this->postJson('/api/calculate-equivalency', ['calculator_rule_id' => $rule->id, 'grades' => ['math' => 90]])->assertNotFound();
    }

    public function test_application_ownership_and_admin_metadata_are_protected(): void
    {
        $owner = $this->user();
        $application = Application::create(['student_id' => $owner->id, 'full_name' => 'Student', 'academic_year' => '2026', 'status' => 'draft']);
        $this->actingAs($this->user(), 'sanctum');
        $this->getJson('/api/applications/'.$application->id)->assertForbidden();
        $this->actingAs($owner, 'sanctum');
        $this->patchJson('/api/applications/'.$application->id, ['meta' => ['admin_notes' => [['note' => 'forged']]]])->assertUnprocessable();
    }

    public function test_required_documents_prevent_premature_submission(): void
    {
        $student = $this->user();
        $settings = SiteContent::where('slug', 'site-settings')->firstOrFail();
        $settings->update(['content' => array_merge($settings->content, ['required_documents' => ['Passport']])]);
        $service = SiteContent::create(['slug' => uniqid('service-'), 'name' => 'Test', 'kind' => 'service', 'content' => []]);
        $application = Application::create(['student_id' => $student->id, 'full_name' => 'Test', 'academic_year' => '2026', 'status' => 'draft', 'meta' => ['service_id' => $service->id]]);
        $this->actingAs($student, 'sanctum');
        $this->patchJson('/api/applications/'.$application->id, ['status' => 'submitted'])->assertUnprocessable()->assertJsonValidationErrors('documents');
        $this->assertSame('draft', $application->fresh()->status);
    }
}
