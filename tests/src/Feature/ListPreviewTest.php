<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\ListPages;
use Pboivin\FilamentPeek\Tests\Models\Page;

it('can open preview modal for a list item', function () {
    $page = Page::factory()
        ->create(['title' => 'Test Page']);

    Livewire::test(ListPages::class)
        ->assertSeeHtml('Test Page')
        ->callTableAction('listPreview', $page->id)
        ->assertDispatched('open-preview-modal');
});
