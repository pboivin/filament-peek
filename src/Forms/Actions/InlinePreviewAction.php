<?php

namespace Pboivin\FilamentPeek\Forms\Actions;

use Filament\Forms\Components\Actions\Action;
use Pboivin\FilamentPeek\Facades\Peek;
use Pboivin\FilamentPeek\Support;

class InlinePreviewAction extends Action
{
    use Support\Concerns\SetsInitialPreviewModalData;

    public static int $count = 1;

    protected ?string $builderField = null;

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
                Peek::ensurePluginIsLoaded();

                Peek::ensurePageSupportsPreviewModal($livewire);

                if ($this->builderField) {
                    Peek::ensurePageSupportsBuilderPreview($livewire);

                    $livewire->openPreviewModalForBuidler($this->builderField);
                } else {
                    $livewire->initialPreviewModalData(
                        $this->evaluate($this->previewModalData)
                    );

                    $livewire->openPreviewModal();
                }
            });

        Peek::registerPreviewModal();
    }

    public function builderPreview(string $builderField = 'blocks'): static
    {
        Peek::registerBuilderEditor();

        $this->builderField = $builderField;

        return $this;
    }

    /** Alias for builderPreview */
    public function builderName(string $builderField = 'blocks'): static
    {
        $this->builderPreview($builderField);

        return $this;
    }
}
