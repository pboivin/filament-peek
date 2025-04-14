<?php

namespace Pboivin\FilamentPeek\Tests\App\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PostResource;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;
}
