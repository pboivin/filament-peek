<?php

namespace Pboivin\FilamentPeek;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class FilamentPeekPlugin implements Plugin
{
    const PACKAGE = 'pboivin/filament-peek';

    const NAME = 'filament-peek';

    const VERSION = '2.0-dev';

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return static::NAME;
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            'panels::body.end',
            fn () => view('filament-peek::preview-modal'),
        );

        if (!config('filament-peek.disablePluginScripts', false)) {
            FilamentAsset::register([
                Js::make(static::NAME, __DIR__ . '/../resources/dist/filament-peek.js'),
            ], package: static::PACKAGE);
        }

        if (!config('filament-peek.disablePluginStyles', false)) {
            FilamentAsset::register([
                Css::make(static::NAME, __DIR__ . '/../resources/dist/filament-peek.css'),
            ], package: static::PACKAGE);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
