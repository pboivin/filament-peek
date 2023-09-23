<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Pages\Page;
use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Support;

it('has a default name', function () {
    $previewAction = PreviewAction::make();

    expect($previewAction->getDefaultName())->toEqual('preview');
});

it('has a default label', function () {
    $previewAction = PreviewAction::make();

    expect($previewAction->getLabel())->toEqual('Preview');
});

it('has a default action', function () {
    $previewAction = PreviewAction::make()
        ->livewire($this->mock(Page::class));

    expect(is_callable($previewAction->getActionFunction()))->toBeTrue();
});

it('sets the view hook to render the modal', function () {
    PreviewAction::make();

    $shared = View::getShared();

    expect($shared[Support\View::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
});
