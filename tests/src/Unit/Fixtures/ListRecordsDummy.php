<?php

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class ListRecordsDummy extends ListRecords
{
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;
}
