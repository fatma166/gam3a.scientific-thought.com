@if ($paginator->hasPages())
    <div style="display:flex;gap:8px;margin-top:16px">
        @if (!$paginator->onFirstPage())
            <a class="btn gray" href="{{ $paginator->previousPageUrl() }}">السابق</a>
        @endif
        @if ($paginator->hasMorePages())
            <a class="btn gray" href="{{ $paginator->nextPageUrl() }}">التالي</a>
        @endif
    </div>
@endif
