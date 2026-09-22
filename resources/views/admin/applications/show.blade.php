@extends('admin.layout')
@section('title','مراجعة طلب')
@section('content')
<div class="card">
    <h2>{{ $application->full_name }}</h2>
    <p>السنة: {{ $application->academic_year }} | الجنسية: {{ $application->nationality }} | الجواز: {{ $application->passport_number }}</p>
    <p>الخدمة: {{ \App\Models\SiteContent::find($application->meta['service_id'] ?? null)?->name }}</p>
    <p>{{ $application->meta['question'] ?? '' }}</p>
    <p>الحالة الحالية: <strong>{{ $application->status }}</strong></p>
    <form method="post" action="/admin/applications/{{ $application->id }}/status">
        @csrf @method('PATCH')
        <label>تغيير الحالة</label>
        <select name="status">
            @foreach(['draft','submitted','under_review','missing_documents','approved','rejected'] as $status)
                <option value="{{ $status }}" @selected($application->status === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <label>ملاحظة</label>
        <textarea name="note"></textarea>
        <button style="margin-top:12px">حفظ الحالة</button>
    </form>
</div>
<div class="card" style="margin-top:18px">
    <h2>الرغبات</h2>
    <table><tr><th>الترتيب</th><th>البرنامج</th><th>الجامعة</th></tr>
    @foreach($application->choices as $choice)
        <tr><td>{{ $choice->rank }}</td><td>{{ $choice->program?->name }}</td><td>{{ $choice->program?->faculty?->university?->name }}</td></tr>
    @endforeach
    </table>
</div>
<div class="card" style="margin-top:18px">
    <h2>المستندات</h2>
    <table><tr><th>النوع</th><th>الملف</th><th>الحالة</th></tr>
    @foreach($application->documents as $document)
        <tr><td>{{ $document->type }}</td><td><a class="btn" href="/admin/applications/{{ $application->id }}/documents/{{ $document->id }}">{{ $document->original_name }}</a></td><td>
            <form method="post" action="/admin/applications/{{ $application->id }}/documents/{{ $document->id }}">
                @csrf @method('PATCH')
                <select name="status">@foreach(['pending_review','approved','rejected','missing'] as $status)<option value="{{ $status }}" @selected($document->status === $status)>{{ $status }}</option>@endforeach</select>
                <textarea name="review_notes" placeholder="ملاحظات للطالب">{{ $document->review_notes }}</textarea><button>حفظ المراجعة</button>
            </form>
        </td></tr>
    @endforeach
    </table>
</div>
<div class="card"><h2>سجل المتابعة</h2>@foreach($application->meta['admin_notes'] ?? [] as $note)<p>{{ $note['at'] }} | {{ $note['status'] }} | {{ $note['note'] }}</p>@endforeach</div>
@endsection
