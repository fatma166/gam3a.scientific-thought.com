<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $entries = json_decode(<<<'JSON'
[
  {
    "slug": "site-settings",
    "name": "هوية الموقع والتنقل",
    "kind": "settings",
    "content": {
      "brand": "TAQDEEM Edu",
      "tagline": "Admissions Platform",
      "footer": "خدمة قبول جامعي تساعد الطالب على اختيار المسار المناسب وتجهيز ملفه ومتابعة طلبه بثقة.",
      "academic_year": "2026 / 2027",
      "required_documents": [
        "جواز السفر",
        "شهادة الميلاد",
        "الشهادة الدراسية",
        "كشف الدرجات",
        "صورة شخصية"
      ],
      "navigation": [
        [
          "الجامعات",
          "/universities"
        ],
        [
          "البرامج",
          "/programs"
        ],
        [
          "الحاسبات",
          "/calculators"
        ],
        [
          "المقالات",
          "/articles"
        ],
        [
          "بوابة الطالب",
          "/dashboard"
        ],
        [
          "لوحة التشغيل",
          "/admin"
        ]
      ]
    }
  },
  {
    "slug": "home",
    "name": "الصفحة الرئيسية",
    "kind": "page",
    "content": {
      "title": "TAQDEEM Edu",
      "eyebrow": "منصة تقديم جامعي للطلاب الوافدين",
      "description": "منصة قبول تعليمية تجمع دليل الجامعات، حاسبات الشهادات، ترشيح البرامج، إدارة المستندات، ومتابعة الطلب.",
      "hero_image": "https://media.elbalad.news/2024/10/large/879/7/408.jpg",
      "benefits": [
        [
          "قبول أوضح",
          "يعرف الطالب الجامعات والبرامج المناسبة لشهادته ومجموعه قبل أن يبدأ الطلب."
        ],
        [
          "ملف واحد منظم",
          "بيانات الطالب، الرغبات، المستندات، الرسائل، والمدفوعات تظهر في رحلة واحدة."
        ],
        [
          "تنبيهات ومتابعة",
          "حالات الطلب واضحة: ناقص مستند، تحت المراجعة، تم الإرسال، أو يحتاج إجراء."
        ],
        [
          "دعم عربي كامل",
          "لغة بسيطة وخطوات مفهومة تناسب الطالب وولي الأمر وفريق القبول."
        ]
      ],
      "steps": [
        "بيانات الطالب",
        "نوع الشهادة والدرجات",
        "الجامعات والبرامج المؤهلة",
        "اختيار 10 رغبات",
        "رفع المستندات",
        "مراجعة الطلب والدفع",
        "متابعة حالة التقديم"
      ],
      "services_title": "خدمات عملية تحل مشاكل الطالب",
      "benefits_title": "رحلة أوضح للطالب وفريق القبول",
      "steps_title": "خطوات تقديم مفهومة",
      "faq_title": "أسئلة قبل أن تبدأ",
      "articles_title": "دليل الطالب"
    }
  },
  {
    "slug": "service-1",
    "name": "تقييم فرصة القبول",
    "kind": "service",
    "content": {
      "name": "تقييم فرصة القبول",
      "price": "ابدأ الآن",
      "description": "مناسبة للطالب الذي يريد معرفة هل يمكنه التقديم وما البرامج الأقرب له.",
      "features": [
        "فحص الشهادة",
        "تقدير المجموع المكافئ",
        "تنبيه للمخاطر قبل التقديم"
      ]
    },
    "sort_order": 0
  },
  {
    "slug": "service-2",
    "name": "تجهيز ملف الطالب",
    "kind": "service",
    "content": {
      "name": "تجهيز ملف الطالب",
      "price": "ملف خال من النواقص",
      "description": "تنظيم بيانات الطالب والمستندات والصور والرغبات قبل إرسال الطلب الرسمي.",
      "features": [
        "مراجعة 10 رغبات",
        "فحص جودة المرفقات",
        "قائمة تصديقات وملاحظات"
      ]
    },
    "sort_order": 1
  },
  {
    "slug": "service-3",
    "name": "متابعة القبول",
    "kind": "service",
    "content": {
      "name": "متابعة القبول",
      "price": "تنبيهات مستمرة",
      "description": "متابعة الإشعارات والرد على النواقص وتحديث الطالب بحالة ملفه حتى ظهور النتيجة.",
      "features": [
        "متابعة حالة الطلب",
        "ردود على الاستفسارات",
        "تحديثات للطالب وولي الأمر"
      ]
    },
    "sort_order": 2
  },
  {
    "slug": "faq-1",
    "name": "هل الحد الأدنى يعني أنني مقبول؟",
    "kind": "faq",
    "content": {
      "answer": "لا. الحد الأدنى يسمح بالتقديم فقط، أما القبول فيتأثر بالمقاعد والمنافسة وصحة البيانات والمستندات."
    },
    "sort_order": 0
  },
  {
    "slug": "faq-2",
    "name": "ماذا لو عندي شهادة IGCSE؟",
    "kind": "faq",
    "content": {
      "answer": "تتم مراجعة عدد المواد والتقديرات ومواد A-Level أو AS-Level والتأكد من المواد المؤهلة لكل كلية."
    },
    "sort_order": 1
  },
  {
    "slug": "faq-3",
    "name": "هل تختلف الشهادات العربية من دولة لأخرى؟",
    "kind": "faq",
    "content": {
      "answer": "نعم، طريقة حساب السعودية والكويت والإمارات وباقي الدول ليست واحدة، لذلك تعرض المنصة مسارًا مناسبًا لكل شهادة."
    },
    "sort_order": 2
  },
  {
    "slug": "faq-4",
    "name": "هل تساعدون في ترتيب الرغبات؟",
    "kind": "faq",
    "content": {
      "answer": "نعم، يتم اقتراح رغبات تجمع بين طموح الطالب وفرص القبول الواقعية والجامعات المناسبة."
    },
    "sort_order": 3
  },
  {
    "slug": "igcse-admission-egypt",
    "name": "دليل قبول طلاب IGCSE في الجامعات المصرية",
    "kind": "article",
    "content": {
      "slug": "igcse-admission-egypt",
      "category": "دليل الشهادات",
      "title": "دليل قبول طلاب IGCSE في الجامعات المصرية",
      "excerpt": "شرح مبسط لفكرة المواد المؤهلة، حساب المجموع المكافئ، وترتيب الرغبات قبل التقديم.",
      "date": "16 سبتمبر 2026",
      "readTime": "7 دقائق",
      "image": "https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=85",
      "content": "شرح مبسط لفكرة المواد المؤهلة، حساب المجموع المكافئ، وترتيب الرغبات قبل التقديم."
    },
    "sort_order": 0
  },
  {
    "slug": "study-in-egypt-for-international-students",
    "name": "ما الذي يحتاجه الطالب الوافد قبل بدء طلب التقديم؟",
    "kind": "article",
    "content": {
      "slug": "study-in-egypt-for-international-students",
      "category": "الدراسة في مصر",
      "title": "ما الذي يحتاجه الطالب الوافد قبل بدء طلب التقديم؟",
      "excerpt": "قائمة عملية بالبيانات والمستندات والقرارات التي يجب تجهيزها قبل فتح طلب جامعي جديد.",
      "date": "14 سبتمبر 2026",
      "readTime": "5 دقائق",
      "image": "https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1200&q=85",
      "content": "قائمة عملية بالبيانات والمستندات والقرارات التي يجب تجهيزها قبل فتح طلب جامعي جديد."
    },
    "sort_order": 1
  },
  {
    "slug": "arab-certificates-equivalency",
    "name": "اختلاف معادلة الشهادات العربية حسب الدولة",
    "kind": "article",
    "content": {
      "slug": "arab-certificates-equivalency",
      "category": "المعادلات",
      "title": "اختلاف معادلة الشهادات العربية حسب الدولة",
      "excerpt": "لماذا تختلف قواعد السعودية والكويت والإمارات والسودان، وكيف تظهر النتيجة للطالب بوضوح.",
      "date": "10 سبتمبر 2026",
      "readTime": "6 دقائق",
      "image": "https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=85",
      "content": "لماذا تختلف قواعد السعودية والكويت والإمارات والسودان، وكيف تظهر النتيجة للطالب بوضوح."
    },
    "sort_order": 2
  },
  {
    "slug": "application-documents-checklist",
    "name": "قائمة المستندات المطلوبة للتقديم الجامعي",
    "kind": "article",
    "content": {
      "slug": "application-documents-checklist",
      "category": "المستندات",
      "title": "قائمة المستندات المطلوبة للتقديم الجامعي",
      "excerpt": "جواز السفر، شهادة الميلاد، الشهادة الدراسية، الصورة الشخصية، وما الذي يحدث عند نقص مستند.",
      "date": "8 سبتمبر 2026",
      "readTime": "4 دقائق",
      "image": "https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1200&q=85",
      "content": "جواز السفر، شهادة الميلاد، الشهادة الدراسية، الصورة الشخصية، وما الذي يحدث عند نقص مستند."
    },
    "sort_order": 3
  },
  {
    "slug": "universities",
    "name": "دليل الجامعات",
    "kind": "page",
    "content": {
      "title": "دليل الجامعات",
      "description": "استكشف الجامعات والبرامج المتاحة."
    }
  },
  {
    "slug": "programs",
    "name": "البرامج والتخصصات",
    "kind": "page",
    "content": {
      "title": "البرامج والتخصصات",
      "description": "قارن البرامج وشروط القبول المعتمدة."
    }
  },
  {
    "slug": "calculators",
    "name": "حاسبة الأهلية",
    "kind": "page",
    "content": {
      "title": "حاسبة الأهلية",
      "description": "أدخل درجاتك حسب القاعدة المنشورة. النتيجة استرشادية ولا تضمن القبول."
    }
  },
  {
    "slug": "apply",
    "name": "ابدأ طلبك",
    "kind": "page",
    "content": {
      "title": "ابدأ طلبك",
      "description": "اختر الخدمة وأدخل بياناتك ورغباتك."
    }
  },
  {
    "slug": "articles",
    "name": "دليل الطالب",
    "kind": "page",
    "content": {
      "title": "دليل الطالب",
      "description": "مقالات وإرشادات التقديم."
    }
  },
  {
    "slug": "dashboard",
    "name": "بوابة الطالب",
    "kind": "page",
    "content": {
      "title": "بوابة الطالب",
      "description": "تابع حالة الطلب والمستندات وملاحظات المراجعة."
    }
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);
        foreach ($entries as $entry) {
            SiteContent::firstOrCreate(['slug' => $entry['slug']], $entry);
        }
    }
}

