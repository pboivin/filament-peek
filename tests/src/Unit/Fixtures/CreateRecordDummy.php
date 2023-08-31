<?php

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class CreateRecordDummy extends CreateRecord
{
    use HasBuilderPreview;
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;

    public ?array $data = [];
}
