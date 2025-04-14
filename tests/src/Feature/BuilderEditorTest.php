<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use InvalidArgumentException;
use Livewire\Livewire;
use Pboivin\FilamentPeek\CachedBuilderPreview;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;
use Pboivin\FilamentPeek\Support;
use Pboivin\FilamentPeek\Tests\Fixtures;
use Pboivin\FilamentPeek\Tests\TestCase;

beforeEach(function () {
    $this->login();

    $this->mock(Support\Cache::class)
        ->shouldReceive('createPreviewToken')
        ->andReturn('test');
});

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
        ->set('previewView', 'preview')
        ->call('refreshBuilderPreview')
        ->assertDispatched(
            'refresh-preview-modal',
            iframeUrl: 'http://peek.test/filament-peek/preview?token=test&refresh=1',
            iframeContent: null,
        );
});

it('mutates the builder preview data', function () {
    $page = new class extends Fixtures\EditRecordDummy
    {
        public static function getBuilderEditorSchema(string $builderName): Component|array
        {
            return [TextInput::make('test')];
        }

        public static function mutateBuilderPreviewData(string $builderName, array $editorData, array $previewData): array
        {
            $previewData['KEY'] = 'VALUE';

            return $previewData;
        }
    };

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', $page::class)
        ->set('builderName', 'test')
        ->set('previewView', 'preview-data')
        ->call('refreshBuilderPreview')
        ->assertDispatched(
            'refresh-preview-modal',
            iframeUrl: 'http://peek.test/filament-peek/preview?token=test&refresh=1',
            iframeContent: null,
        );

    $preview = CachedBuilderPreview::get('test');

    expect($preview->data['KEY'])->toEqual('VALUE');
});
