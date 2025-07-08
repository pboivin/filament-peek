<div
    class="filament-peek-panel filament-peek-editor"
    x-bind:style="editorStyle"
    x-ref="builderEditor"
    @if ($this->canAutoRefresh()) data-auto-refresh-strategy="{{ $this->autoRefreshStrategy }}" @endif
    @if ($this->shouldAutoRefresh()) data-should-auto-refresh="1" @endif
>
    <div class="filament-peek-panel-header">
        <div x-text="editorTitle"></div>

        <div class="filament-peek-editor-buttons">
            <x-filament::button
                color="gray"
                icon="heroicon-o-arrow-path"
                class="{{ \Illuminate\Support\Arr::toCssClasses([
                    'filament-peek-editor-refresh',
                    'filament-peek-editor-icon',
                    'is-icon-active' => $this->shouldAutoRefresh(),
                ]) }}"
                :label-sr-only="true"
                :title="__('filament-peek::ui.refresh-action-label')"
                x-on:click="Livewire.dispatch('refreshBuilderPreview')"
            ></x-filament::button>

            @if ($this->canAutoRefresh())
                <x-filament::dropdown
                    :dark-mode="config('filament.dark_mode')"
                    placement="bottom-end"
                >
                    <x-slot name="trigger">
                        <x-filament::button
                            color="secondary"
                            icon="heroicon-o-cog-6-tooth"
                            class="filament-peek-editor-settings filament-peek-editor-icon"
                            :label-sr-only="true"
                            :title="__('filament-peek::ui.editor-settings-label')"
                        ></x-filament::button>
                    </x-slot>

                    <x-filament::dropdown.list>
                        <label
                            for="filament-peek-editor-auto-refresh"
                            class="filament-peek-editor-auto-refresh-label"
                        >
                            <input
                                type="checkbox"
                                id="filament-peek-editor-auto-refresh"
                                wire:model.live="autoRefresh"
                            >
                            <span>{{ __('filament-peek::ui.editor-auto-refresh-label') }}</span>
                        </label>
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            @endif
        </div>
    </div>

    <div
        class="filament-peek-panel-body"
        x-on:focusout="onEditorFocusOut($event)"
    >
        <div
            x-bind:class="{
                'filament-peek-builder-editor': true,
                'has-sidebar-actions': editorHasSidebarActions,
            }"
        >
            <div class="filament-peek-builder-content">
                <form wire:submit="submit">
                    {{ $this->form }}

                    <button type="submit" style="display: none">
                        {{ __('filament-peek::ui.refresh-action-label') }}
                    </button>
                </form>

                <x-filament-actions::modals />
            </div>

            <div class="filament-peek-builder-actions"></div>

            <div
                class="filament-peek-editor-resizer"
                x-on:mousedown="onEditorResizerMouseDown($event)"
                x-bind:style="{display: editorIsResizable ? 'initial' : 'none'}"
            ></div>
        </div>
    </div>
</div>
