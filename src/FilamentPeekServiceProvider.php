<?php

namespace Pboivin\FilamentPeek;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentPeekServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-peek';

    protected array $resources = [
        // CustomResource::class,
    ];

    protected array $pages = [
        // CustomPage::class,
    ];

    protected array $widgets = [
        // CustomWidget::class,
    ];

    protected array $styles = [
        'plugin-filament-peek' => __DIR__.'/../resources/dist/filament-peek.css',
    ];

    protected array $scripts = [
        'plugin-filament-peek' => __DIR__.'/../resources/dist/filament-peek.js',
    ];

    // protected array $beforeCoreScripts = [
    //     'plugin-filament-peek' => __DIR__ . '/../resources/dist/filament-peek.js',
    // ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
