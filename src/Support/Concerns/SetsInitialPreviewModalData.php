<?php

namespace Pboivin\FilamentPeek\Support\Concerns;

use Closure;

trait SetsInitialPreviewModalData
{
    protected array|Closure $previewModalData = [];

    public function previewModalData(array|Closure $previewModalData): static
    {
        $this->previewModalData = $previewModalData;

        return $this;
    }
}
