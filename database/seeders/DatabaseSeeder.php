<?php

namespace Database\Seeders;

use App\Models\CertificateTrack;
use App\Models\CalculatorRule;
use App\Models\EquivalencyCenter;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gam3a.local'],
            ['name' => 'Gam3a Admin', 'password' => 'password', 'role' => 'admin']
        );

        foreach ([
            ['igcse-gcse', 'IGCSE / GCSE'],
            ['american-diploma', 'American Diploma'],
            ['ib', 'IB'],
            ['saudi-secondary', 'الثانوية السعودية'],
            ['kuwait-secondary', 'الثانوية الكويتية'],
            ['uae-secondary', 'الثانوية الإماراتية'],
            ['sudan-secondary', 'الثانوية السودانية'],
            ['other-arab-certificates', 'شهادات عربية أخرى'],
        ] as [$slug, $name]) {
            CertificateTrack::firstOrCreate(['slug' => $slug], ['name' => $name, 'requirements' => []]);
        }

        $igcse = CertificateTrack::where('slug', 'igcse-gcse')->first();
        CalculatorRule::firstOrCreate(
            ['name' => 'IGCSE Egypt initial equivalency'],
            [
                'certificate_track_id' => $igcse?->id,
                'country' => 'Egypt',
                'rule_type' => 'equivalency',
                'formula' => ['weights' => ['english' => 1, 'math' => 1, 'biology' => 1, 'chemistry' => 1, 'physics' => 1], 'bonus' => 0],
                'inputs_schema' => ['required_subjects' => ['english', 'math'], 'grade_scale' => 'percentage'],
                'result_schema' => ['score' => 'percentage', 'decision' => 'initial_estimate'],
                'notes' => 'Initial configurable rule for admission calculator. Replace with official yearly rules.',
            ]
        );

        EquivalencyCenter::firstOrCreate(
            ['name' => 'Supreme Council of Universities Equivalency Office', 'country' => 'Egypt'],
            [
                'city' => 'Cairo',
                'authority' => 'Supreme Council of Universities',
                'required_documents' => ['Passport copy', 'Certificate', 'Transcript', 'Birth certificate'],
                'processing_notes' => 'Use as an operational placeholder until official integration details are confirmed.',
            ]
        );

        $universities = [
            [
                'slug' => 'cairo-university',
                'name' => 'جامعة القاهرة',
                'city' => 'الجيزة',
                'type' => 'حكومية',
                'acceptance_label' => 'مرتفعة التنافس',
                'image_url' => 'https://media.elbalad.news/2024/10/large/879/7/408.jpg',
                'description' => 'برامج قوية في الطب والهندسة والعلوم الإنسانية مع احتياج واضح لمراجعة قواعد القبول حسب الشهادة.',
                'programs' => ['الطب البشري', 'الهندسة', 'الصيدلة'],
            ],
            [
                'slug' => 'ain-shams',
                'name' => 'جامعة عين شمس',
                'city' => 'القاهرة',
                'type' => 'حكومية',
                'acceptance_label' => 'متوسطة إلى مرتفعة',
                'image_url' => 'https://www.egyptke.com/UploadCache/libfiles/24/7/800x450o/916.jpg',
                'description' => 'اختيار مناسب للطلاب الباحثين عن تخصصات عملية داخل القاهرة مع تنوع في البرامج والكليات.',
                'programs' => ['الهندسة', 'الحاسبات والمعلومات', 'الألسن'],
            ],
            [
                'slug' => 'mansoura-university',
                'name' => 'جامعة المنصورة',
                'city' => 'الدقهلية',
                'type' => 'حكومية',
                'acceptance_label' => 'متوسطة',
                'image_url' => 'https://www.mans.edu.eg/images/speasyimagegallery/albums/1/images/70.jpg',
                'description' => 'جامعة قوية للطب والصيدلة والعلوم، وتظهر ضمن الترشيحات عند توافق المجموع والرغبات.',
                'programs' => ['الطب البشري', 'الصيدلة', 'طب الأسنان'],
            ],
            [
                'slug' => 'new-mansoura',
                'name' => 'جامعة المنصورة الجديدة',
                'city' => 'الساحل',
                'type' => 'أهلية',
                'acceptance_label' => 'حسب البرنامج',
                'image_url' => 'https://www.elaosboa.com/wp-content/uploads/2022/08/elaosboa20784.jpg',
                'description' => 'جامعة حديثة ببرامج بينية مناسبة للطلاب الباحثين عن تخصصات عملية وشروط قبول واضحة.',
                'programs' => ['الذكاء الاصطناعي', 'الأعمال', 'الطب'],
            ],
        ];

        foreach ($universities as $item) {
            $programNames = $item['programs'];
            unset($item['programs']);

            $university = University::updateOrCreate(['slug' => $item['slug']], $item);
            $faculty = Faculty::firstOrCreate(
                ['university_id' => $university->id, 'slug' => 'main'],
                ['name' => 'الكلية الرئيسية']
            );

            foreach ($programNames as $programName) {
                Program::firstOrCreate(
                    ['faculty_id' => $faculty->id, 'slug' => str($programName)->slug('-')->toString()],
                    ['name' => $programName, 'duration_years' => 4]
                );
            }
        }
    }
}
