<?php

namespace Pboivin\FilamentPeek\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Support\Facades\Config;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Pboivin\FilamentPeek\FilamentPeekServiceProvider;
use Pboivin\FilamentPeek\Tests\App\Models\User;

use function Pest\Laravel\actingAs;

class TestCase extends Orchestra
{
    use WithWorkbench;

    protected function configurePackageProviders($app)
    {
        Config::set('filament-peek.internalPreviewUrl.enabled', true);

        TestPanelProvider::$should_load_plugin_assets = true;
    }

    protected function getPackageProviders($app)
    {
        defined('IS_TESTING_FILAMENT_PEEK_PLUGIN') || define('IS_TESTING_FILAMENT_PEEK_PLUGIN', true);

        $this->configurePackageProviders($app);

        return [
            ActionsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentPeekServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            TestPanelProvider::class,
            WidgetsServiceProvider::class,

            // This needs to be last, not sure why
            LivewireServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('view.paths', array_merge(
            $app['config']->get('view.paths'),
            [__DIR__ . '/../resources/views'],
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
