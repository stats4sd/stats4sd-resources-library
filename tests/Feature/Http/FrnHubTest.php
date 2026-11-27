<?php

use App\Livewire\FrnHubBrowseResources;
use App\Models\Tag;
use App\Models\TagType;
use App\Services\Search\DatabaseLibrarySearch;
use App\Contracts\SearchesLibrary;
use Livewire\Livewire;

beforeEach(function () {
    bootPublicSite();
    app()->bind(SearchesLibrary::class, DatabaseLibrarySearch::class);
});

it('serves the FRN hub page over HTTP', function () {
    $this->get('/frn')
        ->assertOk()
        ->assertSee('FRN Research Methods Hub');
});

it('links to the FRN hub from the theme pages', function () {
    $this->get('/theme-pages')
        ->assertOk()
        ->assertSee('href="frn"', escape: false);
});

it('lists only published resources carrying the FRN tag', function () {
    $frnTag = Tag::factory()->create();

    $frnResource = publishedTrove();
    $frnResource->tags()->attach($frnTag);

    $otherResource = publishedTrove();

    $draftResource = draftTrove();
    $draftResource->tags()->attach($frnTag);

    Livewire::test(FrnHubBrowseResources::class, ['tagId' => $frnTag->id])
        ->assertViewHas('totalResources', 1)
        ->assertSee($frnResource->title)
        ->assertDontSee($otherResource->title)
        ->assertDontSee($draftResource->title);
});

it('shows the same tag filters as the browse library page', function () {
    $shownType = TagType::factory()->shownInFilter()->create();
    $shownTag = Tag::factory()->ofType($shownType)->create();
    $hiddenType = TagType::factory()->create(['show_in_filter' => false]);
    $hiddenTag = Tag::factory()->ofType($hiddenType)->create();

    Livewire::test(FrnHubBrowseResources::class)
        ->assertSee($shownTag->name)
        ->assertDontSee($hiddenTag->name);
});

it('narrows the FRN resources by a selected tag filter', function () {
    $frnTag = Tag::factory()->create();
    $topicType = TagType::factory()->shownInFilter()->create();
    $soilTopic = Tag::factory()->ofType($topicType)->create();

    $soilResource = publishedTrove();
    $soilResource->tags()->attach([$frnTag->id, $soilTopic->id]);

    $untaggedFrnResource = publishedTrove();
    $untaggedFrnResource->tags()->attach($frnTag);

    $soilResourceOutsideFrn = publishedTrove();
    $soilResourceOutsideFrn->tags()->attach($soilTopic);

    Livewire::test(FrnHubBrowseResources::class, ['tagId' => $frnTag->id])
        ->assertViewHas('totalResources', 2)
        ->set("selectedTagsByType.{$topicType->id}", [(string) $soilTopic->id])
        ->assertViewHas('totalResources', 1)
        ->assertSee($soilResource->title)
        ->assertDontSee($soilResourceOutsideFrn->title)
        ->call('clearFilters')
        ->assertViewHas('totalResources', 2);
});

it('hides the featured resources section when none of the featured resources exist', function () {
    $this->get('/frn')
        ->assertOk()
        ->assertDontSee('Featured resources');
});

it('shows the featured resources carousel when a featured resource is published', function () {
    $featuredResource = publishedTrove(['id' => 604]);

    $this->get('/frn')
        ->assertOk()
        ->assertSee('Featured resources')
        ->assertSee('id="slide1"', escape: false)
        ->assertSee($featuredResource->title);
});
