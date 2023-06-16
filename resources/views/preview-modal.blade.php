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
        x-on:close-preview-modal.window="onClosePreviewModal()"
        x-on:keyup.escape.window="handleEscapeKey()"
        x-bind:style="modalStyle"
        x-trap="isOpen"
        x-cloak
    >
        @if (\Pboivin\FilamentPeek\Support\View::needsBuilderPreview())
            <div
                class="filament-peek-panel filament-peek-editor"
                x-bind:style="editorStyle"
            >
                <div class="filament-peek-panel-header">
                    <div id="filament-peek-panel-title" x-text="editorTitle"></div>

                    <x-filament::button
                        color="secondary"
                        icon="heroicon-o-refresh"
                        :label-sr-only="true"
                        x-on:click="Livewire.emit('refreshBuilderPreview')"
                        class="filament-peek-editor-refresh"
                    >
                        {{ __('filament-peek::ui.refresh-action-label') }}
                    </x-filament::button>
                </div>

                <div class="filament-peek-panel-body">
                    @livewire('filament-peek::builder-editor')
                </div>
            </div>
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

                        <button type="button" x-on:click="rotateDevicePreset()" x-bind:disabled="!canRotatePreset">
                            <x-heroicon-o-refresh />
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
