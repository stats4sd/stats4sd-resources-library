{{-- The hero, search, filters, result cards and page links shared by the resources and collections pages.
     Same layout as the Browse All page (resources/views/livewire/browse-all.blade.php).

     Expects: $heading, $description, $searchLabel, $itemsLabel, $noMatchesMessage, $emptyMessage. --}}
<div class="relative">
    <!-- Background Image-->
    @if(file_exists(public_path('images/banner.png')))
        <img src="{{ asset('images/banner.png') }}" alt="Background Image" class="absolute inset-0 w-full h-[400px] sm:h-[35vh] object-cover filter brightness-50 z-0">
    @endif

    <!-- Overlay Content -->
    <div class="relative z-10 flex flex-col items-center justify-center h-[400px] sm:h-[35vh] px-8 sm:px-20 xl:px-4 text-white">
        <div class="max-w-3xl w-full mx-auto text-center">
            <!-- Heading -->
            <div class="font-bold text-left md:text-center text-4xl sm:text-5xl md:text-5xl">
                {{ $heading }}
            </div>

            <!-- Description -->
            <div class="mt-6 text-left md:text-center pr-2 mx-auto">
                <p class="mb-4 text-xl">{{ $description }}</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row lg:gap-12">

        <!-- Sidebar (Search & Filters) -->
        <div class="lg:min-w-[280px] w-full lg:w-2/12 bg-[#f4f4f4] lg:bg-brand-bg self-start lg:pl-12 px-8 py-6 lg:py-8">
            <div class="pb-4 sm:pb-0 lg:pb-4 sm:hidden lg:block">
                <div class="pb-4 sm:pb-0 lg:pb-4 text-xl font-bold">{{ t('Search and filter') }}</div>
                <div class="divider hidden lg:block"></div>
            </div>

            <!-- Search input -->
            <div class="relative flex items-center mb-6">
                <input
                    type="text"
                    class="w-full py-2 pl-12 pr-4 bg-gray-200 border-none rounded-full focus:outline-none transition duration-300 focus:bg-gray-100 focus:ring-0 text-gray-700"
                    placeholder="{{ t('Search here') }}"
                    aria-label="{{ $searchLabel }}"
                    wire:model.live.debounce.400ms="query"
                >

                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>

                <!-- Clear Button -->
                @if($query)
                    <button
                        type="button"
                        wire:click="clearSearch"
                        aria-label="{{ t('Clear search') }}"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="gray" class="w-5 h-5 cursor-pointer hover:stroke-gray-700 transition-colors duration-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col sm:mb-2 sm:mt-4 lg:my-0">
                <div class="pb-4 sm:pb-0 ml-2 mr-16 lg:pb-4 hidden sm:block lg:hidden">
                    <div class="pb-4 sm:pb-0 lg:pb-4 text-xl font-bold">{{ t('Filters:') }}</div>
                </div>

                @include('livewire.partials.library-filters')
            </div>
        </div>

        <!-- Result Cards -->
        <div class="flex-1">
            <div class="p-8">
                @php
                    $hasActiveFilters = $query || !empty($selectedLanguages) || !empty($selectedTroveTypes) || collect($selectedTagsByType)->flatten()->isNotEmpty();
                @endphp
                @if($searchUnavailable)
                    {{ t("Text-based search is temporarily unavailable. You can still browse below, and use the filters to narrow your search.") }}
                @endif
                @if($totalHits === 0)
                    {{ $hasActiveFilters ? $noMatchesMessage : $emptyMessage }}
                @else
                    {{ t("Showing ") . $startOfPage . ' - ' . $endOfPage . ' ' . t("out of") . ' ' . $totalHits . ' ' . $itemsLabel }}
                @endif
                @if($hasActiveFilters)
                    <button type="button" wire:click="clearFilters" class="text-gray-500 hover:text-gray-700 underline text-sm">
                        {{ t("Clear Filters") }}
                    </button>
                @endif
            </div>

            <div class="p-8 rounded-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach ($items as $item)
                        {{-- display:contents keeps the cards as effective grid children --}}
                        <div wire:key="{{ $item['type'] }}-{{ $item['id'] }}" class="contents">
                            @if($item['type'] === 'resource')
                                <x-resource-result-card :item="$item" :show-tags="false"/>
                            @elseif($item['type'] === 'collection')
                                <x-collection-result-card :item="$item"/>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @include('livewire.partials.library-pagination')
        </div>
    </div>
</div>
