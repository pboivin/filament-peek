<?php

namespace Pboivin\FilamentPeek\Forms\Components;

use Filament\Forms\Components\Component;

class Preview extends Component
{
    protected string $view = 'filament-peek::components.preview';

    public static function make(): static
    {
        return app(static::class)->configure();
    }
}
