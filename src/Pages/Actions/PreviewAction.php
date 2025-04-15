<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Facades\Peek;
use Pboivin\FilamentPeek\Support\Concerns\CanPreviewInNewTab;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;

class PreviewAction extends Action
{
    use CanPreviewInNewTab;
    use SetsInitialPreviewModalData;

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
                Peek::ensurePluginIsLoaded();

                Peek::ensurePageSupportsPreviewModal($livewire);

                $livewire->initialPreviewModalData(
                    $this->evaluate($this->previewModalData)
                );

                if ($this->shouldPreviewInNewTab()) {
                    $livewire->openPreviewTab();
                } else {
                    $livewire->openPreviewModal();
                }
            });

        Peek::registerPreviewModal();
    }
}
