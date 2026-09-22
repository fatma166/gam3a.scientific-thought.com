@extends('admin.layout')
@section('title',$config['label'])
@section('content')
<p><a class="btn" href="/admin/resources/{{ $resource }}/create">إضافة جديد</a></p>
<table>
    <tr><th>#</th><th>الاسم</th><th>الحالة</th><th></th></tr>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name ?? $item->slug ?? ('Item '.$item->id) }}</td>
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
