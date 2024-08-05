<?php

namespace Pboivin\FilamentPeek\Tests\Unit;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Mockery;
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
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares preview modal data on view pages', function () {
    $page = invade(new Fixtures\ViewRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof Fixtures\ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares preview modal data on edit pages', function () {
    $page = invade(new Fixtures\EditRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof Fixtures\ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

// @todo: Rewrite test
// it('prepares preview modal data on list pages', function () {
//     $page = invade(new Fixtures\ListRecordsDummy());
//     $data = $page->preparePreviewModalData();
//     expect($data['record'])->toBeNull();
//     expect($data['isPeekPreviewModal'])->toBeTrue();
// });

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

// @todo: Rewrite test
it('dispatches open preview modal browser event', function () {
    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    $store = invade(app(\Livewire\Mechanisms\DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModal();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('open-preview-modal');
    }
});

// @todo: Rewrite test
it('dispatches close preview modal browser event', function () {
    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    $store = invade(app(\Livewire\Mechanisms\DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->closePreviewModal();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('close-preview-modal');
    }
});

// @todo: Rewrite test
it('renders the preview modal view', function () {
    $this->mock(ViewFactory::class, function ($mock) {
        $view = Mockery::mock(View::class, function ($mock) {
            $mock->shouldReceive('render')->andReturn('TEST');
        });

        $mock->shouldReceive('make')->andReturn($view);
    });

    $page = invade(new class extends Fixtures\EditRecordDummy
    {
        protected function getPreviewModalView(): ?string
        {
            return 'preview';
        }
    });

    $store = invade(app(\Livewire\Mechanisms\DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModal();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('open-preview-modal');
        expect($item['dispatched'][0]->serialize()['params']['iframeContent'])->toEqual('TEST');
    }
});
