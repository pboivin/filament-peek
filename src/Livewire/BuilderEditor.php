<?php

namespace Pboivin\FilamentPeek\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use InvalidArgumentException;
use Livewire\Component;

class BuilderEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public ?string $previewUrl = null;

    public ?string $previewView = null;

    public ?string $builderName = null;

    public ?string $pageClass = null;

    public array $editorData = [];

    protected $listeners = [
        'openBuilderEditor' => 'openBuilderEditor',
        'refreshBuilderPreview' => 'refreshBuilderPreview',
    ];

    public function render()
    {
        return view('filament-peek::livewire.builder-editor');
    }

    public function updatedEditorData()
    {
        $this->refreshBuilderPreview();
    }

    public function openBuilderEditor(array $event): void
    {
        $this->previewUrl = $event['previewUrl'];
        $this->previewView = $event['previewView'];
        $this->pageClass = $event['pageClass'];
        $this->builderName = $event['builderName'];
        $this->editorData = $event['editorData'];

        $this->dispatchBrowserEvent('open-preview-modal', [
            'modalTitle' => $event['modalTitle'] ?? '',
            'editorTitle' => $event['editorTitle'] ?? '',
            'iframeUrl' => $this->previewUrl,
            'iframeContent' => $this->getPreviewModalHtmlContent(),
            'withEditor' => true,
        ]);
    }

    public function refreshBuilderPreview(): void
    {
        $this->dispatchBrowserEvent('refresh-preview-modal', [
            'iframeUrl' => $this->previewUrl,
            'iframeContent' => $this->getPreviewModalHtmlContent(),
        ]);
    }

    public function closePreviewModal(): void
    {
        $this->dispatchBrowserEvent('close-preview-modal');
    }

    protected function getFormSchema(): array
    {
        if (! $this->pageClass || ! $this->builderName) {
            return [];
        }

        if ($schema = $this->pageClass::getBuilderEditorSchema($this->builderName)) {
            return $schema;
        }

        throw new InvalidArgumentException('Missing Builder editor schema.');
    }

    protected function getFormStatePath(): ?string
    {
        return 'editorData';
    }

    protected function getPreviewModalHtmlContent(): ?string
    {
        if ($this->previewUrl) {
            return null;
        }

        if ($this->previewView) {
            return $this->pageClass::renderBuilderEditorPreviewView(
                $this->builderName,
                $this->previewView,
                $this->editorData
            );
        }

        throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
    }
}
