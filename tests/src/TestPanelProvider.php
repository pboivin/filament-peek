<?php

namespace Pboivin\FilamentPeek\Tests;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource;

class TestPanelProvider extends PanelProvider
{
    public static $should_load_plugin_assets = true;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                PageResource::class,
                PostResource::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                $this->getPlugin(),
            ]);
    }

    protected function getPlugin()
    {
        if (self::$should_load_plugin_assets) {
            return FilamentPeekPlugin::make();
        }

        return FilamentPeekPlugin::make()
            ->disablePluginStyles()
            ->disablePluginScripts();
    }
}
