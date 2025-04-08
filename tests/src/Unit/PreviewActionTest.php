<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Pages\Page;
use Pboivin\FilamentPeek\Facades\Peek;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

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

it('registers the preview modal', function () {
    expect(Peek::isPreviewModalRegistered())->toBeFalse();

    PreviewAction::make();

    expect(Peek::isPreviewModalRegistered())->toBeTrue();
});
