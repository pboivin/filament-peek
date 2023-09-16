<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource;

class CreatePost extends CreateRecord
{
    use HasPostPreview;

    protected static string $resource = PostResource::class;
}
