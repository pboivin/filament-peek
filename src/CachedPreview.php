<?php

namespace Pboivin\FilamentPeek;

use Illuminate\Support\Facades\Cache;

/** @phpstan-consistent-constructor */
class CachedPreview
{
    public static ?string $cacheStore = null;

    public static int $cacheDuration = 60;

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
        return new static($pageClass, $view, $data);
    }

    public function toArray(): array
    {
        return [
            'pageClass' => $this->pageClass,
            'view' => $this->view,
            'data' => $this->data,
        ];
    }

    public static function fromArray(array $data): CachedPreview
    {
        return new static(
            pageClass: $data['pageClass'],
            view: $data['view'],
            data: $data['data'],
        );
    }

    public function render(): string
    {
        return $this->pageClass::renderPreviewModalView($this->view, $this->data);
    }

    public function put(string $token, ?int $ttl = null): bool
    {
        $ttl ??= self::$cacheDuration;

        return Cache::store(static::$cacheStore)->put("filament-peek-preview-{$token}", $this->toArray(), $ttl);
    }

    public static function get(string $token): ?CachedPreview
    {
        $data = Cache::store(static::$cacheStore)->get("filament-peek-preview-{$token}");

        return is_array($data) ? self::fromArray($data) : null;
    }
}
