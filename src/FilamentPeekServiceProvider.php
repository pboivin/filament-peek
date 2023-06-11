<?php

namespace Pboivin\FilamentPeek;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\View\View;
use Spatie\LaravelPackageTools\Package;

class FilamentPeekServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-peek';

    protected array $styles = [
        'plugin-filament-peek' => __DIR__.'/../resources/dist/filament-peek.css',
    ];

    protected array $scripts = [
        'plugin-filament-peek' => __DIR__.'/../resources/dist/filament-peek.js',
    ];

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
            });
        });
    }
}
