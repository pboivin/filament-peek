{{-- Modal controlled via livewire page component --}}
@if ($this->isPreviewModalOpen ?? false)
    <div
        class="filament-preview-modal"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="filament-preview-modal-title"
    >
        <div class="filament-preview-modal-header">
            <div id="filament-preview-modal-title">
                {{ $this->getPreviewModalTitle() }}
            </div>

            <x-filament::button color="secondary" wire:click="closePreviewModal">
                {{ __('filament-peek::ui.close-modal-action-label') }}
            </x-filament::button>
        </div>

        <div class="filament-preview-modal-body">
            <iframe
                @if ($previewModalUrl = $this->getPreviewModalUrl())
                    src="{!! $previewModalUrl !!}"
                @elseif ($previewModalHtmlContent = $this->getPreviewModalHtmlContent())
                    srcdoc="{!! htmlentities($previewModalHtmlContent) !!}"
                @endif
                frameborder="0"
                width="100%"
                height="100%"
            ></iframe>
        </div>
    </div>
@endif

{{-- Alpine.js listener to interact with body scroll --}}
<div
    x-data
    x-on:open-preview-modal.window="document.body.classList.add('is-preview-modal-open')"
    x-on:close-preview-modal.window="document.body.classList.remove('is-preview-modal-open')"
></div>
