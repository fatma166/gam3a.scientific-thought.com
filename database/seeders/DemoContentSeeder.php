<?php

namespace Database\Seeders;

use App\Models\AdmissionRule;
use App\Models\CalculatorRule;
use App\Models\CertificateTrack;
use App\Models\EquivalencyCenter;
use App\Models\EligibilitySearchRule;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            ['cairo-university', 'جامعة القاهرة', 'الجيزة', 'حكومية', 'مرتفعة التنافس', 'https://media.elbalad.news/2024/10/large/879/7/408.jpg'],
            ['ain-shams', 'جامعة عين شمس', 'القاهرة', 'حكومية', 'متوسطة إلى مرتفعة', 'https://www.egyptke.com/UploadCache/libfiles/24/7/800x450o/916.jpg'],
            ['mansoura-university', 'جامعة المنصورة', 'الدقهلية', 'حكومية', 'متوسطة', 'https://www.mans.edu.eg/images/speasyimagegallery/albums/1/images/70.jpg'],
            ['new-mansoura', 'جامعة المنصورة الجديدة', 'الساحل', 'أهلية', 'حسب البرنامج', 'https://www.elaosboa.com/wp-content/uploads/2022/08/elaosboa20784.jpg'],
            ['alexandria-university', 'جامعة الإسكندرية', 'الإسكندرية', 'حكومية', 'مرتفعة', 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=85'],
            ['helwan-university', 'جامعة حلوان', 'القاهرة', 'حكومية', 'متوسطة', 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1200&q=85'],
            ['assuit-university', 'جامعة أسيوط', 'أسيوط', 'حكومية', 'متوسطة', 'https://images.unsplash.com/photo-1498243691581-b145c3f54a5a?auto=format&fit=crop&w=1200&q=85'],
            ['zagaig-university', 'جامعة الزقازيق', 'الشرقية', 'حكومية', 'متوسطة', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1200&q=85'],
            ['galala-university', 'جامعة الجلالة', 'السويس', 'أهلية', 'حسب البرنامج', 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1200&q=85'],
            ['king-salman-international', 'جامعة الملك سلمان الدولية', 'جنوب سيناء', 'أهلية', 'حسب البرنامج', 'https://images.unsplash.com/photo-1607237138185-eedd9c632b0b?auto=format&fit=crop&w=1200&q=85'],
            ['bue', 'الجامعة البريطانية في مصر', 'القاهرة', 'خاصة', 'حسب البرنامج', 'https://images.unsplash.com/photo-1519452575417-564c1401ecc0?auto=format&fit=crop&w=1200&q=85'],
            ['guc', 'الجامعة الألمانية بالقاهرة', 'القاهرة', 'خاصة', 'حسب البرنامج', 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1200&q=85'],
        ];

        $programs = [
            ['medicine', 'الطب البشري', 'Bachelor', 'Arabic', 6, 92],
            ['dentistry', 'طب الأسنان', 'Bachelor', 'Arabic', 5, 90],
            ['pharmacy', 'الصيدلة', 'Bachelor', 'Arabic', 5, 88],
            ['engineering', 'الهندسة', 'Bachelor', 'Arabic', 5, 82],
            ['computer-science', 'علوم الحاسب', 'Bachelor', 'English', 4, 78],
            ['business', 'إدارة الأعمال', 'Bachelor', 'English', 4, 70],
            ['mass-communication', 'الإعلام', 'Bachelor', 'Arabic', 4, 68],
            ['physical-therapy', 'العلاج الطبيعي', 'Bachelor', 'Arabic', 5, 86],
        ];

        $tracks = CertificateTrack::where('is_active', true)->get();

        foreach ($universities as [$slug, $name, $city, $type, $acceptance, $image]) {
            $university = University::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'city' => $city,
                    'type' => $type,
                    'acceptance_label' => $acceptance,
                    'image_url' => $image,
                    'description' => "{$name} ضمن دليل المنصة، وتظهر برامجها للطالب حسب الشهادة والمجموع والرغبات.",
                    'is_active' => true,
                ]
            );

            $faculty = Faculty::updateOrCreate(
                ['university_id' => $university->id, 'slug' => 'main'],
                ['name' => 'البرامج الرئيسية', 'is_active' => true]
            );

            foreach ($programs as [$programSlug, $programName, $degree, $language, $duration, $minimumScore]) {
                $program = Program::updateOrCreate(
                    ['faculty_id' => $faculty->id, 'slug' => $programSlug],
                    [
                        'name' => $programName,
                        'degree' => $degree,
                        'language' => $language,
                        'duration_years' => $duration,
                        'tuition_amount' => $type === 'حكومية' ? null : ($minimumScore * 1000),
                        'tuition_currency' => 'EGP',
                        'is_active' => true,
                    ]
                );

                foreach ($tracks->take(4) as $track) {
                    AdmissionRule::updateOrCreate(
                        ['program_id' => $program->id, 'certificate_track_id' => $track->id],
                        [
                            'minimum_score' => $minimumScore,
                            'required_subjects' => ['English', 'Math'],
                            'notes' => 'قاعدة تجريبية قابلة للتعديل من لوحة التحكم حسب سنة القبول.',
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        foreach ($tracks as $track) {
            CalculatorRule::updateOrCreate(
                ['name' => "{$track->name} - قاعدة حساب مبدئية"],
                [
                    'certificate_track_id' => $track->id,
                    'country' => $track->country ?: 'Egypt',
                    'rule_type' => 'equivalency',
                    'formula' => ['weights' => ['english' => 1, 'math' => 1, 'science' => 1], 'bonus' => 0],
                    'inputs_schema' => ['required_subjects' => ['english', 'math'], 'grade_scale' => 'percentage'],
                    'result_schema' => ['score' => 'percentage', 'decision' => 'initial_estimate'],
                    'notes' => 'قاعدة مبدئية للعرض ويمكن تعديلها من الإدارة.',
                    'is_active' => true,
                ]
            );
        }

        foreach ([
            ['مكتب معادلات القاهرة', 'Egypt', 'Cairo', 'Supreme Council of Universities'],
            ['مكتب معادلات الإسكندرية', 'Egypt', 'Alexandria', 'Supreme Council of Universities'],
            ['مركز توثيق الشهادات السعودية', 'Saudi Arabia', 'Riyadh', 'Education Authority'],
            ['مركز توثيق الشهادات الكويتية', 'Kuwait', 'Kuwait City', 'Education Authority'],
            ['مركز توثيق الشهادات الإماراتية', 'UAE', 'Dubai', 'Education Authority'],
        ] as [$name, $country, $city, $authority]) {
            EquivalencyCenter::updateOrCreate(
                ['name' => $name, 'country' => $country],
                [
                    'city' => $city,
                    'authority' => $authority,
                    'address' => "{$city} - main office",
                    'required_documents' => ['Passport', 'Certificate', 'Transcript', 'Birth certificate'],
                    'processing_notes' => 'بيانات عرض قابلة للتعديل من لوحة التحكم.',
                    'is_active' => true,
                ]
            );
        }

        $keywords = [
            'طب' => ['medicine', 'dentistry', 'pharmacy', 'physical-therapy'],
            'هندسة' => ['engineering', 'computer-science'],
            'حاسب' => ['computer-science', 'engineering'],
            'صيدلة' => ['pharmacy'],
            'إدارة' => ['business'],
            'اعلام' => ['mass-communication'],
        ];

        foreach ($tracks as $track) {
            foreach ($keywords as $keyword => $programSlugs) {
                foreach (Program::whereIn('slug', $programSlugs)->limit(18)->get() as $program) {
                    $baseScore = match ($program->slug) {
                        'medicine' => 92,
                        'dentistry' => 90,
                        'pharmacy' => 88,
                        'physical-therapy' => 86,
                        'engineering' => 82,
                        'computer-science' => 78,
                        'business' => 70,
                        default => 68,
                    };

                    EligibilitySearchRule::updateOrCreate(
                        [
                            'certificate_track_id' => $track->id,
                            'program_id' => $program->id,
                            'specialty_keyword' => $keyword,
                        ],
                        [
                            'name' => "{$track->name} - {$keyword} - {$program->name}",
                            'minimum_score' => $baseScore,
                            'priority' => 100 - $baseScore,
                            'message' => "مناسب مبدئيًا لبحث {$keyword} عند مجموع {$baseScore}% أو أعلى.",
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
