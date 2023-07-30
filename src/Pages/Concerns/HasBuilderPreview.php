<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Component;
use InvalidArgumentException;
use Pboivin\FilamentPeek\Support\Html;

trait HasBuilderPreview
{
    protected function getListeners(): array
    {
        return array_merge($this->listeners, [
            'updateBuilderFieldWithEditorData' => 'updateBuilderFieldWithEditorData',
        ]);
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

    public static function getBuilderEditorSchema(string $builderName): Component|array
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
        if (array_key_exists($builderName, $editorData)) {
            $this->data[$builderName] = $editorData[$builderName];
        }

        if (class_exists('\FilamentTiptapEditor\TiptapEditor')) {
            $this->dispatch('refresh-tiptap-editors');
        }
    }

    /** @internal */
    protected function prepareBuilderEditorData(string $builderName): array
    {
        if (array_key_exists($builderName, $this->data)) {
            return $this->form->getStateOnly([$builderName]);
        }

        return [];
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
        $schema = static::getBuilderEditorSchema($builderName);

        return $schema instanceof Builder;
    }

    /** @internal */
    public function openPreviewModalForBuidler(string $builderName): void
    {
        $this->checkCustomListener();

        $editorData = $this->mutateInitialBuilderEditorData(
            $builderName,
            $this->prepareBuilderEditorData($builderName)
        );

        $this->dispatch(
            'openBuilderEditor',
            previewView: $this->getBuilderPreviewView($builderName),
            previewUrl: $this->getBuilderPreviewUrl($builderName),
            modalTitle: $this->getPreviewModalTitle(),
            editorTitle: $this->getBuilderEditorTitle(),
            editorData: $editorData,
            builderName: $builderName,
            pageClass: static::class,
        );
    }

    private function checkCustomListener(): void
    {
        $hasCustomListener = collect($this->getListeners())
            ->values()
            ->contains('updateBuilderFieldWithEditorData');

        if (! $hasCustomListener) {
            throw new InvalidArgumentException("Missing 'updateBuilderFieldWithEditorData' Livewire event listener. Add it to your Page's `\$listeners` array.");
        }
    }
}
