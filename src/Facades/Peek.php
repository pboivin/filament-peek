<?php

namespace Pboivin\FilamentPeek\Facades;

use Illuminate\Support\Facades\Facade;
use Livewire\Component;
use Pboivin\FilamentPeek\FilamentPeekManager;

/**
 * @method static bool pluginIsLoaded()
 * @method static void ensurePluginIsLoaded()
 * @method static void registerPreviewModal(bool $value = true)
 * @method static bool isPreviewModalRegistered()
 * @method static void registerBuilderEditor(bool $value = true)
 * @method static bool isBuilderPreviewRegistered()
 * @method static bool pageSupportsPreviewModal(Component $page)
 * @method static void ensurePageSupportsPreviewModal(Component $page)
 * @method static bool pageSupportsBuilderPreview(Component $page)
 * @method static void ensurePageSupportsBuilderPreview(Component $page)
 *
 * @see FilamentPeekManager
 */
class Peek extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-peek';
    }
}
