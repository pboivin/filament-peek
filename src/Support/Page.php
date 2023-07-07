<?php

namespace Pboivin\FilamentPeek\Support;

use Filament\Pages\Page as FilamentPage;
use Pboivin\FilamentPeek\Exceptions\PreviewModalException;

class Page
{
    public static function supportsPreviewModal(FilamentPage $page): bool
    {
        return method_exists($page, 'openPreviewModal');
    }

    public static function checkPreviewModalSupport(FilamentPage $page): void
    {
        if (! static::supportsPreviewModal($page)) {
            throw new PreviewModalException('Page class is missing the `HasPreviewModal` trait.');
        }
    }

    public static function supportsBuilderPreview(FilamentPage $page): bool
    {
        return static::supportsPreviewModal($page) && method_exists($page, 'openPreviewModalForBuidler');
    }

    public static function checkBuilderPreviewSupport(FilamentPage $page): void
    {
        static::checkPreviewModalSupport($page);

        if (! static::supportsBuilderPreview($page)) {
            throw new PreviewModalException('Page class is missing the `HasBuilderPreview` trait.');
        }
    }
}
