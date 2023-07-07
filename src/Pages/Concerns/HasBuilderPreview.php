<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Filament\Forms\Components\Builder;
use Pboivin\FilamentPeek\Support\Html;

trait HasBuilderPreview
{
    protected function getListeners(): array
    {
        return ['updateBuilderFieldWithEditorData'];
    }

    protected function getBuilderEditorTitle(): string
    {
        return __('filament-peek::ui.builder-editor-title');
    }

    protected function getBuilderPreviewUrl(string $builderName): ?string
    {
        return null;
    }

    protected function getBuilderPreviewView(string $builderName): ?string
    {
        return null;
    }

    public static function getBuilderEditorSchema(string $builderName): array
    {
        return [];
    }

    protected function mutateInitialBuilderEditorData(string $builderName, array $editorData): array
    {
        return $editorData;
    }

    public static function mutateBuilderPreviewData(string $builderName, array $editorData, array $previewData): array
    {
        return $previewData;
    }

    /** @internal */
    public static function renderBuilderPreview(string $view, array $data): string
    {
        return Html::injectPreviewModalStyle(
            view($view, $data)->render()
        );
    }

    /** @internal */
    public function updateBuilderFieldWithEditorData(string $builderName, array $editorData): void
    {
        if ($editorData[$builderName] ?? false) {
            $this->data[$builderName] = $editorData[$builderName];
        }
    }

    /** @internal */
    protected function prepareBuilderEditorData(string $builderName): array
    {
        return [
            $builderName => $this->data[$builderName],
        ];
    }

    /** @internal */
    public static function prepareBuilderPreviewData(array $data): array
    {
        $data['isPeekPreviewModal'] = true;

        return $data;
    }

    /** @internal */
    public static function builderEditorHasSidebarActions(string $builderName): bool
    {
        $fields = static::getBuilderEditorSchema($builderName);

        return count($fields) === 1 && $fields[0] instanceof Builder;
    }

    /** @internal */
    public function openPreviewModalForBuidler(string $builderName): void
    {
        $editorData = $this->mutateInitialBuilderEditorData(
            $builderName,
            $this->prepareBuilderEditorData($builderName)
        );

        $this->emit('openBuilderEditor', [
            'previewView' => $this->getBuilderPreviewView($builderName),
            'previewUrl' => $this->getBuilderPreviewUrl($builderName),
            'modalTitle' => $this->getPreviewModalTitle(),
            'editorTitle' => $this->getBuilderEditorTitle(),
            'editorData' => $editorData,
            'builderName' => $builderName,
            'pageClass' => static::class,
        ]);
    }
}
