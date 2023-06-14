<?php

use Filament\Pages\Page;
use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Support\View as SupportView;

it('has a default name', function () {
    $previewAction = PreviewAction::make();

    expect($previewAction->getDefaultName())->toBe('preview');
});

it('has a default label', function () {
    $previewAction = PreviewAction::make();

    expect($previewAction->getLabel())->toBe(__('filament-peek::ui.preview-action-label'));
});

it('has a default action', function () {
    $previewAction = PreviewAction::make()
        ->livewire($this->mock(Page::class));

    expect(is_callable($previewAction->getAction()))->toBeTrue();
});

it('sets the view hook to render the modal', function () {
    PreviewAction::make();

    $shared = View::getShared();

    expect($shared[SupportView::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
});
