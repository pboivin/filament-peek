<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Pboivin\FilamentPeek\Support\HTML;

trait HasBuilderPreview
{
    protected function getBuilderEditorTitle(): string
    {
        return __('filament-peek::ui.builder-editor-title');
    }

    protected function mutateInitialBuilderEditorData(array $data): array
    {
        return $data;
    }

    protected function getBuilderEditorPreviewUrl(string $builderName): ?string
    {
        return null;
    }

    protected function getBuilderEditorPreviewView(string $builderName): ?string
    {
        return null;
    }

    public static function getBuilderEditorSchema(string $builderName): array
    {
        return [];
    }

    public static function renderBuilderEditorPreviewView(string $builderName, string $view, array $data): string
    {
        return HTML::injectPreviewModalStyle(
            view($view, $data)->render()
        );
    }

    /** @internal */
    public function openPreviewModalForBuidler(string $builderName): void
    {
        $editorData = $this->mutateInitialBuilderEditorData(
            $this->getBuilderEditorData($builderName)
        );

        $this->emit('openBuilderEditor', [
            'previewView' => $this->getBuilderEditorPreviewView($builderName),
            'previewUrl' => $this->getBuilderEditorPreviewUrl($builderName),
            'modalTitle' => $this->getPreviewModalTitle(),
            'editorTitle' => $this->getBuilderEditorTitle(),
            'editorData' => $editorData,
            'builderName' => $builderName,
            'pageClass' => static::class,
        ]);
    }

    /** @internal */
    protected function getBuilderEditorData(string $builderName): array
    {
        return [
            $builderName => $this->data[$builderName],
        ];
    }
}
