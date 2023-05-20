<?php

namespace App\Filament\Pages\Concerns;

use Filament\Support\Exceptions\Halt;
use InvalidArgumentException;

trait HasPreview
{
    public bool $isPreviewModalVisible = false;

    public ?string $previewModalHtmlContent = null;

    protected array $previewModalData = [];

    protected function getPreviewModalUrl(): ?string
    {
        return null;
    }

    protected function getPreviewModalView(): ?string
    {
        return null;
    }

    protected function getPreviewModalTitle(): string
    {
        return 'Preview'; // translate
    }

    protected function getPreviewModalDataRecordKey(): string
    {
        return 'record';
    }

    protected function getPreviewModalHtmlContent(): ?string
    {
        return $this->previewModalHtmlContent;
    }

    protected function preparePreviewModalData(): array
    {
        $data = $this->form->getState();

        $data = $this->mutateFormDataBeforeSave($data);

        $record = $this->getRecord();

        $record->fill($data);

        return [
            $this->getPreviewModalDataRecordKey() => $record,
            'isFilamentPreviewModal' => true,
        ];
    }

    protected function mutatePreviewModalData($data): array
    {
        return $data;
    }

    public function showPreviewModal(): void
    {
        try {
            $this->previewModalData = $this->mutatePreviewModalData($this->preparePreviewModalData());

            if ($this->getPreviewModalUrl()) {
                // pass
            } elseif ($view = $this->getPreviewModalView()) {
                $this->previewModalHtmlContent = view($view, $this->previewModalData)->render();
            } else {
                throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
            }
        } catch (Halt $exception) {
            $this->previewModalUrl = null;

            $this->previewModalHtmlContent = null;

            return;
        }

        $this->isPreviewModalVisible = true;

        $this->dispatchBrowserEvent('show-preview-modal');
    }

    public function hidePreviewModal(): void
    {
        $this->isPreviewModalVisible = false;

        $this->previewModalHtmlContent = null;

        $this->dispatchBrowserEvent('hide-preview-modal');
    }
}
