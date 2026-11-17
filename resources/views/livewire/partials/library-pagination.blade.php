{{-- Page links shared by the resources and collections pages (same markup as the Browse All page). --}}
@if($totalPages > 1)
<div class="max-w-6xl mx-auto my-5">
    <nav class="rounded-md shadow-xs flex w-full justify-end" aria-label="{{ t('Pagination') }}">
        <button
            type="button"
            class="py-2 px-4 rounded-full {{ $page === 1 ? 'bg-gray-50 text-gray-400' : 'bg-white hover:text-brand-primary' }}"
            wire:click="goToPage(1)"
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="{{ t('First page') }}"
            @disabled($page === 1)
        >
            {{ t('First') }}
        </button>

        <button
            type="button"
            class="py-2 px-4 rounded-full {{ $page === 1 ? 'bg-gray-50 text-gray-400' : 'bg-white hover:text-brand-primary' }}"
            wire:click="goToPage({{ $page - 1 }})"
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="{{ t('Previous page') }}"
            @disabled($page === 1)
        >
            {{ t('Previous') }}
        </button>

        @if(($pageWindow[0] ?? 1) > 1)
            <span class="py-2 px-2" aria-hidden="true">&hellip;</span>
        @endif

        @foreach($pageWindow as $pageNumber)
            <button
                type="button"
                class="py-2 px-4 rounded-full {{ $page === $pageNumber ? 'text-white bg-brand-primary' : 'text-black hover:text-brand-primary' }}"
                wire:click="goToPage({{ $pageNumber }})"
                x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                aria-label="{{ t('Page') }} {{ $pageNumber }}"
                @if($page === $pageNumber) aria-current="page" @endif
            >{{ $pageNumber }}</button>
        @endforeach

        @if((end($pageWindow) ?: $totalPages) < $totalPages)
            <span class="py-2 px-2" aria-hidden="true">&hellip;</span>
        @endif

        <button
            type="button"
            class="py-2 px-4 rounded-full {{ $page === $totalPages ? 'bg-gray-50 text-gray-400' : 'bg-white hover:text-brand-primary' }}"
            wire:click="goToPage({{ $page + 1 }})"
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="{{ t('Next page') }}"
            @disabled($page === $totalPages)
        >
            {{ t('Next') }}
        </button>

        <button
            type="button"
            class="py-2 px-4 rounded-full {{ $page === $totalPages ? 'bg-gray-50 text-gray-400' : 'bg-white hover:text-brand-primary' }}"
            wire:click="goToPage({{ $totalPages }})"
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="{{ t('Last page') }}"
            @disabled($page === $totalPages)
        >
            {{ t('Last') }}
        </button>
    </nav>
</div>
@endif
