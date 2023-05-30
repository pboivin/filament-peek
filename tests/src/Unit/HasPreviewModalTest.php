<?php

use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Resource;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Tests\TestCase;

it('has no initial preview modal url', function () {
    $page = invade(new EditRecordDummy());

    expect($page->getPreviewModalUrl())->toBeNull();
});

it('has no initial preview modal view', function () {
    $page = invade(new EditRecordDummy());

    expect($page->getPreviewModalView())->toBeNull();
});

it('has initial preview modal title', function () {
    $page = invade(new EditRecordDummy());

    expect($page->getPreviewModalTitle())->not()->toBeEmpty();
});

it('has initial preview modal data record key', function () {
    $page = invade(new EditRecordDummy());

    expect($page->getPreviewModalDataRecordKey())->toEqual('record');
});

it('prepares preview modal data on create pages', function () {
    $page = invade(new CreateRecordDummy());

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares preview modal data on edit pages', function () {
    $page = invade(new EditRecordDummy());

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares preview modal data on list pages', function () {
    $page = invade(new ListRecordsDummy());

    $data = $page->preparePreviewModalData();

    expect($data['record'])->toBeNull();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('requires url or blade view', function () {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing preview modal URL or Blade view');

    $page = invade(new EditRecordDummy());

    $page->openPreviewModal();
});

it('mutates preview modal data before opening the modal', function () {
    $page = invade(new class extends EditRecordDummy
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

it('dispatches open preview modal browser event', function () {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    expect(count($page->dispatchQueue))->toEqual(0);

    $page->openPreviewModal();

    expect(count($page->dispatchQueue))->toEqual(1);

    expect($page->dispatchQueue[0]['event'])->toEqual('open-preview-modal');
    expect($page->dispatchQueue[0]['data']['modalTitle'])->toEqual('Preview');
    expect($page->dispatchQueue[0]['data']['iframeUrl'])->toEqual('https://example.com');
    expect($page->dispatchQueue[0]['data']['iframeContent'])->toBeNull();
});

it('dispatches close preview modal browser event', function () {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    expect(count($page->dispatchQueue))->toEqual(0);

    $page->closePreviewModal();

    expect(count($page->dispatchQueue))->toEqual(1);

    expect($page->dispatchQueue[0]['event'])->toEqual('close-preview-modal');
});

it('renders the preview modal view', function () {
    $this->mock(ViewFactory::class, function ($mock) {
        $view = Mockery::mock(View::class, function ($mock) {
            $mock->shouldReceive('render')->andReturn('TEST');
        });

        $mock->shouldReceive('make')->andReturn($view);
    });

    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalView(): ?string
        {
            return 'preview';
        }
    });

    expect(count($page->dispatchQueue))->toEqual(0);

    $page->openPreviewModal();

    expect(count($page->dispatchQueue))->toEqual(1);

    expect($page->dispatchQueue[0]['event'])->toEqual('open-preview-modal');
    expect($page->dispatchQueue[0]['data']['modalTitle'])->toEqual('Preview');
    expect($page->dispatchQueue[0]['data']['iframeUrl'])->toBeNull();
    expect($page->dispatchQueue[0]['data']['iframeContent'])->toEqual('TEST');
});

class CreateRecordDummy extends CreateRecord
{
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;
}

class EditRecordDummy extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;

    public function getRecord(): Model
    {
        return new ModelDummy();
    }
}

class ListRecordsDummy extends ListRecords
{
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;
}

class ResourceDummy extends Resource
{
    protected static ?string $model = ModelDummy::class;
}

class ModelDummy extends Model
{
}
