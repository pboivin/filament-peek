<?php

namespace Pboivin\FilamentPeek\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Pboivin\FilamentPeek\FilamentPeekServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected function getPackageProviders($app)
    {

        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentPeekServiceProvider::class,
        ];
    }

    // public function getEnvironmentSetUp($app)
    // {
    // }
}
