{{-- 
    Reusable Pagination Partial
    Usage: @include('components.pagination', ['paginator' => $yourData, 'colspan' => 7])
--}}
@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
@endphp
<div class="table-pagination">
    <span class="page-info">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
    </span>

    <div class="pagination">
        {{-- PREV --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled"><i class="fa fa-chevron-left"></i></span>
        @else
            <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="page-btn"><i class="fa fa-chevron-left"></i></a>
        @endif

        {{-- PAGE 1 --}}
        @if ($current == 1)
            <span class="page-btn active">1</span>
        @else
            <a href="{{ $paginator->appends(request()->query())->url(1) }}" class="page-btn">1</a>
        @endif

        {{-- DOTS sebelum current (jika current > 3) --}}
        @if ($current > 3)
            <span class="page-dots">…</span>
        @endif

        {{-- PAGE sebelum current --}}
        @if ($current > 2)
            <a href="{{ $paginator->appends(request()->query())->url($current - 1) }}" class="page-btn">{{ $current - 1 }}</a>
        @endif

        {{-- CURRENT PAGE (jika bukan page 1 dan bukan last) --}}
        @if ($current != 1 && $current != $last)
            <span class="page-btn active">{{ $current }}</span>
        @endif

        {{-- PAGE setelah current --}}
        @if ($current < $last - 1)
            <a href="{{ $paginator->appends(request()->query())->url($current + 1) }}" class="page-btn">{{ $current + 1 }}</a>
        @endif

        {{-- DOTS setelah current (jika current < last - 2) --}}
        @if ($current < $last - 2)
            <span class="page-dots">…</span>
        @endif

        {{-- LAST PAGE --}}
        @if ($last > 1)
            @if ($current == $last)
                <span class="page-btn active">{{ $last }}</span>
            @else
                <a href="{{ $paginator->appends(request()->query())->url($last) }}" class="page-btn">{{ $last }}</a>
            @endif
        @endif

        {{-- NEXT --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" class="page-btn"><i class="fa fa-chevron-right"></i></a>
        @else
            <span class="page-btn disabled"><i class="fa fa-chevron-right"></i></span>
        @endif
    </div>
</div>
@endif
