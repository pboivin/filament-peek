<?php

namespace Pboivin\FilamentPeek\Tests\App\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PostResource;

class CreatePost extends CreateRecord
{
    use HasPostPreview;

    protected static string $resource = PostResource::class;
}
