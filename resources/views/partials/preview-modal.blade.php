{{-- Modal controlled via livewire page component --}}
@if ($this->isPreviewModalOpen ?? false)
    <div
        class="filament-peek-preview-modal"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="filament-peek-preview-modal-title"
    >
        <div class="filament-peek-preview-modal-header">
            <div id="filament-peek-preview-modal-title">
                {{ $this->getPreviewModalTitle() }}
            </div>

            <x-filament::button color="secondary" wire:click="closePreviewModal">
                {{ __('filament-peek::ui.close-modal-action-label') }}
            </x-filament::button>
        </div>

        <div class="filament-peek-preview-modal-body">
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
    x-on:open-preview-modal.window="document.body.classList.add('is-filament-peek-preview-modal-open')"
    x-on:close-preview-modal.window="document.body.classList.remove('is-filament-peek-preview-modal-open')"
></div>
