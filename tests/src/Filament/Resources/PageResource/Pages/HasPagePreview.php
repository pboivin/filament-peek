<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages;

use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

trait HasPagePreview
{
    use HasPreviewModal;

    protected function getActions(): array
    {
        return [
            PreviewAction::make()
                ->label('Test_Preview_Action'),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'preview-page';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'page';
    }
}
