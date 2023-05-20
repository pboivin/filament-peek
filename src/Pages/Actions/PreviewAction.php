<?php

namespace App\Filament\Pages\Actions;

use Filament\Pages\Actions\Action;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;

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

        $this->label('Preview'); // @todo: translate

        $this->color('secondary');

        $this->action('showPreviewModal');
    }
}
