<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Pboivin\FilamentPeek\CachedPreview;

if (config('filament-peek.internalPreviewUrl.enabled', false)) {
    Route::prefix('filament-peek')
        ->middleware(config('filament-peek.internalPreviewUrl.middleware', []))
        ->group(function () {
            Route::get('preview', function () {
                abort_unless($token = Request::query('token'), 404);

                abort_unless($preview = CachedPreview::get($token), 404);

                if (Request::wantsJson()) {
                    return $preview->data;
                }

                return $preview->render();
            })->name('filament-peek.preview');
        });
}
