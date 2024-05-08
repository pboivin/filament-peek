@php
    $attributes = (new \Illuminate\View\ComponentAttributeBag())
        ->merge(['id' => $getId()], escape: false)
        ->merge($getExtraAttributes(), escape: false);
@endphp

<div
    x-data="PeekPreviewComponent({
        devicePresets: @js(config('filament-peek.devicePresets', false)),
        initialDevicePreset: @js(config('filament-peek.initialDevicePreset', 'fullscreen')),
        allowIframeOverflow: @js(config('filament-peek.allowIframeOverflow', false)),
        previewUrl: @js($getPreviewUrl(), null),
        previewContent: @js($getPreviewContent(), null),
    })"
    {{ $attributes->class('filament-peek-form-preview') }}
>
    <div class="filament-peek-panel">
        <div class="filament-peek-panel-header">
            <div>{{-- spacer --}}</div>

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
                        @include('filament-peek::partials.icon-rotate')
                    </button>
                </div>
            @endif

            <div>{{-- TODO: Actions --}}</div>
        </div>

        <div
            x-ref="previewBody"
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
