<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;
}
