<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource;

class EditPage extends EditRecord
{
    use HasPagePreview;

    protected static string $resource = PageResource::class;
}
