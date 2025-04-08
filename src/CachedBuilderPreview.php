<?php

namespace Pboivin\FilamentPeek;

class CachedBuilderPreview extends CachedPreview
{
    public function render(): string
    {
        return $this->pageClass::renderBuilderPreview($this->view, $this->data);
    }
}
