<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use InvalidArgumentException;

it('has no initial builder preview url', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getBuilderPreviewUrl('blocks'))->toBeNull();
});

it('has no initial builder preview view', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getBuilderPreviewView('blocks'))->toBeNull();
});

it('has no initial builder editor schema', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getBuilderEditorSchema('blocks'))->toBeEmpty();
});

it('has initial builder editor title', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getBuilderEditorTitle())->not()->toBeEmpty();
});

it('has required event listener', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getListeners())->toContain('updateBuilderFieldWithEditorData');
});

it('prepares builder preview data on create pages', function () {
    $page = invade(new Fixtures\CreateRecordDummy);

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares builder preview data on edit pages', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

// @todo: Rewrite test
it('dispatches openBuilderEditor event', function () {
    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getBuilderPreviewView(string $builderName): ?string
        {
            return 'test';
        }
    });

    $store = invade(app(\Livewire\Mechanisms\DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModalForBuidler('blocks');

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('openBuilderEditor');
        expect($item['dispatched'][0]->serialize()['params']['previewView'])->toEqual('test');
        expect($item['dispatched'][0]->serialize()['params']['modalTitle'])->toEqual('Preview');
        expect($item['dispatched'][0]->serialize()['params']['editorTitle'])->toEqual('Editor');
        expect($item['dispatched'][0]->serialize()['params']['builderName'])->toEqual('blocks');
        expect($item['dispatched'][0]->serialize()['params']['pageClass'])->not()->toBeEmpty();
        expect($item['dispatched'][0]->serialize()['params']['editorData'])->toBeArray();
    }
});

// @todo: Rewrite test
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

    $store = invade(app(\Livewire\Mechanisms\DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModalForBuidler('blocks');

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('openBuilderEditor');
        expect($item['dispatched'][0]->serialize()['params']['editorData']['mutated'])->toBeTrue();
    }
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
