<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource;

class EditPage extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    protected function getActions(): array
    {
        return [
            PreviewAction::make()
                ->label('Test_Preview_Action')
                ->previewModalData(fn () => ['initial_data' => 'PreviewAction']),
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
