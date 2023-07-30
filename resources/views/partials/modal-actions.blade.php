<template x-if="withEditor">
    <div class="flex gap-4">
        @if (\Pboivin\FilamentPeek\Support\View::needsBuilderEditor() && config('filament-peek.builderEditor.canDiscardChanges', false) )
            <x-filament::button x-on:click="acceptEditorChanges()">
                {{ __('filament-peek::ui.accept-action-label') }}
            </x-filament::button>

            <x-filament::button color="gray" x-on:click="discardEditorChanges()">
                {{ __('filament-peek::ui.discard-action-label') }}
            </x-filament::button>
        @else
            <x-filament::button color="gray" x-on:click="acceptEditorChanges()">
                {{ __('filament-peek::ui.close-modal-action-label') }}
            </x-filament::button>
        @endif
    </div>
</template>

<template x-if="!withEditor">
    <x-filament::button color="gray" x-on:click="closePreviewModal()">
        {{ __('filament-peek::ui.close-modal-action-label') }}
    </x-filament::button>
</template>
