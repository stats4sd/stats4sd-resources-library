{{-- Sidebar filters shared by the resources, collections and FRN hub pages. They mirror the Browse All page
     (resources/views/livewire/browse-all.blade.php), without the per-option counts. --}}
            <!-- Language Filter - hidden via the "Show language filter" site option or when only one locale is configured -->
            @if(config('branding.features.show_language_filter', true) && count(config('branding.locales', ['en' => 'English'])) > 1)
            <div class="" x-data="window.innerWidth >= 1024 ? { open: true } : { open: false }">
                <div class="border-t border-gray-400 sm:border-0 lg:border-t mb-6 sm:my-0 lg:mb-6"></div>
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <label class="text-base lg:font-bold">{{ t("Language:") }}</label>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300" :class="open ? 'rotate-90' : '-rotate-90'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
                <div class="space-y-2 mt-2 text-sm" x-show="open">
                    @foreach(collect(config('branding.locales', ['en' => 'English']))->sort() as $code => $label)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="selectedLanguages" value="{{ $code }}" class="mr-2 accent-brand-primary"/>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Resource Type Filter - hidden via the "Show resource type filter" site option or when no resource types are configured -->
            @if(config('branding.features.show_trove_type_filter', false) && $filterTroveTypes->isNotEmpty())
            <div class="sm:ml-6 lg:ml-0" x-data="window.innerWidth >= 1024 ? { open: true } : { open: false }">
                <div class="border-t border-gray-400 sm:border-0 lg:border-t my-6 sm:my-0 lg:my-6"></div>
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <label class="text-base lg:font-bold">{{ t('Type:') }}</label>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300" :class="open ? 'rotate-90' : '-rotate-90'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
                <div class="space-y-2 mt-4 text-sm" x-show="open">
                    @foreach($filterTroveTypes as $filterTroveType)
                        <label class="flex items-center rounded cursor-pointer">
                            <input type="checkbox" wire:model.live="selectedTroveTypes" value="{{ $filterTroveType->id }}" class="mr-2 accent-brand-primary"/>
                            {{ $filterTroveType->label }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tag Type Filters (configured via admin panel) -->
            @foreach($filterTagTypes as $filterTagType)
            <div class="sm:ml-6 lg:ml-0" x-data="window.innerWidth >= 1024 ? { open: true } : { open: false }">
                <div class="border-t border-gray-400 sm:border-0 lg:border-t my-6 sm:my-0 lg:my-6"></div>
                <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                    <label class="text-base lg:font-bold">{{ $filterTagType->label }}:</label>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300" :class="open ? 'rotate-90' : '-rotate-90'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
                <div class="space-y-2 mt-4 text-sm" x-show="open">
                    @foreach($filterTagType->tags as $tag)
                        <label class="flex items-center rounded cursor-pointer">
                            <input type="checkbox" wire:model.live="selectedTagsByType.{{ $filterTagType->id }}" value="{{ $tag->id }}" class="mr-2 accent-brand-primary"/>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endforeach
