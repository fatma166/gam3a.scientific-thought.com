@extends('admin.layout')
@section('title',$config['label'])
@section('content')
@if($resource === 'site-content')
    <div class="card" style="margin-bottom:14px">
        <strong>تحكم الصفحة الرئيسية</strong>
        <p style="color:#64748b">العناصر المعروضة هنا تتحكم في الصفحة الرئيسية في Next.js على <code>http://localhost:3000/</code>: الهيرو، الخدمات، المميزات، الخطوات، الأسئلة، المقالات، والتنقل.</p>
        <div class="actions">
            <a class="btn gray" href="/admin/resources/site-content?scope=home">سيكشنات الرئيسية</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=page">الصفحات</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=service">الخدمات</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=faq">الأسئلة</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=article">المقالات</a>
        </div>
    </div>
@endif
<p><a class="btn" href="/admin/resources/{{ $resource }}/create">إضافة جديد</a></p>
<table>
    <tr><th>#</th><th>الاسم</th>@if($resource === 'site-content')<th>النوع</th><th>Slug</th><th>الترتيب</th>@endif<th>الحالة</th><th></th></tr>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name ?? $item->slug ?? ('Item '.$item->id) }}</td>
            @if($resource === 'site-content')
                <td>{{ $item->kind }}</td>
                <td>{{ $item->slug }}</td>
                <td>{{ $item->sort_order }}</td>
            @endif
            <td>{{ isset($item->is_active) ? ($item->is_active ? 'مفعل' : 'متوقف') : '-' }}</td>
            <td class="actions">
                <a class="btn" href="/admin/resources/{{ $resource }}/{{ $item->id }}/edit">تعديل</a>
                <form method="post" action="/admin/resources/{{ $resource }}/{{ $item->id }}">@csrf @method('DELETE')<button class="btn red">حذف</button></form>
            </td>
        </tr>
    @endforeach
</table>
{{ $items->links() }}
@endsection
