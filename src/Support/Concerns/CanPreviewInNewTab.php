<?php

namespace Pboivin\FilamentPeek\Support\Concerns;

use Closure;

trait CanPreviewInNewTab
{
    protected bool|Closure $shouldPreviewInNewTab = false;

    public function previewInNewTab(bool|Closure $condition = true): static
    {
        $this->shouldPreviewInNewTab = $condition;

        return $this;
    }

    public function shouldPreviewInNewTab(): bool
    {
        return (bool) $this->evaluate($this->shouldPreviewInNewTab);
    }
}
