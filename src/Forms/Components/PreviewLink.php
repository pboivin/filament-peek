<?php

namespace Pboivin\FilamentPeek\Forms\Components;

use Filament\Forms\Components\ViewField;
use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

class PreviewLink extends ViewField
{
    protected string $view = 'filament-peek::components.preview-link';

    public static function make(string $name = ''): static
    {
        View::share(PreviewAction::PREVIEW_ACTION_SETUP_HOOK, true);

        $static = parent::make($name ?: 'filament_peek_preview_link');

        $static->label(__('filament-peek::ui.preview-action-label'));

        return $static;
    }
}
