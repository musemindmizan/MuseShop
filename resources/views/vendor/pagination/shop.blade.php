@if ($paginator->hasPages())
    <ul class="flex space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li>
                <span class="w-10 h-10 flex items-center justify-center border rounded text-gray-300 cursor-not-allowed">
                    <i class="fa fa-angle-left"></i>
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="w-10 h-10 flex items-center justify-center border rounded hover:bg-primary hover:text-white transition">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li>
                    <span class="w-10 h-10 flex items-center justify-center border rounded text-gray-400">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li>
                        @if ($page == $paginator->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center border rounded bg-primary text-white shadow">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="w-10 h-10 flex items-center justify-center border rounded hover:bg-primary hover:text-white transition">{{ $page }}</a>
                        @endif
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="w-10 h-10 flex items-center justify-center border rounded hover:bg-primary hover:text-white transition">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        @else
            <li>
                <span class="w-10 h-10 flex items-center justify-center border rounded text-gray-300 cursor-not-allowed">
                    <i class="fa fa-angle-right"></i>
                </span>
            </li>
        @endif
    </ul>
@endif
