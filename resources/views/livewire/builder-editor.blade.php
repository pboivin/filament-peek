<div
    class="filament-peek-panel filament-peek-editor"
    x-bind:style="editorStyle"
>
    <div class="filament-peek-panel-header">
        <div id="filament-peek-panel-title" x-text="editorTitle"></div>

        <div class="inline-flex items-center">
            <x-filament::button
                color="secondary"
                icon="heroicon-o-refresh"
                :label-sr-only="true"
                x-on:click="Livewire.emit('refreshBuilderPreview')"
                class="{{ Arr::toCssClasses([
                    'filament-peek-editor-refresh',
                    'filament-peek-editor-icon',
                    'text-primary-600' => $autoRefresh,
                ]) }}"
            >
                {{ __('filament-peek::ui.refresh-action-label') }}
            </x-filament::button>

            <x-filament-support::dropdown placement="bottom-end">
                <x-slot name="trigger">
                    <x-filament::button
                        color="secondary"
                        icon="heroicon-s-cog"
                        :label-sr-only="true"
                        class="filament-peek-editor-settings filament-peek-editor-icon"
                    >
                        {{ __('filament-peek::ui.editor-settings-label') }}
                    </x-filament::button>
                </x-slot>

                <x-filament-support::dropdown.list>
                        <label 
                            for="filament-peek-editor-auto-refresh" 
                            class="filament-peek-editor-auto-refresh-label"
                        >
                            <input 
                                type="checkbox"
                                id="filament-peek-editor-auto-refresh"
                                class="block rounded border-gray-300 text-primary-600 shadow-sm outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:checked:border-primary-600 dark:checked:bg-primary-600"
                                wire:model="autoRefresh"
                            >
                            <span>{{ __('filament-peek::ui.editor-auto-refresh-label') }}</span>
                        </label>
                </x-filament-support::dropdown.list>
            </x-filament-support::dropdown>
        </div>
    </div>

    <div
        class="filament-peek-panel-body"
        x-ref="builderEditorBody"
        x-on:focusout="onEditorFocusOut($event)"
    >
        <div class="filament-peek-builder-editor">
            <div class="filament-peek-builder-content">
                {{ $this->form }}
            </div>
        
            <div class="filament-peek-builder-actions"></div>
        </div>
    </div>
</div>
