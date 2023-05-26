<?php

namespace Pboivin\FilamentPeek\Forms\Components;

use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

class PreviewLink extends Component
{
    protected string $view = 'filament-peek::components.preview-link';

    public static function make(): static
    {
        View::share(PreviewAction::PREVIEW_ACTION_SETUP_HOOK, true);

        $static = app(static::class);

        $static->configure();

        $static->label(__('filament-peek::ui.preview-action-label'));

        return $static;
    }
}
