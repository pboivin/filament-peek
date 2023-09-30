<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Pboivin\FilamentPeek\CachedPreview;

Route::prefix('filament-peek')->group(function () {
    Route::get('preview', function () {
        abort_unless($token = Request::query('token'), 404);

        abort_unless($preview = Cache::get("filament-peek-preview-{$token}"), 404);

        /** @var CachedPreview $preview*/
        return $preview->render();
    })->name('filament-peek.preview');
});
