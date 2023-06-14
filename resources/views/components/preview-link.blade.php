@if ($alignmentClass = $getAlignmentClass())
    <div class="{{ $alignmentClass }}">
@endif

    <x-filament::button
        color="secondary"
        tag="a"
        href="#"
        :wire:click.prevent="$getPreviewAction()"
        {{ $attributes->merge($getExtraAttributes()) }}
    >
        {{ $getLabel() }}
    </x-filament::button>

@if ($getAlignmentClass())
    </div>
@endif
