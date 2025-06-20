<?php

namespace Pboivin\FilamentPeek\Tables\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Facades\Peek;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;

class ListPreviewAction extends Action
{
    use SetsInitialPreviewModalData;

    public static function getDefaultName(): ?string
    {
        return 'listPreview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->icon('heroicon-s-eye')
            ->action(function ($livewire, $record) {
                Peek::ensurePluginIsLoaded();

                Peek::ensurePageSupportsPreviewModal($livewire);

                $livewire->initialPreviewModalData(
                    $this->evaluate($this->previewModalData)
                );

                $livewire->setPreviewableRecord($record);

                $livewire->openPreviewModal();
            });

        Peek::registerPreviewModal();
    }
}
