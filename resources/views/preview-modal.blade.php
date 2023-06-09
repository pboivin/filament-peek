@if (View::shared(\Pboivin\FilamentPeek\Pages\Actions\PreviewAction::PREVIEW_ACTION_SETUP_HOOK))
    <div
        class="filament-peek-preview-modal"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="filament-peek-preview-modal-title"
        x-data="PeekPreviewModal({
            devicePresets: @js(config('filament-peek.devicePresets', false)),

            initialDevicePreset: @js(config('filament-peek.initialDevicePreset', 'fullscreen')),

            allowIframeOverflow: @js(config('filament-peek.allowIframeOverflow', false)),

            shouldShowActiveDevicePreset: @js(config('filament-peek.showActiveDevicePreset', true)),

            shouldCloseModalWithEscapeKey: @js(config('filament-peek.closeModalWithEscapeKey', true)),
        })"
        x-on:open-preview-modal.window="onOpenPreviewModal($event)"
        x-on:close-preview-modal.window="onClosePreviewModal()"
        x-on:keyup.escape.window="handleEscapeKey()"
        x-bind:style="modalStyle"
        x-trap="isOpen"
        x-cloak
    >
        <div class="filament-peek-preview-modal-header">
            <div id="filament-peek-preview-modal-title" x-text="modalTitle"></div>

            @if (config('filament-peek.devicePresets', false))
                <div class="filament-peek-device-presets">
                    @foreach (config('filament-peek.devicePresets') as $presetName => $presetConfig)
                        <button 
                            type="button" 
                            data-preset-name="{{ $presetName }}"
                            x-on:click="setDevicePreset('{{ $presetName }}')"
                            x-bind:class="{'is-active-device-preset': isActiveDevicePreset('{{ $presetName }}')}"
                        >
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
