<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PageResource\Pages\ListPages;
use Pboivin\FilamentPeek\Tests\App\Models\Page;

// @todo: Move unit tests to feature tests

it('can open preview modal for a list item', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(ListPages::class)
        ->assertSeeHtml('Test Page')
        ->callTableAction('listPreview', $page->id)
        ->assertDispatched('open-preview-modal');
});

it('can set initial preview modal data', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    $livewire = Livewire::test(ListPages::class);
    $livewire->callTableAction('listPreview', $page->id);

    $instance = invade($livewire->instance());
    expect($instance->previewModalData['initial_data'])->toEqual('ListPreviewAction');
});
