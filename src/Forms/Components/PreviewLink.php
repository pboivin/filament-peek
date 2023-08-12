<?php

namespace Pboivin\FilamentPeek\Forms\Components;

use Filament\Forms\Components\Component;
use Filament\Forms\Contracts\HasForms;
use Pboivin\FilamentPeek\Support;

/**
 * @deprecated Use InlinePreviewAction instead.
 */
class PreviewLink extends Component
{
    protected string $view = 'filament-peek::components.preview-link';

    protected ?string $builderField = null;

    protected ?string $alignment = null;

    protected bool $isButton = false;

    protected bool $isUnderlined = false;

    public static function make(): static
    {
        Support\View::setupPreviewModal();

        return app(static::class)
            ->configure()
            ->label(__('filament-peek::ui.preview-action-label'));
    }

    public function getLivewire(): HasForms
    {
        $livewire = parent::getLivewire();

        if ($this->builderField) {
            Support\Panel::ensurePluginIsLoaded();

            Support\Page::ensureBuilderPreviewSupport($livewire);
        }

        return $livewire;
    }

    public function builderPreview(string $value = 'blocks'): static
    {
        Support\View::setupBuilderEditor();

        $this->builderField = $value;

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

    public function underline(bool $value = true): static
    {
        $this->isUnderlined = $value;

        return $this;
    }

    public function getUnderlineClass(): string
    {
        return $this->isUnderlined ? 'underline' : '';
    }

    public function getPreviewAction(): ?string
    {
        if ($this->builderField) {
            return "openPreviewModalForBuidler('{$this->builderField}')";
        }

        return 'openPreviewModal';
    }

    public function button(bool $value = true): static
    {
        $this->isButton = $value;

        return $this;
    }

    public function isButton(): bool
    {
        return $this->isButton;
    }
}
