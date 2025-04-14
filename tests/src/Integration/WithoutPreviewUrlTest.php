<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Livewire\Livewire;
use Pboivin\FilamentPeek\CachedPreview;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;
use Pboivin\FilamentPeek\Support;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages\EditPost;
use Pboivin\FilamentPeek\Tests\Models\Page;

use function Pest\Laravel\get;

it('cannot access preview url if disabled', function () {
    $this->login();

    CachedPreview::make(EditPost::class, 'preview-data', ['KEY' => 'VALUE'])
        ->put('test');

    get('/filament-peek/preview/?token=test')
        ->assertNotFound();
});

it('can use inline view for page preview', function () {
    $this->mock(Support\Cache::class)
        ->shouldReceive('createPreviewToken')
        ->andReturn('test');

    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(EditPage::class, ['record' => $page->id])
        ->assertSeeHtml('Test Page')
        ->callAction('preview')
        ->assertDispatched(
            'open-preview-modal',
            iframeUrl: null,
        );
});

it('can use inline view for builder preview', function () {
    $this->mock(Support\Cache::class)
        ->shouldReceive('createPreviewToken')
        ->andReturn('test');

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', EditPost::class)
        ->set('builderName', 'test')
        ->set('previewView', 'preview-data')
        ->call('refreshBuilderPreview')
        ->assertDispatched(
            'refresh-preview-modal',
            iframeUrl: null
        );
});
