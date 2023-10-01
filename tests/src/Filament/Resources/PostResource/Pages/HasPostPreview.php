<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

trait HasPostPreview
{
    use HasBuilderPreview;
    use HasPreviewModal;

    protected function getActions(): array
    {
        return [
            PreviewAction::make()
                ->label('Test_Preview_Action'),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'preview-post';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }

    public static function getBuilderEditorSchema(string $builderName): Component|array
    {
        return [TextInput::make('test')];
    }
}
