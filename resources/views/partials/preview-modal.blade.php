@if ($this->isPreviewModalOpen ?? false)
    <div
        class="filament-peek-preview-modal"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="filament-peek-preview-modal-title"
        x-data="{
            devicePresets: @js(config('filament-peek.devicePresets', false)),

            initialDevicePreset: @js(config('filament-peek.initialDevicePreset', 'fullscreen')),

            allowIframeOverflow: @js(config('filament-peek.allowIframeOverflow', false)),

            canRotatePreset: false,

            iframeStyle: {
                width: '100%',
                height: '100%',
                maxWidth: '100%',
                maxHeight: '100%',
            },

            setIframeDimensions(width, height) {
                this.iframeStyle.maxWidth = width;
                this.iframeStyle.maxHeight = height;

                if (this.allowIframeOverflow) {
                    this.iframeStyle.width = width;
                    this.iframeStyle.height = height;
                }
            },

            setDevicePreset(name) {
                name = name || this.initialDevicePreset;
                if (!this.devicePresets) return;
                if (!this.devicePresets[name]) return;
                if (!this.devicePresets[name].width) return;
                if (!this.devicePresets[name].height) return;

                this.setIframeDimensions(this.devicePresets[name].width, this.devicePresets[name].height);

                this.canRotatePreset = this.devicePresets[name].canRotatePreset || false;
            },

            rotateDevicePreset(name) {
                const newMaxWidth = this.iframeStyle.maxHeight;
                const newMaxHeight = this.iframeStyle.maxWidth;
                this.iframeStyle.maxWidth = newMaxWidth;
                this.iframeStyle.maxHeight = newMaxHeight;
            },
        }"
        x-init="setDevicePreset()"
    >
        <div class="filament-peek-preview-modal-header">
            <div id="filament-peek-preview-modal-title">
                {{ $this->getPreviewModalTitle() }}
            </div>

            @if (config('filament-peek.devicePresets', false))
                <div class="filament-peek-device-presets">
                    @foreach (config('filament-peek.devicePresets') as $presetName => $presetConfig)
                        <button type="button" x-on:click="setDevicePreset('{{ $presetName }}')">
                            <x-dynamic-component
                                :component="$presetConfig['icon'] ?? 'heroicon-o-desktop-computer'"
                                :class="Arr::toCssClasses(['rotate-90' => $presetConfig['rotateIcon'] ?? false])"
                            />
                        </button>
                    @endforeach

                    <button type="button" x-on:click="rotateDevicePreset()" x-bind:disabled="!canRotatePreset">
                        <x-heroicon-o-refresh />
                    </button>
                </div>
            @endif

            <x-filament::button color="secondary" wire:click="closePreviewModal">
                {{ __('filament-peek::ui.close-modal-action-label') }}
            </x-filament::button>
        </div>

        <div class="{{ Arr::toCssClasses([
            'filament-peek-preview-modal-body' => true,
            'allow-iframe-overflow' => config('filament-peek.allowIframeOverflow', false),
        ]) }}">
            <iframe
                @if ($previewModalUrl = $this->getPreviewModalUrl())
                    src="{!! $previewModalUrl !!}"
                @elseif ($previewModalHtmlContent = $this->getPreviewModalHtmlContent())
                    srcdoc="{!! htmlentities($previewModalHtmlContent) !!}"
                @endif
                frameborder="0"
                x-bind:style="iframeStyle"
            ></iframe>
        </div>
    </div>
@endif

<div
    x-data
    x-on:open-preview-modal.window="document.body.classList.add('is-filament-peek-preview-modal-open')"
    x-on:close-preview-modal.window="document.body.classList.remove('is-filament-peek-preview-modal-open')"
></div>
