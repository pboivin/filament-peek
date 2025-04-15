<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\App\Models\Page;

it('can set initial preview modal data', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    ($livewire = Livewire::test(EditPage::class, ['record' => $page->id]))
        ->assertSeeHtml('Test Page');

    collect(
        $livewire
            ->instance()
            ->getForm('form')
            ->getComponents()[0]
            ->getChildComponents()[0]
            ->getActions()
    )
        ->first()
        ->call();

    $instance = invade($livewire->instance());

    expect($instance->previewModalData['initial_data'])->toEqual('InlinePreviewAction');
});
