<?php

namespace Pboivin\FilamentPeek\Tables\Actions;

use Filament\Tables\Actions\Action;
use Pboivin\FilamentPeek\Support;

class ListPreviewAction extends Action
{
    use Support\Concerns\SetsInitialPreviewModalData;

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
                Support\Panel::ensurePluginIsLoaded();

                Support\Page::ensurePreviewModalSupport($livewire);

                $livewire->initialPreviewModalData(
                    $this->evaluate($this->previewModalData)
                );

                $livewire->setPreviewableRecord($record);

                $livewire->openPreviewModal();
            });

        Support\View::setupPreviewModal();
    }
}
