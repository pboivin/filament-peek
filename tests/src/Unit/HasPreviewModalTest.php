<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Pboivin\FilamentPeek\Exceptions\PreviewModalException;
use Tests\TestCase;

it('has no initial preview modal url', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getPreviewModalUrl())->toBeNull();
});

it('has no initial preview modal view', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getPreviewModalView())->toBeNull();
});

it('has initial preview modal title', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getPreviewModalTitle())->not()->toBeEmpty();
});

it('has initial preview modal data record key', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    expect($page->getPreviewModalDataRecordKey())->toEqual('record');
});

it('prepares preview modal data on create pages', function () {
    $page = invade(new Fixtures\CreateRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof Fixtures\ModelDummy)->toBeTrue();
});

it('prepares preview modal data on view pages', function () {
    $page = invade(new Fixtures\ViewRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof Fixtures\ModelDummy)->toBeTrue();
});

it('prepares preview modal data on edit pages', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof Fixtures\ModelDummy)->toBeTrue();
});

it('prepares preview modal data on list pages', function () {
    $page = invade(new Fixtures\ListRecordsDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'])->toBeNull();
});

it('requires url or blade view', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing preview modal URL or Blade view');

    $page = invade(new Fixtures\EditRecordDummy);

    $page->openPreviewModal();
});

it('mutates preview modal data before opening the modal', function () {
    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }

        protected function mutatePreviewModalData($data): array
        {
            return array_merge($data, ['test' => 'test']);
        }
    });

    $page->openPreviewModal();

    expect($page->previewModalData['test'])->toEqual('test');
});

it('requires internal preview url for preview tab', function () {
    Config::set('filament-peek.internalPreviewUrl.enabled', false);

    /** @var TestCase $this */
    $this->expectException(PreviewModalException::class);
    $this->expectExceptionMessage('You must enable the [internalPreviewUrl] configuration to open the preview in a new tab');

    $page = invade(new Fixtures\EditRecordDummy);

    $page->openPreviewTab();
});
