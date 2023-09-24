<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\ListPages;
use Pboivin\FilamentPeek\Tests\Models\Page;

it('can open preview modal for a list item', function () {
    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(ListPages::class)
        ->assertSeeHtml('Test Page')
        ->callTableAction('listPreview', $page->id)
        ->assertDispatched('open-preview-modal');
});

it('can set initial preview modal data', function () {
    $page = Page::factory()->create(['title' => 'Test Page']);

    $livewire = Livewire::test(ListPages::class);
    $livewire->callTableAction('listPreview', $page->id);

    $instance = invade($livewire->instance());
    expect($instance->previewModalData['initial_data'])->toEqual('ListPreviewAction');
});
