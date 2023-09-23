<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;
use Pboivin\FilamentPeek\Support;

it('has a default label', function () {
    $previewLink = PreviewLink::make();

    expect($previewLink->getLabel())->toEqual('Preview');
});

it('can render', function () {
    $previewLink = PreviewLink::make();

    $content = (string) $previewLink->render();

    expect($content)->toContain('wire:click.prevent="openPreviewModal"');
    expect($content)->toContain('Preview');

    // Default styles
    expect($content)->not->toContain('flex justify');
    expect($content)->not->toContain('underline');
});

it('can be aligned', function () {
    $previewLink = PreviewLink::make()->alignRight();

    $content = (string) $previewLink->render();

    expect($content)->toContain('flex justify');
});

it('can be underlined', function () {
    $previewLink = PreviewLink::make()->underline();

    $content = (string) $previewLink->render();

    expect($content)->toContain('underline');
});

it('sets the view hook to render the modal', function () {
    PreviewLink::make();

    $shared = View::getShared();

    expect($shared[Support\View::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
});
