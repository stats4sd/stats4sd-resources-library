<div>
    <div class="h-4 bg-brand-bg"></div>

    @include('livewire.partials.library-browser', [
        'heading' => t('Search collections'),
        'description' => t('A collection is a group of resources compiled together for a specific purpose, such as series of guides on a specific topic, or the reading for an online course.'),
        'searchLabel' => t('Search collections'),
        'itemsLabel' => t('collections'),
        'noMatchesMessage' => t('No collections match your search or filters.'),
        'emptyMessage' => t('No collections have been added yet.'),
    ])
</div>
