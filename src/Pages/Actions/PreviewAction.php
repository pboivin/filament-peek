<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support;

class PreviewAction extends Action
{
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
                Support\Page::checkPreviewModalSupport($livewire);

                $livewire->openPreviewModal();
            });

        Support\View::setupPreviewModal();
    }
}
