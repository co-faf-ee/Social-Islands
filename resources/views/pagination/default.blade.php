@if ($paginator->hasPages())
  <div class="ui pagination menu">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <div class="disabled item"><i class="angle double left icon"></i></div>
        @else
            <a class="item" onclick="a('{{ $paginator->previousPageUrl() }}')" rel="prev"><i class="angle double left icon"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <div class="disabled item">...</div>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <a class="active item">{{ $page }}</a>
                    @else
                        <a class="item" onclick="a('{{ $url }}')">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="item" onclick="a('{{ $paginator->nextPageUrl() }}')" rel="next"><i class="angle double right icon"></i></a>
        @else
            <a class="item"><i class="angle double right icon"></i></a>
        @endif
    </div>
@endif
