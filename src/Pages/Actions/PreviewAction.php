<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\View;

class PreviewAction extends Action
{
    const PREVIEW_ACTION_SETUP_HOOK = '__is_filament_peek_preview_action_setup';

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'));

        $this->color('secondary');

        $this->action('openPreviewModal');

        View::share(self::PREVIEW_ACTION_SETUP_HOOK, true);
    }
}
