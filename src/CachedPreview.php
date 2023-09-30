<?php

namespace Pboivin\FilamentPeek;

class CachedPreview
{
    public function __construct(
        public string $pageClass,
        public string $view,
        public array $data,
    ) {
    }

    public static function make(
        string $pageClass,
        string $view,
        array $data,
    ): CachedPreview {
        return new static($pageClass, $view, $data);
    }

    public function render(): string
    {
        return $this->pageClass::renderPreviewModalView($this->view, $this->data);
    }
}
