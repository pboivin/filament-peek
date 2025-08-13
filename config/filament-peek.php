<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Device Presets
    |--------------------------------------------------------------------------
    |
    | Device presets allow users to quickly resize the preview iframe to
    | specific dimensions. Set this to `false` to disable device presets.
    |
    */

    'devicePresets' => [
        'fullscreen' => [
            'icon' => 'heroicon-o-computer-desktop',
            'width' => '100%',
            'height' => '100%',
            'canRotatePreset' => false,
        ],
        'tablet-landscape' => [
            'icon' => 'heroicon-o-device-tablet',
            'rotateIcon' => true,
            'width' => '1080px',
            'height' => '810px',
            'canRotatePreset' => true,
        ],
        'mobile' => [
            'icon' => 'heroicon-o-device-phone-mobile',
            'width' => '375px',
            'height' => '667px',
            'canRotatePreset' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Initial Device Preset
    |--------------------------------------------------------------------------
    |
    | The default device preset to be activated when the modal is open.
    |
    */

    'initialDevicePreset' => 'fullscreen',

    /*
    |--------------------------------------------------------------------------
    | Allow Iframe Overflow
    |--------------------------------------------------------------------------
    |
    | Set this to `true` to allow the iframe dimensions to go beyond the
    | capacity of the available preview modal area.
    |
    */

    'allowIframeOverflow' => false,

    /*
    |--------------------------------------------------------------------------
    | Allow Iframe Pointer Events
    |--------------------------------------------------------------------------
    |
    | Set this to `true` to allow all pointer events (clicks, etc.) within the
    | iframe. By default, only scrolling is allowed.
    |
    */

    'allowIframePointerEvents' => false,

    /*
    |--------------------------------------------------------------------------
    | Close Modal With Escape Key
    |--------------------------------------------------------------------------
    |
    | Set this to `false` to reserve the Escape key for the purposes of your
    | page preview. This option does not apply to Builder previews.
    |
    */

    'closeModalWithEscapeKey' => true,

    /*
    |--------------------------------------------------------------------------
    | Internal Preview URL
    |--------------------------------------------------------------------------
    |
    | Enable this option to render all Blade previews through an internal URL.
    | This improves the isolation of the iframe in the context of the page.
    | Add additional middleware for this URL in the `middleware` array.
    |
    */

    'internalPreviewUrl' => [
        'enabled' => true,
        'middleware' => [],
    ],

];
