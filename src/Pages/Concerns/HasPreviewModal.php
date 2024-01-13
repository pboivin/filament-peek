<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Filament\Support\Exceptions\Halt;
use InvalidArgumentException;
use Pboivin\FilamentPeek\Support\Html;

trait HasPreviewModal
{
    protected array $previewModalData = [];

    protected bool $shouldCallHooksBeforePreview = false;

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

    protected function mutatePreviewModalData(array $data): array
    {
        return $data;
    }

    /** @internal */
    protected function renderPreviewModalView(?string $view, array $data): string
    {
        return Html::injectPreviewModalStyle(
            view($view, $data)->render()
        );
    }

    /** @internal */
    protected function preparePreviewModalData(): array
    {
        $data = $this->form->getState($this->shouldCallHooksBeforePreview);
        $record = null;

        if (method_exists($this, 'mutateFormDataBeforeCreate')) {
            $data = $this->mutateFormDataBeforeCreate($data);

            $record = $this->getModel()::make($data);
        } elseif (method_exists($this, 'mutateFormDataBeforeSave')) {
            $data = $this->mutateFormDataBeforeSave($data);

            $record = $this->getRecord();

            $record->fill($data);
        } elseif (method_exists($this, 'getRecord')) {
            $record = $this->getRecord();
        }

        return [
            $this->getPreviewModalDataRecordKey() => $record,
            'isPeekPreviewModal' => true,
        ];
    }

    /** @internal */
    public function openPreviewModal(): void
    {
        $previewModalUrl = null;
        $previewModalHtmlContent = null;

        try {
            $this->previewModalData = $this->mutatePreviewModalData($this->preparePreviewModalData());

            if ($previewModalUrl = $this->getPreviewModalUrl()) {
                // pass
            } elseif ($view = $this->getPreviewModalView()) {
                $previewModalHtmlContent = $this->renderPreviewModalView($view, $this->previewModalData);
            } else {
                throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
            }
        } catch (Halt $exception) {
            $this->closePreviewModal();

            return;
        }

        $this->dispatchBrowserEvent('open-preview-modal', [
            'modalTitle' => $this->getPreviewModalTitle(),
            'iframeUrl' => $previewModalUrl,
            'iframeContent' => $previewModalHtmlContent,
        ]);
    }

    /** @internal */
    public function closePreviewModal(): void
    {
        $this->dispatchBrowserEvent('close-preview-modal');
    }
}
