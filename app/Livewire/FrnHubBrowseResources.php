<?php

namespace App\Livewire;

use App\Services\Search\LibraryHit;
use App\Services\Search\LibrarySearchRequest;
use Livewire\Attributes\Locked;

/**
 * The FRN hub's resource browser: the Browse Library search and filters, restricted
 * to resources carrying the FRN tag and shown on a single page.
 */
class FrnHubBrowseResources extends BrowseAll
{
    /**
     * Key for the FRN tag's own filter group. Real groups are keyed by tag type id,
     * which starts at 1, so this never collides with a visitor's selection.
     */
    private const FRN_TAG_FILTER_KEY = 0;

    #[Locked]
    public int $tagId = 406;

    /** The hub shows every matching resource on one page. */
    private int $maxResources = 1000;

    public function mount(int $tagId = 406): void
    {
        $this->tagId = $tagId;

        parent::mount();
    }

    public function render()
    {
        $this->searchUnavailable = false;

        $hits = $this->runSearch($this->buildSearchRequest())->hits;

        // The hub lists resources only, so collection hits are dropped.
        $resources = $this->hydrateItems(array_values(array_filter(
            $hits,
            fn (LibraryHit $hit) => $hit->type === 'trove',
        )));

        return view('livewire.frn-hub-browse-resources', [
            'resources' => $resources,
            'totalResources' => $resources->count(),
            'filterTagTypes' => $this->filterTagTypes,
            'filterTroveTypes' => $this->filterTroveTypes,
        ]);
    }

    protected function buildSearchRequest(): LibrarySearchRequest
    {
        $libraryRequest = parent::buildSearchRequest();

        return new LibrarySearchRequest(
            query: $libraryRequest->query,
            tagIdsByType: [self::FRN_TAG_FILTER_KEY => [$this->tagId]] + $libraryRequest->tagIdsByType,
            troveTypeIds: $libraryRequest->troveTypeIds,
            locales: $libraryRequest->locales,
            page: 1,
            perPage: $this->maxResources,
        );
    }
}
