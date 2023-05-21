<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Filament\Support\Exceptions\Halt;
use InvalidArgumentException;

trait HasPreviewModal
{
    public bool $isPreviewModalOpen = false;

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
        return __('filament-peek::ui.preview-modal-title');
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

    public function openPreviewModal(): void
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
            $this->closePreviewModal();

            return;
        }

        $this->isPreviewModalOpen = true;

        $this->dispatchBrowserEvent('open-preview-modal');
    }

    public function closePreviewModal(): void
    {
        $this->isPreviewModalOpen = false;

        $this->previewModalUrl = null;

        $this->previewModalHtmlContent = null;

        $this->dispatchBrowserEvent('close-preview-modal');
    }
}
