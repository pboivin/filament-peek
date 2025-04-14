<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use InvalidArgumentException;
use Pboivin\FilamentPeek\Tests\Fixtures;

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

it('prepares builder preview data on create pages', function () {
    $page = invade(new Fixtures\CreateRecordDummy);

    $data = $page->prepareBuilderPreviewData(['key' => 'value']);

    expect($data['key'])->toEqual('value');
});

it('prepares builder preview data on edit pages', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    $data = $page->prepareBuilderPreviewData(['key' => 'value']);

    expect($data['key'])->toEqual('value');
});

it('has required event listener', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getListeners())->toContain('updateBuilderFieldWithEditorData');
});

it('throws an exception for missing event listener', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing [updateBuilderFieldWithEditorData] Livewire event listener');

    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getListeners(): array
        {
            return ['test'];
        }
    });

    $page->openPreviewModalForBuidler('blocks');
});
