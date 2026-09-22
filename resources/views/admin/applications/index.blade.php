@extends('admin.layout')
@section('title','إدارة الطلبات')
@section('content')
<table>
    <tr><th>#</th><th>الطالب</th><th>الجنسية</th><th>الشهادة</th><th>الحالة</th><th></th></tr>
    @foreach($applications as $application)
        <tr>
            <td>{{ $application->id }}</td>
            <td>{{ $application->full_name }}</td>
            <td>{{ $application->nationality }}</td>
            <td>{{ $application->certificateTrack?->name }}</td>
            <td>{{ $application->status }}</td>
            <td><a class="btn" href="/admin/applications/{{ $application->id }}">مراجعة</a></td>
        </tr>
    @endforeach
</table>
{{ $applications->links() }}
@endsection
