<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use InvalidArgumentException;
use Livewire\Livewire;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;

// @todo: Builder editor tests
//  - mutateBuilderPreviewData
//  - prepareBuilderPreviewData
//  - renderBuilderPreview

it('can render', function () {
    Livewire::test(BuilderEditor::class)
        ->assertSeeHtml('Editor');
});

it('throws an exception for missing form schema', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing Builder editor schema');

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', Fixtures\EditRecordDummy::class)
        ->set('builderName', 'test')
        ->call('refreshBuilderPreview');
});

it('throws an exception for missing blade view', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing preview modal URL or Blade view');

    $page = new class extends Fixtures\EditRecordDummy
    {
        public static function getBuilderEditorSchema(string $builderName): Component|array
        {
            return [TextInput::make('test')];
        }
    };

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', $page::class)
        ->set('builderName', 'test')
        ->call('refreshBuilderPreview');
});

it('renders the preview blade view', function () {
    $page = new class extends Fixtures\EditRecordDummy
    {
        public static function getBuilderEditorSchema(string $builderName): Component|array
        {
            return [TextInput::make('test')];
        }
    };

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', $page::class)
        ->set('builderName', 'test')
        ->set('previewView', 'preview')
        ->call('refreshBuilderPreview')
        ->assertDispatchedBrowserEvent('refresh-preview-modal');
});

it('renders the preview url', function () {
    $page = new class extends Fixtures\EditRecordDummy
    {
        public static function getBuilderEditorSchema(string $builderName): Component|array
        {
            return [TextInput::make('test')];
        }
    };

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', $page::class)
        ->set('builderName', 'test')
        ->set('previewUrl', 'https://example.com')
        ->call('refreshBuilderPreview')
        ->assertDispatchedBrowserEvent('refresh-preview-modal');
});
