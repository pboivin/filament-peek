<?php

namespace Pboivin\FilamentPeek;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPeekServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name(FilamentPeekPlugin::NAME)
            ->hasTranslations()
            ->hasConfigFile()
            ->hasViews();
    }

    // public function packageRegistered(): void
    // {
    //     parent::packageRegistered();
    //     // $this->app->resolving('filament', function () {
    //     //     Filament::serving(function () {
    //     //         Livewire::component(
    //     //             'filament-peek::builder-editor',
    //     //             config('filament-peek.builderEditor.livewireComponentClass', BuilderEditor::class)
    //     //         );
    //     //     });
    //     // });
    // }

}
