<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\Models\Page;

it('can set initial preview modal data', function () {
    $page = Page::factory()->create(['title' => 'Test Page']);

    ($livewire = Livewire::test(TestEditPage::class, ['record' => $page->id]))
        ->assertSeeHtml('Test Page')
        ->callAction('preview')
        ->assertDispatched('open-preview-modal');

    $instance = invade($livewire->instance());

    expect($instance->previewModalData['initial_data'])->toEqual('test');
});

class TestEditPage extends EditPage
{
    protected function getActions(): array
    {
        return [
            PreviewAction::make()
                ->previewModalData(fn () => ['initial_data' => 'test']),
        ];
    }
}
