<?php

namespace Pboivin\FilamentPeek;

class CachedBuilderPreview extends CachedPreview
{
    public static function make(
        string $pageClass,
        string $view,
        array $data,
    ): CachedBuilderPreview {
        return new CachedBuilderPreview($pageClass, $view, $data);
    }

    public function render(): string
    {
        return $this->pageClass::renderBuilderPreview($this->view, $this->data);
    }
}
