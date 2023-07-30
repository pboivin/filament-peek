<?php

namespace Pboivin\FilamentPeek\Support;

use Filament\Facades\Filament;
use Filament\Panel as FilamentPanel;
use Pboivin\FilamentPeek\Exceptions\PreviewModalException;
use Pboivin\FilamentPeek\FilamentPeekPlugin;

class Panel
{
    public static function pluginIsLoaded(): bool
    {
        /** @var FilamentPanel $panel */
        $panel = Filament::getCurrentPanel();

        return $panel->hasPlugin(FilamentPeekPlugin::ID);
    }

    public static function ensurePluginIsLoaded(): void
    {
        if (! static::pluginIsLoaded()) {
            throw new PreviewModalException('The `FilamentPeekPlugin` class is not registered in the current Panel.');
        }
    }
}
