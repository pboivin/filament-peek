<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use InvalidArgumentException;
use Livewire\Livewire;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;
use Pboivin\FilamentPeek\Tests\TestCase;

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
        ->assertDispatched(
            'refresh-preview-modal',
            iframeUrl: null,
            iframeContent: "Preview\n",
        );
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
        ->assertDispatched(
            'refresh-preview-modal',
            iframeUrl: 'https://example.com',
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

    $editor = new class extends BuilderEditor
    {
        public $html = '';

        protected function getPreviewModalHtmlContent(): ?string
        {
            return $this->html = parent::getPreviewModalHtmlContent();
        }
    };

    $livewire = Livewire::test($editor::class)
        ->set('pageClass', $page::class)
        ->set('builderName', 'test')
        ->set('previewView', 'preview-data')
        ->call('refreshBuilderPreview')
        ->assertDispatched('refresh-preview-modal');

    /** @var TestCase $this */
    $this->assertStringContainsString('isPeekPreviewModal:1', $livewire->get('html'));
    $this->assertStringContainsString('KEY:VALUE', $livewire->get('html'));
});
