<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;

use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

trait HasPostPreview
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
        return 'preview-post';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }
}
