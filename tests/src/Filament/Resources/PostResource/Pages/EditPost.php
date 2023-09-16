<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource;

class EditPost extends EditRecord
{
    use HasPostPreview;

    protected static string $resource = PostResource::class;
}
