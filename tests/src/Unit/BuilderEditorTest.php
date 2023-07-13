<?php

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Livewire\Livewire;
use Pboivin\FilamentPeek\Livewire\BuilderEditor;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

it('can render', function () {
    Livewire::test(BuilderEditor::class)
        ->assertSeeHtml('Editor');
});

it('throws an exception for missing form schema', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing Builder editor schema');

    Livewire::test(BuilderEditor::class)
        ->set('pageClass', BuilderEditorEditRecordDummy::class)
        ->set('builderName', 'test')
        ->call('refreshBuilderPreview');
});

it('throws an exception for missing blade view', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing preview modal URL or Blade view');

    $page = new class extends BuilderEditorEditRecordDummy
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
    $page = new class extends BuilderEditorEditRecordDummy
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
    $page = new class extends BuilderEditorEditRecordDummy
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

class BuilderEditorEditRecordDummy extends EditRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    public $data = ['blocks' => ['key' => 'value']];

    protected static string $resource = BuilderEditorBuilderResourceDummy::class;

    public function getRecord(): Model
    {
        return new BuilderEditorBuilderModelDummy();
    }
}

class BuilderEditorBuilderResourceDummy extends Resource
{
    protected static ?string $model = BuilderEditorBuilderModelDummy::class;
}

class BuilderEditorBuilderModelDummy extends Model
{
}
