<x-filament::button
    color="secondary"
    tag="a"
    href="#"
    wire:click.prevent="openPreviewModal"
    {{ $attributes->merge($getExtraAttributes()) }}
>
    {{ $getLabel() }}
</x-filament::button>
