<?php

namespace Pboivin\FilamentPeek;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\View\View;
use Livewire\Livewire;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;
use Spatie\LaravelPackageTools\Package;

class FilamentPeekServiceProvider extends PluginServiceProvider
{
    const VERSION = '1.1.3';

    public static string $name = 'filament-peek';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasTranslations()
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->resolving('filament', function () {
            Filament::serving(function () {
                Filament::registerRenderHook(
                    'body.end',
                    fn (): View => view('filament-peek::preview-modal'),
                );

                Livewire::component(
                    'filament-peek::builder-editor',
                    config('filament-peek.builderEditor.livewireComponentClass', BuilderEditor::class)
                );
            });
        });
    }

    protected function getBeforeCoreScripts(): array
    {
        if (config('filament-peek.disablePluginScripts', false)) {
            return [];
        }

        return [
            'plugin-filament-peek-'.self::VERSION => __DIR__.'/../resources/dist/filament-peek.js',
        ];
    }

    protected function getStyles(): array
    {
        if (config('filament-peek.disablePluginStyles', false)) {
            return [];
        }

        return [
            'plugin-filament-peek-'.self::VERSION => __DIR__.'/../resources/dist/filament-peek.css',
        ];
    }
}
