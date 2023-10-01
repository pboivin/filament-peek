<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Pboivin\FilamentPeek\CachedPreview;

if (config('filament-peek.useInternalPreviewUrl', false)) {
    Route::prefix('filament-peek')
        ->middleware(config('filament-peek.internalPreviewUrlMiddleWare', []))
        ->group(function () {
            Route::get('preview', function () {
                abort_unless($token = Request::query('token'), 404);

                abort_unless($preview = CachedPreview::get($token), 404);

                return $preview->render();
            })->name('filament-peek.preview');
        });
}
