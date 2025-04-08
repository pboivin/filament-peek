<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Pages\Page;
use Pboivin\FilamentPeek\Facades\Peek;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;

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

it('registers the preview modal without builder preview', function () {
    expect(Peek::isPreviewModalRegistered())->toBeFalse();
    expect(Peek::isBuilderPreviewRegistered())->toBeFalse();

    InlinePreviewAction::make();

    expect(Peek::isPreviewModalRegistered())->toBeTrue();
    expect(Peek::isBuilderPreviewRegistered())->toBeFalse();
});

it('registers the preview modal with builder preview', function () {
    expect(Peek::isPreviewModalRegistered())->toBeFalse();
    expect(Peek::isBuilderPreviewRegistered())->toBeFalse();

    InlinePreviewAction::make()->builderPreview('test');

    expect(Peek::isPreviewModalRegistered())->toBeTrue();
    expect(Peek::isBuilderPreviewRegistered())->toBeTrue();
});

it('supports the builderName alias for builderPreview', function () {
    expect(Peek::isPreviewModalRegistered())->toBeFalse();
    expect(Peek::isBuilderPreviewRegistered())->toBeFalse();

    InlinePreviewAction::make()->builderName('test');

    expect(Peek::isPreviewModalRegistered())->toBeTrue();
    expect(Peek::isBuilderPreviewRegistered())->toBeTrue();
});
