<?php

namespace Pboivin\FilamentPeek\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Support\Facades\Config;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Pboivin\FilamentPeek\FilamentPeekServiceProvider;
use Pboivin\FilamentPeek\Tests\App\Models\User;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

use function Pest\Laravel\actingAs;

class TestCase extends Orchestra
{
    protected function configurePackageProviders($app)
    {
        Config::set('filament-peek.internalPreviewUrl.enabled', true);

        TestPanelProvider::$should_load_plugin_assets = true;
    }

    protected function getPackageProviders($app)
    {
        $this->configurePackageProviders($app);

        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FormsServiceProvider::class,
            TablesServiceProvider::class,
            ActionsServiceProvider::class,
            NotificationsServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentPeekServiceProvider::class,
            TestPanelProvider::class,
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

    public function login(?User $as = null): User
    {
        /** @var User */
        $user = $as ?? User::factory()->create();

        actingAs($user);

        return $user;
    }
}
