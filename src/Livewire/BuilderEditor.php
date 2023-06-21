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

    public bool $autoRefresh = false;

    protected $listeners = [
        'refreshBuilderPreview',
        'closeBuilderEditor',
        'openBuilderEditor',
    ];

    public function mount()
    {
        if ($this->canAutoRefresh()) {
            $this->autoRefresh = (bool) session('peek_builder_editor_auto_refresh');
        }
    }

    public function render()
    {
        return view('filament-peek::livewire.builder-editor');
    }

    public function canAutoRefresh(): bool
    {
        return (bool) config('filament-peek.builderEditor.experimental.showAutoRefreshToggle', false);
    }

    public function shouldAutoRefresh(): bool
    {
        return $this->canAutoRefresh() && $this->autoRefresh;
    }

    public function updatedAutoRefresh($value): void
    {
        session()->put('peek_builder_editor_auto_refresh', (bool) $value);
    }

    public function updatedEditorData(): void
    {
        if ($this->shouldAutoRefresh()) {
            $this->refreshBuilderPreview();
        }
    }

    public function dispatchFormEvent(...$args): void
    {
        foreach ($this->getCachedForms() as $form) {
            $form->dispatchEvent(...$args);
        }

        if ($this->shouldAutoRefresh()) {
            $this->refreshBuilderPreview();
        }
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

    public function closeBuilderEditor(): void
    {
        $this->emit('updateBuilderEditorField', $this->editorData);

        $this->dispatchBrowserEvent('close-preview-modal', ['delay' => true]);
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
        $previewData = $this->pageClass::mutateBuilderPreviewData(
            $this->builderName,
            $this->pageClass::prepareBuilderPreviewData($this->editorData)
        );

        if ($this->previewUrl) {
            return null;
        }

        if ($this->previewView) {
            return $this->pageClass::renderBuilderEditorPreviewView(
                $this->previewView,
                $previewData
            );
        }

        throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
    }
}
