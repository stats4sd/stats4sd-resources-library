<?php

namespace App\Livewire;

/** The "Search resources" page: the library search and filters, listing resources only. */
class Resources extends BrowseSingleType
{
    protected function hitType(): string
    {
        return 'trove';
    }

    protected function viewName(): string
    {
        return 'livewire.resources';
    }
}
