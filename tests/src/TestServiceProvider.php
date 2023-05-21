<?php

namespace Pboivin\FilamentPeek\Tests;

use Filament\PluginServiceProvider;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource;

class TestServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-peek-test';

    protected array $resources = [
        PostResource::class,
    ];
}
