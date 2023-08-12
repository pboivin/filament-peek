<?php

namespace Pboivin\FilamentPeek\Forms\Actions;

use Filament\Forms\Components\Actions\Action;
use Pboivin\FilamentPeek\Support;

class InlinePreviewAction extends Action
{
    public static int $count = 1;

    public static function getDefaultName(): ?string
    {
        return 'inlinePreview'.static::$count++;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->link()
            ->action(function ($livewire) {
                Support\Panel::ensurePluginIsLoaded();

                Support\Page::ensurePreviewModalSupport($livewire);

                $arguments = $this->getArguments();

                if ($builderField = $arguments['builderField'] ?? false) {
                    Support\Page::ensureBuilderPreviewSupport($livewire);

                    $livewire->openPreviewModalForBuidler($builderField);
                } else {
                    $livewire->openPreviewModal();
                }
            });

        Support\View::setupPreviewModal();
    }

    public function builderPreview(string $builderField = 'blocks'): static
    {
        Support\View::setupBuilderEditor();

        $this->arguments(['builderField' => $builderField]);

        return $this;
    }
}
