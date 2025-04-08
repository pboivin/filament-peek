<?php

namespace Pboivin\FilamentPeek;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPeekServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name(FilamentPeekPlugin::ID)
            ->hasTranslations()
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('preview');
    }

    public function packageRegistered(): void
    {
        $this->app->scoped('filament-peek', function (): FilamentPeekManager {
            return new FilamentPeekManager;
        });
    }
}
