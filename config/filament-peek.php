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
            'icon' => 'heroicon-o-desktop-computer',
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
            'icon' => 'heroicon-o-device-mobile',
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
    | Show Active Device Preset
    |--------------------------------------------------------------------------
    |
    | Highlight the active device preset with a small circle under the icon.
    |
    */

    'showActiveDevicePreset' => true,

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
    | page preview.
    |
    */

    'closeModalWithEscapeKey' => true,

];
