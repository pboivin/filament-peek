<?php

namespace Pboivin\FilamentPeek\Forms\Actions;

use Filament\Forms\Components\Actions\Action;
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
                Support\Panel::ensurePluginIsLoaded();

                Support\Page::ensurePreviewModalSupport($livewire);

                if ($this->builderField) {
                    Support\Page::ensureBuilderPreviewSupport($livewire);

                    $livewire->openPreviewModalForBuidler($this->builderField);
                } else {
                    $livewire->initialPreviewModalData(
                        $this->evaluate($this->previewModalData)
                    );

                    $livewire->openPreviewModal();
                }
            });

        Support\View::setupPreviewModal();
    }

    public function builderPreview(string $builderField = 'blocks'): static
    {
        Support\View::setupBuilderEditor();

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
