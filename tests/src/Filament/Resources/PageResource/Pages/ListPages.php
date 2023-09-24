<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource;

class ListPages extends ListRecords
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    protected function getPreviewModalView(): ?string
    {
        return 'preview-page';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'page';
    }
}
