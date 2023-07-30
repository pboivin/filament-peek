<?php

namespace Pboivin\FilamentPeek;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;

class FilamentPeekPlugin implements Plugin
{
    const PACKAGE = 'pboivin/filament-peek';

    const ID = 'filament-peek';

    const VERSION = '2.0-dev';

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return static::ID;
    }

    public function register(Panel $panel): void
    {
        Livewire::component(
            'filament-peek::builder-editor',
            config('filament-peek.builderEditor.livewireComponentClass', BuilderEditor::class)
        );

        $panel->renderHook(
            'panels::body.end',
            fn () => view('filament-peek::preview-modal'),
        );

        if (! config('filament-peek.disablePluginScripts', false)) {
            FilamentAsset::register([
                Js::make(static::ID, __DIR__.'/../resources/dist/filament-peek.js'),
            ], package: static::PACKAGE);
        }

        if (! config('filament-peek.disablePluginStyles', false)) {
            FilamentAsset::register([
                Css::make(static::ID, __DIR__.'/../resources/dist/filament-peek.css'),
            ], package: static::PACKAGE);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
