<?php

namespace App\Livewire;

use App\Services\Search\LibraryHit;
use App\Services\Search\LibrarySearchRequest;

/**
 * The Browse All search and filters, narrowed to one kind of result: only resources
 * or only collections. The library search returns both kinds together, so this asks
 * for every match, keeps the wanted kind and pages through them here.
 */
abstract class BrowseSingleType extends BrowseAll
{
    private const PER_PAGE = 24;

    /** The most matches fetched from the library search before narrowing to one kind. */
    private const MAX_MATCHES = 1000;

    /** The LibraryHit type this page lists: 'trove' or 'collection'. */
    abstract protected function hitType(): string;

    abstract protected function viewName(): string;

    public function render()
    {
        $this->searchUnavailable = false;

        $hits = array_values(array_filter(
            $this->runSearch($this->buildSearchRequest())->hits,
            fn (LibraryHit $hit) => $hit->type === $this->hitType(),
        ));

        $totalHits = count($hits);
        $totalPages = (int) ceil($totalHits / self::PER_PAGE);

        if ($totalPages > 0 && $this->page > $totalPages) {
            $this->page = $totalPages;
        }

        $firstHitOfPage = (max(1, $this->page) - 1) * self::PER_PAGE;

        $items = $this->hydrateItems(array_slice($hits, $firstHitOfPage, self::PER_PAGE));

        $startOfPage = $totalHits === 0 ? 0 : $firstHitOfPage + 1;

        return view($this->viewName(), [
            'items' => $items,
            'filterTagTypes' => $this->filterTagTypes,
            'filterTroveTypes' => $this->filterTroveTypes,
            'totalHits' => $totalHits,
            'totalPages' => $totalPages,
            'startOfPage' => $startOfPage,
            'endOfPage' => $startOfPage === 0 ? 0 : $startOfPage + $items->count() - 1,
            'pageWindow' => $this->pageWindow($totalPages),
        ]);
    }

    protected function buildSearchRequest(): LibrarySearchRequest
    {
        $libraryRequest = parent::buildSearchRequest();

        return new LibrarySearchRequest(
            query: $libraryRequest->query,
            tagIdsByType: $libraryRequest->tagIdsByType,
            troveTypeIds: $libraryRequest->troveTypeIds,
            locales: $libraryRequest->locales,
            page: 1,
            perPage: self::MAX_MATCHES,
        );
    }
}
