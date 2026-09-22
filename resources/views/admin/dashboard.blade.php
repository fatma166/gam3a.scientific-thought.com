@extends('admin.layout')
@section('title','لوحة التحكم')
@section('content')
<section class="grid">
    @foreach($stats as $label => $value)
        <div class="card stat"><span>{{ str_replace('_',' ', $label) }}</span><strong>{{ $value }}</strong></div>
    @endforeach
</section>
<section class="card" style="margin-top:18px">
    <h2>آخر الطلبات</h2>
    <table>
        <tr><th>الطالب</th><th>الحالة</th><th>السنة</th><th></th></tr>
        @forelse($latestApplications as $application)
            <tr>
                <td>{{ $application->full_name }}</td>
                <td>{{ $application->status }}</td>
                <td>{{ $application->academic_year }}</td>
                <td><a class="btn" href="/admin/applications/{{ $application->id }}">عرض</a></td>
            </tr>
        @empty
            <tr><td colspan="4">لا توجد بيانات بعد.</td></tr>
        @endforelse
    </table>
</section>
@endsection
