<?php

namespace Pboivin\FilamentPeek;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;

class FilamentPeekPlugin implements Plugin
{
    const PACKAGE = 'pboivin/filament-peek';

    const ID = 'filament-peek';

    const VERSION = '2.3.0';

    protected bool $shouldLoadPluginScripts = true;

    protected bool $shouldLoadPluginStyles = true;

    public function disablePluginScripts(): self
    {
        $this->shouldLoadPluginScripts = false;

        return $this;
    }

    public function disablePluginStyles(): self
    {
        $this->shouldLoadPluginStyles = false;

        return $this;
    }

    public function shouldLoadPluginScripts(): bool
    {
        return $this->shouldLoadPluginScripts;
    }

    public function shouldLoadPluginStyles(): bool
    {
        return $this->shouldLoadPluginStyles;
    }

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

        if ($this->shouldLoadPluginScripts()) {
            FilamentAsset::register([
                Js::make(static::ID, __DIR__.'/../resources/dist/filament-peek.js'),
            ], package: static::PACKAGE);
        }

        if ($this->shouldLoadPluginStyles()) {
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
