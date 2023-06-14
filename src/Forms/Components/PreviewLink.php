<?php

namespace Pboivin\FilamentPeek\Forms\Components;

use Filament\Forms\Components\Component;
use Pboivin\FilamentPeek\Support\View;

class PreviewLink extends Component
{
    protected string $view = 'filament-peek::components.preview-link';

    protected ?string $builderField = null;

    protected ?string $alignment = null;

    public static function make(): static
    {
        View::setupPreviewModal();

        $static = app(static::class);

        $static->configure();

        $static->label(__('filament-peek::ui.preview-action-label'));

        return $static;
    }

    public function builderPreview(string $builderField = 'blocks'): static
    {
        View::setupBuilderPreview();

        $this->builderField = $builderField;

        return $this;
    }

    public function getBuilderField(): ?string
    {
        return $this->builderField;
    }

    public function alignLeft(): static
    {
        $this->alignment = 'left';

        return $this;
    }

    public function alignCenter(): static
    {
        $this->alignment = 'center';

        return $this;
    }

    public function alignRight(): static
    {
        $this->alignment = 'right';

        return $this;
    }

    public function getAlignment(): ?string
    {
        return $this->alignment;
    }

    public function getAlignmentClass(): ?string
    {
        return match ($this->alignment) {
            'left' => 'flex justify-start',
            'center' => 'flex justify-center',
            'right' => 'flex justify-end',
            default => null,
        };
    }

    public function getPreviewAction(): ?string
    {
        if ($this->builderField) {
            return "openPreviewModalForBuidler('{$this->builderField}')";
        }

        return 'openPreviewModal';
    }
}
