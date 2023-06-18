@if (\Pboivin\FilamentPeek\Support\View::needsPreviewModal())
    <div
        class="filament-peek-modal"
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
        x-on:refresh-preview-modal.window="onRefreshPreviewModal($event)"
        x-on:close-preview-modal.window="onClosePreviewModal($event)"
        x-on:keyup.escape.window="handleEscapeKey()"
        x-bind:style="modalStyle"
        x-trap="isOpen"
        x-cloak
    >
        @if (\Pboivin\FilamentPeek\Support\View::needsBuilderPreview())
            @livewire('filament-peek::builder-editor')
        @endif

        <div class="filament-peek-panel filament-peek-preview">
            <div class="filament-peek-panel-header">
                <div id="filament-peek-panel-title" x-text="modalTitle"></div>

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

                        <button 
                            type="button" 
                            class="filament-peek-rotate-preset"
                            x-on:click="rotateDevicePreset()" 
                            x-bind:disabled="!canRotatePreset"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="28" viewBox="0 0 5 7"><path fill="currentColor" fill-rule="evenodd" d="M3.936 2.944a.53.53 0 0 0-.723-.194L.463 4.338a.53.53 0 0 0-.193.723l.794 1.375a.53.53 0 0 0 .723.193l2.75-1.587a.53.53 0 0 0 .193-.723z" clip-rule="evenodd"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width=".529" d="M4.22.564v1.044h-.126M.78 2.026C1.002.317 3.427.011 4.094 1.608m0 0h-.953"/></svg>
                        </button>
                    </div>
                @endif

                <x-filament::button
                    color="secondary"
                    x-on:click="dispatchCloseModalEvent()"
                >
                    {{ __('filament-peek::ui.close-modal-action-label') }}
                </x-filament::button>
            </div>

            <div
                x-ref="previewModalBody"
                class="{{ Arr::toCssClasses([
                    'filament-peek-panel-body' => true,
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
    </div>
@endif
