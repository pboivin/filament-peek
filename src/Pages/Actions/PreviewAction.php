<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Filament\Pages\Actions\Action;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Support\Facades\View;

class PreviewAction extends Action
{
    use CanCustomizeProcess;

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

        View::share('is_filament_peek_preview_action_setup', true);
    }
}
