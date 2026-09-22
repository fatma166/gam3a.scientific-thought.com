@extends('admin.layout')
@section('title', ($item ?? null) ? 'تعديل '.$config['label'] : 'إضافة '.$config['label'])
@section('content')
@php
    $item = $item ?? null;
    $model = new $config['model'];
    $jsonFields = array_values(array_intersect($model->getFillable(), ['content','requirements','required_subjects','formula','inputs_schema','result_schema','required_documents']));
    $fields = array_values(array_diff($model->getFillable(), $jsonFields, ['is_active']));
    $defaults = ['sort_order' => 0, 'degree' => 'bachelor', 'language' => 'Arabic', 'tuition_currency' => 'EGP', 'rule_type' => 'equivalency'];
    $relations = ['university_id' => \App\Models\University::class, 'faculty_id' => \App\Models\Faculty::class, 'program_id' => \App\Models\Program::class, 'certificate_track_id' => \App\Models\CertificateTrack::class];
    $labels = ['name'=>'الاسم','slug'=>'الرابط المختصر','kind'=>'نوع المحتوى','sort_order'=>'ترتيب العرض','university_id'=>'الجامعة','faculty_id'=>'الكلية','program_id'=>'البرنامج','certificate_track_id'=>'الشهادة','minimum_score'=>'الحد الأدنى للمجموع','notes'=>'ملاحظات','description'=>'الوصف','city'=>'المدينة','country'=>'الدولة','image_url'=>'رابط الصورة'];
@endphp
<form class="card" method="post" action="{{ $item ? "/admin/resources/{$resource}/{$item->id}" : "/admin/resources/{$resource}" }}">
    @csrf
    @if($item) @method('PATCH') @endif
    <div class="form-grid">
        @foreach($fields as $field)
            <div>
                <label>{{ $labels[$field] ?? $field }}</label>
                @if(isset($relations[$field]))
                    <select name="{{ $field }}"><option value="">اختر</option>@foreach($relations[$field]::orderBy('name')->get() as $related)<option value="{{ $related->id }}" @selected(old($field, $item?->{$field}) == $related->id)>{{ $related->name }} (#{{ $related->id }})</option>@endforeach</select>
                @elseif($field === 'kind')
                    <select name="kind">@foreach(['page'=>'صفحة','service'=>'خدمة','faq'=>'سؤال شائع','article'=>'مقال','settings'=>'إعدادات الموقع'] as $value=>$label)<option value="{{ $value }}" @selected(old('kind', $item?->kind) === $value)>{{ $label }}</option>@endforeach</select>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $item?->{$field} ?? $defaults[$field] ?? '') }}">
                @endif
            </div>
        @endforeach
        @foreach($jsonFields as $field)
            <div class="full">
                <label>{{ $field }} JSON</label>
                <textarea name="{{ $field }}" rows="4">{{ old($field, $item?->{$field} ? json_encode($item->{$field}, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
            </div>
        @endforeach
        <div><input type="hidden" name="is_active" value="0"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item?->is_active ?? true))> مفعل</label></div>
    </div>
    <button style="margin-top:14px">حفظ</button>
    @if($resource === 'site-content') @include('admin.resources.content-editor') @endif
</form>
@endsection
