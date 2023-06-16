<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Pboivin\FilamentPeek\Support\Html;

trait HasBuilderPreview
{
    protected function getListeners(): array
    {
        return ['updateBuilderEditorField'];
    }

    protected function getBuilderEditorTitle(): string
    {
        return __('filament-peek::ui.builder-editor-title');
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

    protected function mutateInitialBuilderEditorData(array $data): array
    {
        return $data;
    }

    protected function prepareBuilderEditorData(string $builderName): array
    {
        return [
            $builderName => $this->data[$builderName],
        ];
    }

    public function updateBuilderEditorField(array $editorData): void
    {
        foreach ($editorData as $key => $value) {
            if ($this->data[$key] ?? false) {
                $this->data[$key] = $value;
            }
        }
    }

    public static function renderBuilderEditorPreviewView(string $builderName, string $view, array $data): string
    {
        return Html::injectPreviewModalStyle(
            view($view, $data)->render()
        );
    }

    /** @internal */
    public function openPreviewModalForBuidler(string $builderName): void
    {
        $editorData = $this->mutateInitialBuilderEditorData(
            $this->prepareBuilderEditorData($builderName)
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
}
