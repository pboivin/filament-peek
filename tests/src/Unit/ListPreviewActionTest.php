<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Pages\Page;
use Illuminate\Support\Facades\View;
use Pboivin\FilamentPeek\Support;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;

it('has a default name', function () {
    $previewAction = ListPreviewAction::make();

    expect($previewAction->getDefaultName())->toEqual('listPreview');
});

it('has a default label', function () {
    $previewAction = ListPreviewAction::make();

    expect($previewAction->getLabel())->toEqual('Preview');
});

it('has a default action', function () {
    $previewAction = ListPreviewAction::make()
        ->livewire($this->mock(Page::class));

    expect(is_callable($previewAction->getActionFunction()))->toBeTrue();
});

it('sets the view hook to render the modal', function () {
    ListPreviewAction::make();

    $shared = View::getShared();

    expect($shared[Support\View::PREVIEW_ACTION_SETUP_HOOK])->toBeTrue();
});
