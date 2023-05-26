<?php

use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

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

    expect($shared[PreviewAction::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
});
