@if ($alignmentClass = $getAlignmentClass())
    <div class="{{ $alignmentClass }}">
@endif

    @if ($isButton())
        <x-filament::button
            color="secondary"
            tag="a"
            href="#"
            wire:click.prevent="{{ $getPreviewAction() }}"
            {{ $attributes->merge($getExtraAttributes()) }}
        >
            {{ $getLabel() }}
        </x-filament::button>
    @else
        <a
            href="#"
            wire:click.prevent="{{ $getPreviewAction() }}"
            {{ $attributes->class(['text-primary-600', 'dark:text-primary-500', $getUnderlineClass()])
                          ->merge($getExtraAttributes())
            }}
        >
            {{ $getLabel() }}
        </a>
    @endif

@if ($getAlignmentClass())
    </div>
@endif
