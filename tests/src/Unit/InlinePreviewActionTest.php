<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Pages\Page;
use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;
use Pboivin\FilamentPeek\Support;

it('has a default name', function () {
    $previewAction = InlinePreviewAction::make();

    expect($previewAction->getDefaultName())->toHavePrefix('inlinePreview');
});

it('has a default label', function () {
    $previewAction = InlinePreviewAction::make();

    expect($previewAction->getLabel())->toBe('Preview');
});

it('has a default action', function () {
    $previewAction = InlinePreviewAction::make()
        ->livewire($this->mock(Page::class));

    expect(is_callable($previewAction->getActionFunction()))->toBeTrue();
});

it('sets the view hook to render the modal', function () {
    InlinePreviewAction::make();

    $shared = View::getShared();

    expect($shared[Support\View::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
    expect(isset($shared[Support\View::BUILDER_PREVIEW_SETUP_HOOK]))->toBeFalse();
});

it('sets the view hook to render a builder preview', function () {
    InlinePreviewAction::make()
        ->builderPreview('test');

    $shared = View::getShared();

    expect($shared[Support\View::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
    expect($shared[Support\View::BUILDER_PREVIEW_SETUP_HOOK])->toBeTrue();
});

it('supports the builderName alias for builderPreview', function () {
    InlinePreviewAction::make()
        ->builderName('test');

    $shared = View::getShared();

    expect($shared[Support\View::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
    expect($shared[Support\View::BUILDER_PREVIEW_SETUP_HOOK])->toBeTrue();
});
