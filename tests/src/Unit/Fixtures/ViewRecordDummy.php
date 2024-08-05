<?php

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class ViewRecordDummy extends ViewRecord
{
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;

    public function getRecord(): Model
    {
        return new ModelDummy;
    }
}
