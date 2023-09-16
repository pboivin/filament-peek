<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource;

class CreatePage extends CreateRecord
{
    use HasPagePreview;

    protected static string $resource = PageResource::class;
}
