<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Filament\Pages\Actions\Action;
use Pboivin\FilamentPeek\Support\Page;
use Pboivin\FilamentPeek\Support\View;

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
            ->color('secondary')
            ->before(fn ($livewire) => Page::checkPreviewModalSupport($livewire))
            ->action('openPreviewModal');

        View::setupPreviewModal();
    }
}
