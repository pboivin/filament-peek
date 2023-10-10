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
use Pboivin\FilamentPeek\CachedBuilderPreview;
use Pboivin\FilamentPeek\Support;

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

    public int $refreshCount = 1;

    private bool $refreshRequested = false;

    private ?array $previewData = null;

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
        if (! $this->pageClass || ! $this->builderName) {
            return;
        }

        if (! $this->previewUrl && ! $this->previewView) {
            throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
        }

        if ($this->refreshRequested) {
            return;
        }

        $this->refreshRequested = true;

        $this->dispatch(
            'refresh-preview-modal',
            iframeUrl: $this->getPreviewModalUrl(),
            iframeContent: $this->getPreviewModalHtmlContent(),
        );

        $this->refreshCount++;
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

    protected function getPreviewData(): array
    {
        if (! $this->pageClass || ! $this->builderName) {
            return [];
        }

        if (! $this->previewData) {
            $formState = $this->form->getState();

            $this->previewData = $this->pageClass::mutateBuilderPreviewData(
                $this->builderName,
                $this->editorData,
                $this->pageClass::prepareBuilderPreviewData($formState),
            );
        }

        return $this->previewData;
    }

    protected function getPreviewModalUrl(): ?string
    {
        if ($this->previewUrl) {
            return $this->previewUrl;
        }

        if ($this->previewView && $this->shouldUseInternalPreviewUrl()) {
            $token = app(Support\Cache::class)->createPreviewToken();

            CachedBuilderPreview::make($this->pageClass, $this->previewView, $this->getPreviewData())
                ->put($token);

            return route('filament-peek.preview', [
                'token' => $token,
                'refresh' => $this->refreshCount,
            ]);
        }

        return null;
    }

    protected function getPreviewModalHtmlContent(): ?string
    {
        if ($this->previewUrl) {
            return null;
        }

        if ($this->shouldUseInternalPreviewUrl()) {
            return null;
        }

        if ($this->previewView) {
            return $this->pageClass::renderBuilderPreview(
                $this->previewView,
                $this->getPreviewData(),
            );
        }

        return null;
    }

    protected function shouldUseInternalPreviewUrl()
    {
        return config('filament-peek.builderEditor.useInternalPreviewUrl', true)
            && config('filament-peek.internalPreviewUrl.enabled', false);
    }
}
