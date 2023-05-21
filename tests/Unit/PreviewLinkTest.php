<?php

use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

it('has a default name', function () {
    $previewLink = PreviewLink::make();

    expect($previewLink->getName())->toBe('filament_peek_preview_link');
});

it('has a default label', function () {
    $previewLink = PreviewLink::make();

    expect($previewLink->getLabel())->toBe(__('filament-peek::ui.preview-action-label'));
});

it('can render', function () {
    $previewLink = PreviewLink::make();

    $content = (string) $previewLink->render();

    expect($content)->toContain('wire:click.prevent="openPreviewModal"');
    expect($content)->toContain(__('filament-peek::ui.preview-action-label'));
});

it('sets the view hook to render the modal', function () {
    PreviewLink::make();

    $shared = View::getShared();

    expect($shared['is_filament_peek_preview_action_setup'])->toBeTrue();
});
