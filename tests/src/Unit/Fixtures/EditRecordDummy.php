<?php

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditRecordDummy extends EditRecord
{
    use HasBuilderPreview;
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;

    public ?array $data = [];

    public function getRecord(): Model
    {
        return new ModelDummy;
    }
}
