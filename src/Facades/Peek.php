<?php

namespace Pboivin\FilamentPeek\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see FilamentPeekManager
 */
class Peek extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-peek';
    }
}
