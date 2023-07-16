<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use InvalidArgumentException;

it('has no initial builder preview url', function () {
    $page = invade(new Fixtures\EditRecordDummy());

    expect($page->getBuilderPreviewUrl('blocks'))->toBeNull();
});

it('has no initial builder preview view', function () {
    $page = invade(new Fixtures\EditRecordDummy());

    expect($page->getBuilderPreviewView('blocks'))->toBeNull();
});

it('has no initial builder editor schema', function () {
    $page = invade(new Fixtures\EditRecordDummy());

    expect($page->getBuilderEditorSchema('blocks'))->toBeEmpty();
});

it('has initial builder editor title', function () {
    $page = invade(new Fixtures\EditRecordDummy());

    expect($page->getBuilderEditorTitle())->not()->toBeEmpty();
});

it('has required event listener', function () {
    $page = invade(new Fixtures\EditRecordDummy());

    expect($page->getListeners())->toContain('updateBuilderFieldWithEditorData');
});

it('prepares builder preview data on create pages', function () {
    $page = invade(new Fixtures\CreateRecordDummy());

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares builder preview data on edit pages', function () {
    $page = invade(new Fixtures\EditRecordDummy());

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('dispatches openBuilderEditor event', function () {
    $page = invade(new class extends Fixtures\EditRecordDummy
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
    expect($event['params'][0]['builderName'])->toEqual('blocks');
    expect($event['params'][0]['pageClass'])->not()->toBeEmpty();
    expect($event['params'][0]['editorData'])->toBeArray();
});

it('mutates initial builder editor data', function () {
    $page = invade(new class extends Fixtures\EditRecordDummy
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

    expect($event['params'][0]['editorData']['mutated'])->toBeTrue();
});

it('throws an exception for missing event listener', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage("Missing 'updateBuilderFieldWithEditorData' Livewire event listener");

    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getListeners(): array
        {
            return ['test'];
        }
    });

    $page->openPreviewModalForBuidler('blocks');
});
