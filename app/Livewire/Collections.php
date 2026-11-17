<?php

namespace App\Livewire;

/** The "Search collections" page: the library search and filters, listing collections only. */
class Collections extends BrowseSingleType
{
    protected function hitType(): string
    {
        return 'collection';
    }

    protected function viewName(): string
    {
        return 'livewire.collections';
    }
}
