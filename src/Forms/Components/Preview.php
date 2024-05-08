<?php

namespace Pboivin\FilamentPeek\Forms\Components;

use Closure;
use Filament\Forms\Components\Component;

class Preview extends Component
{
    protected string $view = 'filament-peek::components.preview';

    protected null | string | Closure $previewView = null;

    protected null | string | Closure $previewUrl = null;

    protected array | Closure $previewData = [];

    public static function make(): static
    {
        return app(static::class)->configure();
    }

    public function previewView(null | string | Closure $view): self
    {
        $this->previewView = $view;

        return $this;
    }

    public function getPreviewView(): ?string
    {
        return $this->evaluate($this->previewView);
    }

    public function previewUrl(null | string | Closure $url): self
    {
        $this->previewUrl = $url;

        return $this;
    }

    public function getPreviewUrl(): ?string
    {
        return $this->evaluate($this->previewUrl);
    }

    public function previewData(array | Closure $data): self
    {
        $this->previewData = $data;

        return $this;
    }

    public function getPreviewData(): array
    {
        return $this->evaluate($this->previewData);
    }

    public function getPreviewContent(): ?string
    {
        if ($this->previewUrl) {
            return null;
        }

        if (!($view = $this->getPreviewView())) {
            return null;
        }

        return view($view, $this->getPreviewData())->render();
    }
}
