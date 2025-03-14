@if (\Pboivin\FilamentPeek\Support\View::needsPreviewModal())
    <div
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="filament-peek-modal-title"
        x-data="PeekPreviewModal({
            devicePresets: @js(config('filament-peek.devicePresets', false)),
            initialDevicePreset: @js(config('filament-peek.initialDevicePreset', 'fullscreen')),
            allowIframeOverflow: @js(config('filament-peek.allowIframeOverflow', false)),
            shouldCloseModalWithEscapeKey: @js(config('filament-peek.closeModalWithEscapeKey', true)),
            editorAutoRefreshDebounceTime: @js(config('filament-peek.builderEditor.autoRefreshDebounceMilliseconds', 500)),
            shouldRestoreIframePositionOnRefresh: @js(config('filament-peek.builderEditor.preservePreviewScrollPosition', false)),
            canResizeEditorSidebar: @js(config('filament-peek.builderEditor.canResizeSidebar', true)),
            editorSidebarMinWidth: @js(config('filament-peek.builderEditor.sidebarMinWidth', '30rem')),
            editorSidebarInitialWidth: @js(config('filament-peek.builderEditor.sidebarInitialWidth', '30rem')),
        })"
        x-bind:class="{
            'filament-peek-modal': true,
            'is-filament-peek-editor-resizing': editorIsResizing,
        }"
        x-bind:style="modalStyle"
        x-on:open-preview-tab.window="onOpenPreviewTab($event)"
        x-on:open-preview-modal.window="onOpenPreviewModal($event)"
        x-on:refresh-preview-modal.window="onRefreshPreviewModal($event)"
        x-on:close-preview-modal.window="onClosePreviewModal($event)"
        x-on:keyup.escape.window="handleEscapeKey()"
        x-on:mouseup.window="onMouseUp($event)"
        x-on:mousemove.debounce.5ms.window="onMouseMove($event)"
        x-trap="isOpen"
        x-cloak
    >
        @if (\Pboivin\FilamentPeek\Support\View::needsBuilderEditor())
            @livewire('filament-peek::builder-editor')
        @endif

        <div class="filament-peek-panel filament-peek-preview">
            <div class="filament-peek-panel-header">
                <div
                    id="filament-peek-modal-title"
                    class="filament-peek-modal-title"
                    x-text="modalTitle"
                ></div>

                @if (config('filament-peek.devicePresets', false))
                    <div class="filament-peek-device-presets">
                        @foreach (config('filament-peek.devicePresets') as $presetName => $presetConfig)
                            <button
                                type="button"
                                data-preset-name="{{ $presetName }}"
                                x-on:click="setDevicePreset('{{ $presetName }}')"
                                x-bind:class="{'is-active-device-preset': isActiveDevicePreset('{{ $presetName }}')}"
                            >
                                <x-filament::icon
                                    :icon="$presetConfig['icon'] ?? 'heroicon-o-computer-desktop'"
                                    :class="\Illuminate\Support\Arr::toCssClasses(['rotate-90' => $presetConfig['rotateIcon'] ?? false])"
                                />
                            </button>
                        @endforeach

                        <button
                            type="button"
                            class="filament-peek-rotate-preset"
                            x-on:click="rotateDevicePreset()"
                            x-bind:disabled="!canRotatePreset"
                        >
                            @include('filament-peek::partials.icon-rotate')
                        </button>
                    </div>
                @endif

                <div class="filament-peek-modal-actions">
                    @include('filament-peek::partials.modal-actions')
                </div>
            </div>

            <div
                x-ref="previewModalBody"
                class="{{ \Illuminate\Support\Arr::toCssClasses([
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

                <div class="filament-peek-iframe-cover"></div>
            </div>
        </div>
    </div>
@endif
