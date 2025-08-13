<?php

namespace Pboivin\FilamentPeek;

/**
 * @deprecated 3.0.0
 */
class CachedBuilderPreview extends CachedPreview
{
    public function render(): string
    {
        return $this->pageClass::renderBuilderPreview($this->view, $this->data);
    }
}
