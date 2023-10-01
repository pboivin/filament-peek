<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\CachedPreview;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages\EditPost;
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
