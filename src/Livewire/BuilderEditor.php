<?php

namespace Pboivin\FilamentPeek\Livewire;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Livewire\Component;

/**
 * @property ComponentContainer $form
 */
class BuilderEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public ?string $previewUrl = null;

    public ?string $previewView = null;

    public ?string $builderName = null;

    public ?string $pageClass = null;

    public array $editorData = [];

    public bool $autoRefresh = false;

    public string $autoRefreshStrategy = 'simple';

    protected $listeners = [
        'refreshBuilderPreview',
        'resetBuilderEditor',
        'closeBuilderEditor',
        'openBuilderEditor',
    ];

    public function mount(): void
    {
        $this->autoRefreshStrategy = config('filament-peek.builderEditor.autoRefreshStrategy', 'simple');

        if ($this->canAutoRefresh()) {
            $this->autoRefresh = (bool) session('peek_builder_editor_auto_refresh');
        }
    }

    public function render(): ViewContract
    {
        if ($this->shouldAutoRefresh()) {
            try {
                $this->refreshBuilderPreview();
            } catch (ValidationException $e) {
                // pass
            }
        }

        return view('filament-peek::livewire.builder-editor');
    }

    public function canAutoRefresh(): bool
    {
        return (bool) config('filament-peek.builderEditor.canEnableAutoRefresh', false);
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

    public function openBuilderEditor(
        array $editorData,
        string $builderName,
        string $pageClass,
        ?string $previewView,
        ?string $previewUrl,
        ?string $modalTitle,
        ?string $editorTitle,
    ): void {
        $this->previewUrl = $previewUrl;
        $this->previewView = $previewView;
        $this->pageClass = $pageClass;
        $this->builderName = $builderName;

        $this->form->fill($editorData);

        $this->dispatch(
            'open-preview-modal',
            modalTitle: $modalTitle ?: '',
            editorTitle: $editorTitle ?: '',
            withEditor: true,
            editorHasSidebarActions: $this->pageClass::builderEditorHasSidebarActions($this->builderName),
        );

        $this->refreshBuilderPreview();
    }

    public function refreshBuilderPreview(): void
    {
        $this->dispatch(
            'refresh-preview-modal',
            iframeUrl: $this->previewUrl,
            iframeContent: $this->getPreviewModalHtmlContent(),
        );
    }

    public function closeBuilderEditor(): void
    {
        // Trigger validation
        $this->form->getState();

        $this->dispatch(
            'updateBuilderFieldWithEditorData',
            builderName: $this->builderName,
            editorData: $this->editorData,
        );

        $this->dispatch(
            'close-preview-modal',
            delay: true,
        );

        $this->dispatch('resetBuilderEditor')->self();
    }

    public function resetBuilderEditor(): void
    {
        $this->previewUrl = null;
        $this->previewView = null;
        $this->builderName = null;
        $this->pageClass = null;
    }

    public function submit(): void
    {
        $this->refreshBuilderPreview();
    }

    protected function getFormSchema(): array
    {
        if (! $this->pageClass || ! $this->builderName) {
            return [];
        }

        if ($schema = $this->pageClass::getBuilderEditorSchema($this->builderName)) {
            return Arr::wrap($schema);
        }

        throw new InvalidArgumentException('Missing Builder editor schema.');
    }

    protected function getFormStatePath(): ?string
    {
        return 'editorData';
    }

    protected function getPreviewModalHtmlContent(): ?string
    {
        if (! $this->pageClass || ! $this->builderName) {
            return '';
        }

        $formState = $this->form->getState();

        $previewData = $this->pageClass::mutateBuilderPreviewData(
            $this->builderName,
            $this->editorData,
            $this->pageClass::prepareBuilderPreviewData($formState),
        );

        if ($this->previewUrl) {
            return null;
        }

        if ($this->previewView) {
            return $this->pageClass::renderBuilderPreview(
                $this->previewView,
                $previewData
            );
        }

        throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
    }
}
