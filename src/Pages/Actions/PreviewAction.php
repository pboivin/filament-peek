<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Closure;
use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support;

class PreviewAction extends Action
{
    protected array|Closure $previewModalData = [];

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->color('gray')
            ->action(function ($livewire) {
                Support\Panel::ensurePluginIsLoaded();

                Support\Page::ensurePreviewModalSupport($livewire);

                $livewire->initialPreviewModalData(
                    $this->evaluate($this->previewModalData)
                );

                $livewire->openPreviewModal();
            });

        Support\View::setupPreviewModal();
    }

    public function previewModalData(array|Closure $previewModalData): static
    {
        $this->previewModalData = $previewModalData;

        return $this;
    }
}
