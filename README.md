# Peek

<p>
<a href="https://github.com/pboivin/filament-peek/actions"><img src="https://github.com/pboivin/filament-peek/workflows/run-tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/v/pboivin/filament-peek" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/dt/pboivin/filament-peek" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/l/pboivin/filament-peek" alt="License"></a>
</p>

A Filament plugin to add a full-screen preview modal to your Panel pages. The modal can be used before saving to preview a modified record.

<p class="filament-hidden">
<img src="https://raw.githubusercontent.com/pboivin/filament-peek/2.x/art/01-page-preview.jpg" alt="Screenshots of the edit page and preview modal">
</p>

## Installation

You can install the package via composer:

```bash
composer require pboivin/filament-peek:"^2.0"
```

Register a `FilamentPeekPlugin` instance in your Panel provider:

```php
use Pboivin\FilamentPeek\FilamentPeekPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentPeekPlugin::make(),
        ]);
}
```

Then, publish the assets:

```bash
php artisan filament:assets
```

#### Upgrading from `1.x`

Follow the steps in the [Upgrade Guide](https://github.com/pboivin/filament-peek/tree/2.x/docs/upgrade-guide.md).

## Compatibility

| Peek | Status | Filament | PHP |
|------|----------|-----|--------|
| [1.x](https://github.com/pboivin/filament-peek/tree/1.x) | Bugfixes only | ^2.0 | ^8.0 |
| [2.x](https://github.com/pboivin/filament-peek/tree/2.x) | Current version | ^3.0 | ^8.1 |

Please feel free to report any issues you encounter with Peek [in this repository](https://github.com/pboivin/filament-peek/issues). I'll work with you to determine where the issue is coming from.

## Demo Projects

Here are a few example projects available to give this plugin a try:

| Repository | Description |
|------|----------|
| [filament-peek-demo](https://github.com/pboivin/filament-peek-demo) | Content previews on a simple Filament project with Laravel Blade views. |
| [filament-peek-demo-with-astro](https://github.com/pboivin/filament-peek-demo-with-astro) | Content previews on a more complex project with Filament as "headless CMS", and [Astro](https://astro.build/) on the front-end. (Archived) |
| [Log1x/filament-starter](https://github.com/Log1x/filament-starter) | A great starting point for TALL stack projects using Filament. Implements content previews using full-page Livewire components. |

## Documentation

The documentation is available in the ['docs' directory](https://github.com/pboivin/filament-peek/tree/2.x/docs) on GitHub:

<!-- BEGIN_TOC -->

- [Configuration](https://github.com/pboivin/filament-peek/blob/2.x/docs/configuration.md)
    - [Publishing the Config File](https://github.com/pboivin/filament-peek/blob/2.x/docs/configuration.md#publishing-the-config-file)
    - [Available Options](https://github.com/pboivin/filament-peek/blob/2.x/docs/configuration.md#available-options)
    - [Integrating With a Custom Theme](https://github.com/pboivin/filament-peek/blob/2.x/docs/configuration.md#integrating-with-a-custom-theme)
- [Page Previews](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md)
    - [Overview](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#overview)
    - [Using the Preview Modal on Edit pages](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#using-the-preview-modal-on-edit-pages)
    - [Using the Preview Modal on List pages](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#using-the-preview-modal-on-list-pages)
    - [Detecting the Preview Modal](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#detecting-the-preview-modal)
    - [Using a Preview URL](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#using-a-preview-url)
    - [Embedding a Preview Action into the Form](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#embedding-a-preview-action-into-the-form)
    - [Preview Pointer Events](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#preview-pointer-events)
    - [Adding Extra Data to Previews](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#adding-extra-data-to-previews)
    - [Alternate Templating Engines](https://github.com/pboivin/filament-peek/blob/2.x/docs/page-previews.md#alternate-templating-engines)
- [Builder Previews](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md)
    - [Overview](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#overview)
    - [Using Builder Previews on Edit pages](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#using-builder-previews-on-edit-pages)
    - [Using Multiple Builder Fields](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#using-multiple-builder-fields)
    - [Using Custom Fields](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#using-custom-fields)
    - [Customizing the Preview Action](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#customizing-the-preview-action)
    - [Automatically Updating the Builder Preview](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#automatically-updating-the-builder-preview)
    - [Adding Extra Data to the Builder Editor State](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#adding-extra-data-to-the-builder-editor-state)
    - [Adding Extra Data to the Builder Preview](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#adding-extra-data-to-the-builder-preview)
    - [Alternate Templating Engines](https://github.com/pboivin/filament-peek/blob/2.x/docs/builder-previews.md#alternate-templating-engines)
- [JavaScript Hooks](https://github.com/pboivin/filament-peek/blob/2.x/docs/javascript-hooks.md)
- [Upgrading from v1.x](https://github.com/pboivin/filament-peek/blob/2.x/docs/upgrade-guide.md)

<!-- END_TOC -->

## FAQ and Known Issues

I've started compiling some notes and solutions to common issues in [Discussions](https://github.com/pboivin/filament-peek/discussions/categories/general). Feel free to contribute your own tips and tricks.

## Changelog

Please see [CHANGELOG](https://github.com/pboivin/filament-peek/blob/2.x/CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/pboivin/filament-peek/blob/2.x/.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](https://github.com/pboivin/filament-peek/security/policy) on how to report security vulnerabilities.

## Credits

- [Patrick Boivin](https://github.com/pboivin)
- [All Contributors](https://github.com/pboivin/filament-peek/contributors)

## Acknowledgements

The initial idea is heavily inspired by module previews in [Twill CMS](https://twillcms.com/).

## License

The MIT License (MIT). Please see [License File](https://github.com/pboivin/filament-peek/blob/2.x/LICENSE.md) for more information.
 
