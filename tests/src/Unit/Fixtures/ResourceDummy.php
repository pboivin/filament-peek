<?php

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Resource;

class ResourceDummy extends Resource
{
    protected static ?string $model = ModelDummy::class;
}
