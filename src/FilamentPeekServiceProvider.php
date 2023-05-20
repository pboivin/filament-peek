<?php

namespace Pboivin\FilamentPeek;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentPeekServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-peek';

    protected array $styles = [
        'plugin-filament-peek' => __DIR__.'/../resources/dist/filament-peek.css',
    ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
