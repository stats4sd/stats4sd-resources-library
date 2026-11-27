<?php

use App\Contracts\SearchesLibrary;
use App\Livewire\Collections;
use App\Livewire\Resources;
use App\Models\Collection;
use App\Models\Tag;
use App\Models\TagType;
use App\Services\Search\DatabaseLibrarySearch;
use Livewire\Livewire;

beforeEach(function () {
    bootPublicSite();
    app()->bind(SearchesLibrary::class, DatabaseLibrarySearch::class);
});

it('serves the resources and collections pages over HTTP', function () {
    $this->get('/resources')->assertOk()->assertSee('Search resources');
    $this->get('/collections')->assertOk()->assertSee('Search collections');
});

it('lists only resources on the resources page', function () {
    $resource = publishedTrove();
    $collection = Collection::factory()->create();

    $component = Livewire::test(Resources::class)->assertViewHas('totalHits', 1);

    expect($component->viewData('items')->pluck('type', 'id')->all())->toBe([$resource->id => 'resource']);
});

it('lists only public collections on the collections page', function () {
    publishedTrove();
    $publicCollection = Collection::factory()->create();
    Collection::factory()->private()->create();

    $component = Livewire::test(Collections::class)->assertViewHas('totalHits', 1);

    expect($component->viewData('items')->pluck('type', 'id')->all())->toBe([$publicCollection->id => 'collection']);
});

it('narrows the resources page by a selected tag filter', function () {
    $tagType = TagType::factory()->shownInFilter()->create();
    $tag = Tag::factory()->ofType($tagType)->create();

    $taggedResource = publishedTrove();
    $taggedResource->tags()->attach($tag);
    publishedTrove();

    $component = Livewire::test(Resources::class)
        ->assertViewHas('totalHits', 2)
        ->assertSee($tag->name)
        ->set("selectedTagsByType.{$tagType->id}", [(string) $tag->id])
        ->assertViewHas('totalHits', 1);

    expect($component->viewData('items')->pluck('id')->all())->toBe([$taggedResource->id]);

    $component->call('clearFilters')->assertViewHas('totalHits', 2);
});

it('pages through the resources 24 at a time', function () {
    foreach (range(1, 30) as $ignored) {
        publishedTrove();
    }

    Livewire::test(Resources::class)
        ->assertViewHas('totalHits', 30)
        ->assertViewHas('totalPages', 2)
        ->assertViewHas('endOfPage', 24)
        ->call('goToPage', 2)
        ->assertViewHas('startOfPage', 25)
        ->assertViewHas('endOfPage', 30)
        ->assertViewHas('items', fn ($items) => $items->count() === 6);
});
