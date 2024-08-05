<?php

namespace Pboivin\FilamentPeek;

use Illuminate\Support\Facades\Cache;

class CachedPreview
{
    public static ?string $cacheStore = null;

    public function __construct(
        public string $pageClass,
        public string $view,
        public array $data,
    ) {}

    public static function make(
        string $pageClass,
        string $view,
        array $data,
    ): CachedPreview {
        return new CachedPreview($pageClass, $view, $data);
    }

    public function render(): string
    {
        return $this->pageClass::renderPreviewModalView($this->view, $this->data);
    }

    public function put(string $token, int $ttl = 60): bool
    {
        return Cache::store(static::$cacheStore)->put("filament-peek-preview-{$token}", $this, $ttl);
    }

    public static function get(string $token): ?CachedPreview
    {
        return Cache::store(static::$cacheStore)->get("filament-peek-preview-{$token}");
    }
}
