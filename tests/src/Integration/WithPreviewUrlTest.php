<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Livewire\Livewire;
use Pboivin\FilamentPeek\CachedPreview;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;
use Pboivin\FilamentPeek\Support;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages\EditPost;
use Pboivin\FilamentPeek\Tests\Models\Page;
use Pboivin\FilamentPeek\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('can access preview url if enabled', function () {
    actingAs(User::factory()->create());

    CachedPreview::make(EditPost::class, 'preview-data', ['KEY' => 'VALUE'])
        ->put('test');

    get('/filament-peek/preview/?token=test')
        ->assertSuccessful()
        ->assertSee('KEY:VALUE');
});

it('can use internal preview url for page preview', function () {
    $this->mock(Support\Cache::class)
        ->shouldReceive('createPreviewToken')
        ->andReturn('test');

    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(EditPage::class, ['record' => $page->id])
        ->assertSeeHtml('Test Page')
        ->callAction('preview')
        ->assertDispatched(
            'open-preview-modal',
            iframeUrl: 'http://peek.test/filament-peek/preview?token=test',
            iframeContent: null,
        );
});

it('can use internal preview url for builder preview', function () {
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
            iframeUrl: 'http://peek.test/filament-peek/preview?token=test&refresh=1',
            iframeContent: null
        );
});
