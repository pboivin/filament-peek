<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\CachedPreview;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PostResource\Pages\EditPost;

use function Pest\Laravel\get;

it('can override the serializable classes config if needed', function () {
    config()->set('cache.serializable_classes', false);

    $this->login();

    CachedPreview::make(EditPost::class, 'preview-data', ['KEY' => 'VALUE'])
        ->put('test');

    get('/filament-peek/preview/?token=test')
        ->assertSuccessful()
        ->assertSee('KEY:VALUE');

    $this->assertTrue(config('cache.serializable_classes'));
});
