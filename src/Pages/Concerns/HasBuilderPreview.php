<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Filament\Support\Exceptions\Halt;
use InvalidArgumentException;

trait HasBuilderPreview
{
    protected function getBuilderEditorTitle(): string
    {
        return __('filament-peek::ui.builder-editor-title');
    }

    public function openPreviewModalForBuidler(string $builderField): void
    {
        $previewModalUrl = null;
        $previewModalHtmlContent = null;

        // try {
        //     $this->previewModalData = $this->mutatePreviewModalData($this->preparePreviewModalData());

        //     if ($previewModalUrl = $this->getPreviewModalUrl()) {
        //         // pass
        //     } elseif ($view = $this->getPreviewModalView()) {
        //         $previewModalHtmlContent = $this->injectPreviewModalStyle(
        //             $this->renderPreviewModalView($view, $this->previewModalData)
        //         );
        //     } else {
        //         throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
        //     }
        // } catch (Halt $exception) {
        //     $this->closePreviewModal();

        //     return;
        // }

        $this->dispatchBrowserEvent('open-preview-modal', [
            'modalTitle' => $this->getPreviewModalTitle(),
            'editorTitle' => $this->getBuilderEditorTitle(),
            'iframeUrl' => $previewModalUrl,
            'iframeContent' => $previewModalHtmlContent,
            'withEditor' => true,
        ]);
    }

    /*
    protected array $previewModalData = [];

    protected function getPreviewModalUrl(): ?string
    {
        return null;
    }

    protected function getPreviewModalView(): ?string
    {
        return null;
    }



    protected function getPreviewModalDataRecordKey(): string
    {
        return 'record';
    }

    protected function mutatePreviewModalData($data): array
    {
        return $data;
    }

    protected function renderPreviewModalView($view, $data): string
    {
        return view($view, $data)->render();
    }

    protected function injectPreviewModalStyle($htmlContent): string
    {
        if (config('filament-peek.allowIframePointerEvents', false)) {
            return $htmlContent;
        }

        $style = '<style>body { pointer-events: none !important; }</style>';

        return preg_replace('#\</[ ]*body\>#', "{$style}</body>", $htmlContent);
    }

    protected function preparePreviewModalData(): array
    {
        $data = $this->form->getState();
        $record = null;

        if (method_exists($this, 'mutateFormDataBeforeCreate')) {
            $data = $this->mutateFormDataBeforeCreate($data);

            $record = $this->getModel()::make($data);
        } elseif (method_exists($this, 'mutateFormDataBeforeSave')) {
            $data = $this->mutateFormDataBeforeSave($data);

            $record = $this->getRecord();

            $record->fill($data);
        }

        return [
            $this->getPreviewModalDataRecordKey() => $record,
            'isPeekPreviewModal' => true,
        ];
    }

    public function openPreviewModal(): void
    {
        $previewModalUrl = null;
        $previewModalHtmlContent = null;

        try {
            $this->previewModalData = $this->mutatePreviewModalData($this->preparePreviewModalData());

            if ($previewModalUrl = $this->getPreviewModalUrl()) {
                // pass
            } elseif ($view = $this->getPreviewModalView()) {
                $previewModalHtmlContent = $this->injectPreviewModalStyle(
                    $this->renderPreviewModalView($view, $this->previewModalData)
                );
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

    public function closePreviewModal(): void
    {
        $this->dispatchBrowserEvent('close-preview-modal');
    }
    */
}
