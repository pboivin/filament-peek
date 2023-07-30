<?php

namespace Pboivin\FilamentPeek\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Pboivin\FilamentPeek\FilamentPeekServiceProvider;
use Pboivin\FilamentPeek\Tests\Models\User;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {

        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            TablesServiceProvider::class,
            NotificationsServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentPeekServiceProvider::class,

            // @todo: Redo full integration tests with Laravel Dusk
            // AdminPanelProvider::class,
            // TestServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('view.paths', array_merge(
            $app['config']->get('view.paths'),
            [__DIR__.'/../resources/views'],
        ));
    }
}
