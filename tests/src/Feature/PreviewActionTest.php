<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\Models\Page;

// @todo: Move unit tests to feature tests

it('can set initial preview modal data', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    ($livewire = Livewire::test(EditPage::class, ['record' => $page->id]))
        ->assertSeeHtml('Test Page')
        ->callAction('preview')
        ->assertDispatched('open-preview-modal');

    $instance = invade($livewire->instance());

    expect($instance->previewModalData['initial_data'])->toEqual('PreviewAction');
});
