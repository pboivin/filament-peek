<?php

namespace Pboivin\FilamentPeek;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\View\View;
use Spatie\LaravelPackageTools\Package;

class FilamentPeekServiceProvider extends PluginServiceProvider
{
    const VERSION = '0.3.0';

    public static string $name = 'filament-peek';

    protected array $styles = [
        'plugin-filament-peek-' . self::VERSION => __DIR__ . '/../resources/dist/filament-peek.css',
    ];

    protected array $beforeCoreScripts = [
        'plugin-filament-peek-' . self::VERSION => __DIR__ . '/../resources/dist/filament-peek.js',
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
