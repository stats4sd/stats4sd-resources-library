<div>
    <div class="h-4 bg-brand-bg"></div>

    @include('livewire.partials.library-browser', [
        'heading' => t('Search resources'),
        'description' => t('Guides, tools, videos, papers and other items that we use and recommend to others.'),
        'searchLabel' => t('Search resources'),
        'itemsLabel' => t('resources'),
        'noMatchesMessage' => t('No resources match your search or filters.'),
        'emptyMessage' => t('No resources have been added yet.'),
    ])
</div>
