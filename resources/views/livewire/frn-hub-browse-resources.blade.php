<div class="flex flex-col lg:flex-row lg:gap-12 bg-gray-50">

    <!-- Sidebar (Search & Filters) -->
    <div class="lg:min-w-[280px] w-full lg:w-2/12 bg-[#f4f4f4] bg-white self-start lg:pl-8 px-8 py-6 lg:py-8 lg:m-6 lg:shadow-xl">
        <div class="pb-4 sm:pb-0 lg:pb-4 sm:hidden lg:block">
            <div class=" text-xl font-bold">{{ t('Search and filter') }}</div>
            <div class="h-3 w-12 bg-brand-primary my-3"></div>
        </div>  

        <!-- Search bar -->
        <div class="relative flex items-center mb-6">
            <input
                type="text"
                class="w-full py-2 pl-12 pr-4 bg-gray-200 border-none rounded-full focus:outline-none transition duration-300 focus:bg-gray-100 focus:ring-0 text-gray-700"
                placeholder="{{ t('Search here') }}"
                aria-label="{{ t('Search resources') }}"
                wire:model.live.debounce.400ms="query"
            >

            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>

            <!-- Clear Button -->
            @if($query)
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="clearSearch" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="gray" class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 cursor-pointer hover:stroke-gray-700 transition-colors duration-200">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            @endif
        </div>
        <div class="flex flex-col sm:flex-row lg:flex-col sm:mb-2 sm:mt-4 lg:my-0">
            <div class="pb-4 sm:pb-0 ml-2 mr-16 lg:pb-4 hidden sm:block lg:hidden">
                <div class="pb-4 sm:pb-0 lg:pb-4 text-xl font-bold">{{ t('Filters:') }}</div>
                <div class="divider hidden lg:block"></div>
            </div>  
            @include('livewire.partials.library-filters')
        </div>
    </div>

    <!-- Resources Cards -->
    <div class="flex-1">
        <div class="p-8">
            {{ t("Showing ") . $totalResources . t(" resources") }}
            @if($query || !empty($selectedLanguages) || !empty($selectedTroveTypes) || collect($selectedTagsByType)->flatten()->isNotEmpty())
                <button wire:click="clearFilters" class="text-gray-500 hover:text-gray-700 underline text-sm">
                    {{ t("Clear Filters") }}
                </button>
            @endif
        </div>

        <div id="Resources-content" class="p-8 rounded-lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach ($resources as $resource)
                    <div wire:key="frn-resource-{{ $resource['id'] }}">
                        <x-resource-result-card :item="$resource" :show-tags="false"/>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>