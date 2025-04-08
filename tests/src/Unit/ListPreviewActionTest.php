<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Pages\Page;
use Pboivin\FilamentPeek\Facades\Peek;
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

it('registers the preview modal', function () {
    expect(Peek::isPreviewModalRegistered())->toBeFalse();

    ListPreviewAction::make();

    expect(Peek::isPreviewModalRegistered())->toBeTrue();
});
