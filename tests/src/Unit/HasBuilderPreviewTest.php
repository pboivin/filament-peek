<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

it('has no initial builder preview url', function () {
    $page = invade(new BuilderEditRecordDummy());

    expect($page->getBuilderPreviewUrl('blocks'))->toBeNull();
});

it('has no initial builder preview view', function () {
    $page = invade(new BuilderEditRecordDummy());

    expect($page->getBuilderPreviewView('blocks'))->toBeNull();
});

it('has no initial builder editor schema', function () {
    $page = invade(new BuilderEditRecordDummy());

    expect($page->getBuilderEditorSchema('blocks'))->toBeEmpty();
});

it('has initial builder editor title', function () {
    $page = invade(new BuilderEditRecordDummy());

    expect($page->getBuilderEditorTitle())->not()->toBeEmpty();
});

it('has required event listener', function () {
    $page = invade(new BuilderEditRecordDummy());

    expect($page->getListeners())->toContain('updateBuilderFieldWithEditorData');
});

it('prepares builder editor data on create pages', function () {
    $page = invade(new BuilderCreateRecordDummy());

    $data = $page->prepareBuilderEditorData('blocks');

    expect($data['blocks'])->toEqual(['key' => 'value']);
});

it('prepares builder editor data on edit pages', function () {
    $page = invade(new BuilderEditRecordDummy());

    $data = $page->prepareBuilderEditorData('blocks');

    expect($data['blocks'])->toEqual(['key' => 'value']);
});

it('prepares builder preview data on create pages', function () {
    $page = invade(new BuilderCreateRecordDummy());

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares builder preview data on edit pages', function () {
    $page = invade(new BuilderEditRecordDummy());

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('dispatches openBuilderEditor event', function () {
    $page = invade(new class extends BuilderEditRecordDummy
    {
        protected function getBuilderPreviewView(string $builderName): ?string
        {
            return 'test';
        }
    });

    expect(count($page->eventQueue))->toEqual(0);

    $page->openPreviewModalForBuidler('blocks');

    expect(count($page->eventQueue))->toEqual(1);

    $event = $page->eventQueue[0]->serialize();

    expect($event['event'])->toEqual('openBuilderEditor');
    expect($event['params'][0]['previewView'])->toEqual('test');
    expect($event['params'][0]['modalTitle'])->toEqual('Preview');
    expect($event['params'][0]['editorTitle'])->toEqual('Editor');
    expect($event['params'][0]['editorData'])->toEqual(['blocks' => ['key' => 'value']]);
    expect($event['params'][0]['builderName'])->toEqual('blocks');
    expect($event['params'][0]['pageClass'])->not()->toBeEmpty();
});

it('mutates initial builder editor data', function () {
    $page = invade(new class extends BuilderEditRecordDummy
    {
        protected function getBuilderPreviewView(string $builderName): ?string
        {
            return 'test';
        }

        protected function mutateInitialBuilderEditorData(string $builderName, array $data): array
        {
            $data['mutated'] = true;

            return $data;
        }
    });

    $page->openPreviewModalForBuidler('blocks');

    $event = $page->eventQueue[0]->serialize();

    expect($event['params'][0]['editorData'])->toEqual(['blocks' => ['key' => 'value'], 'mutated' => true]);
});

it('throws an exception for missing event listener', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage("Missing 'updateBuilderFieldWithEditorData' Livewire event listener");

    $page = invade(new class extends BuilderEditRecordDummy
    {
        protected function getListeners(): array
        {
            return ['test'];
        }
    });

    $page->openPreviewModalForBuidler('blocks');
});

class BuilderCreateRecordDummy extends CreateRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    public $data = ['blocks' => ['key' => 'value']];

    protected static string $resource = BuilderResourceDummy::class;
}

class BuilderEditRecordDummy extends EditRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    public $data = ['blocks' => ['key' => 'value']];

    protected static string $resource = BuilderResourceDummy::class;

    public function getRecord(): Model
    {
        return new BuilderModelDummy();
    }
}

class BuilderResourceDummy extends Resource
{
    protected static ?string $model = BuilderModelDummy::class;
}

class BuilderModelDummy extends Model
{
}

// @todo: Builder editor tests
//  - mutateBuilderPreviewData
//  - prepareBuilderPreviewData
//  - renderBuilderPreview
