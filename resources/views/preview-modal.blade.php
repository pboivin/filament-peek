@if (View::shared(\Pboivin\FilamentPeek\Pages\Actions\PreviewAction::PREVIEW_ACTION_SETUP_HOOK))
    <div
        class="filament-peek-preview-modal"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="filament-peek-preview-modal-title"
        x-data="{
            isOpen: false,

            devicePresets: @js(config('filament-peek.devicePresets', false)),

            initialDevicePreset: @js(config('filament-peek.initialDevicePreset', 'fullscreen')),

            allowIframeOverflow: @js(config('filament-peek.allowIframeOverflow', false)),

            canRotatePreset: false,

            modalTitle: null,

            modalStyle: {
                display: 'none',
            },

            iframeUrl: null,

            iframeContent: null,

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

            rotateDevicePreset() {
                const newMaxWidth = this.iframeStyle.maxHeight;
                const newMaxHeight = this.iframeStyle.maxWidth;

                this.setIframeDimensions(newMaxWidth, newMaxHeight);
            },

            onOpenPreviewModal($event) {
                document.body.classList.add('is-filament-peek-preview-modal-open');

                this.modalTitle = $event.detail.modalTitle;
                this.iframeUrl = $event.detail.iframeUrl;
                this.iframeContent = $event.detail.iframeContent;
                this.modalStyle.display = 'flex';
                this.isOpen = true;

                setTimeout(() => { 
                    const iframe = this.$refs.previewModalBody.querySelector('iframe');

                    if (! (iframe && iframe.contentWindow)) return;

                    iframe.contentWindow.addEventListener('keyup', (e) => {
                        if (e.key === 'Escape') this.handleEscapeKey();
                    });
                }, 500);
            },

            onClosePreviewModal() {
                document.body.classList.remove('is-filament-peek-preview-modal-open');

                this.modalStyle.display = 'none';
                this.modalTitle = null;
                this.iframeUrl = null;
                this.iframeContent = null;
                this.isOpen = false;
            },

            handleEscapeKey() {
                if (!this.isOpen) return;

                this.onClosePreviewModal();
            },
        }"
        x-init="setDevicePreset()"
        x-on:open-preview-modal.window="onOpenPreviewModal($event)"
        x-on:close-preview-modal.window="onClosePreviewModal()"
        x-on:keyup.escape.window="handleEscapeKey()"
        x-bind:style="modalStyle"
        x-cloak
        x-trap="isOpen"
    >
        <div class="filament-peek-preview-modal-header">
            <div id="filament-peek-preview-modal-title" x-text="modalTitle"></div>

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

            <x-filament::button color="secondary" x-on:click="$dispatch('close-preview-modal')">
                {{ __('filament-peek::ui.close-modal-action-label') }}
            </x-filament::button>
        </div>

        <div
            x-ref="previewModalBody"
            class="{{ Arr::toCssClasses([
                'filament-peek-preview-modal-body' => true,
                'allow-iframe-overflow' => config('filament-peek.allowIframeOverflow', false),
            ]) }}"
        >
            <template x-if="iframeUrl">
                <iframe
                    x-bind:src="iframeUrl"
                    x-bind:style="iframeStyle"
                    frameborder="0"
                ></iframe>
            </template>

            <template x-if="!iframeUrl && iframeContent">
                <iframe
                    x-bind:srcdoc="iframeContent"
                    x-bind:style="iframeStyle"
                    frameborder="0"
                ></iframe>
            </template>
        </div>
    </div>
@endif
