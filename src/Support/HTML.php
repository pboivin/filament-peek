<?php

namespace Pboivin\FilamentPeek\Support;

class HTML
{
    public static function injectPreviewModalStyle($htmlContent): string
    {
        if (config('filament-peek.allowIframePointerEvents', false)) {
            return $htmlContent;
        }

        $style = '<style>body { pointer-events: none !important; }</style>';

        return preg_replace('#\</[ ]*body\>#', "{$style}</body>", $htmlContent);
    }
}
