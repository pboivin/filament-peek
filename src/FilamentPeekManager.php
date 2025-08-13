<?php

namespace Pboivin\FilamentPeek;

use Filament\Facades\Filament;
use Livewire\Component;
use Pboivin\FilamentPeek\Exceptions\PreviewModalException;

class FilamentPeekManager
{
    protected bool $isPreviewModalRegistered = false;

    protected bool $isBuilderPreviewRegistered = false;

    public function pluginIsLoaded(): bool
    {
        return (bool) Filament::getCurrentPanel()?->hasPlugin(FilamentPeekPlugin::ID);
    }

    public function ensurePluginIsLoaded(): void
    {
        if (! static::pluginIsLoaded()) {
            throw new PreviewModalException('The [FilamentPeekPlugin] class is not registered in the current Panel.');
        }
    }

    public function registerPreviewModal(bool $value = true): void
    {
        $this->isPreviewModalRegistered = $value;
    }

    public function isPreviewModalRegistered(): bool
    {
        return $this->isPreviewModalRegistered;
    }

    public function registerBuilderEditor(bool $value = true): void
    {
        $this->isBuilderPreviewRegistered = $value;
    }

    public function isBuilderPreviewRegistered(): bool
    {
        return $this->isBuilderPreviewRegistered;
    }

    public function pageSupportsPreviewModal(Component $page): bool
    {
        return method_exists($page, 'openPreviewModal');
    }

    public function ensurePageSupportsPreviewModal(Component $page): void
    {
        if (! $this->pageSupportsPreviewModal($page)) {
            $basename = class_basename($page);

            throw new PreviewModalException("[{$basename}] class is missing the [HasPreviewModal] trait.");
        }
    }

    public function pageSupportsBuilderPreview(Component $page): bool
    {
        return $this->pageSupportsPreviewModal($page) && method_exists($page, 'openPreviewModalForBuilder');
    }

    public function ensurePageSupportsBuilderPreview(Component $page): void
    {
        $this->ensurePageSupportsPreviewModal($page);

        if (! $this->pageSupportsBuilderPreview($page)) {
            $basename = class_basename($page);

            throw new PreviewModalException("[{$basename}] class is missing the [HasBuilderPreview] trait.");
        }
    }

    public function html(): Support\Html
    {
        return app(Support\Html::class);
    }

    public function cache(): Support\Cache
    {
        return app(Support\Cache::class);
    }
}
