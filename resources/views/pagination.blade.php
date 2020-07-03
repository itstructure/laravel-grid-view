@php
/**
 * @var \Illuminate\Pagination\LengthAwarePaginator $paginator
 * @var array[] $elements
 */
@endphp

@if ($paginator->hasPages())
    <nav class="d-inline-flex d-xl-flex">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ \Itstructure\GridView\Helpers\UrlSliderHelper::previousPageUrl(request(), $paginator) }}">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach($elements as $key => $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ \Itstructure\GridView\Helpers\UrlSliderHelper::toPageUrl(request(), $paginator, $page) }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                {{-- "Three Dots" Separator --}}
                @elseif(is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ \Itstructure\GridView\Helpers\UrlSliderHelper::nextPageUrl(request(), $paginator) }}">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#">&raquo;</a>
                </li>
            @endif
        </ul>
    </nav>
    <div class="clearfix"></div>
@endif
