@if ($paginator->hasPages())
    <div class="flex items-center justify-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 border border-border text-slate-300 cursor-not-allowed transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-border text-text-main hover:border-primary hover:text-primary transition-all shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="hidden sm:flex items-center gap-2">
            @foreach ($paginator->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 flex items-center justify-center text-text-muted font-bold">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-lg shadow-indigo-100 ring-2 ring-primary ring-offset-2">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-border text-text-main font-bold hover:border-primary hover:text-primary transition-all shadow-sm hover:shadow-md active:scale-95">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Mobile Indicator --}}
        <div class="sm:hidden flex items-center px-4 font-bold text-sm text-text-main">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-border text-text-main hover:border-primary hover:text-primary transition-all shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 border border-border text-slate-300 cursor-not-allowed transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </div>
@endif