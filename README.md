# Peek

<p>
<a href="https://github.com/pboivin/filament-peek/actions"><img src="https://github.com/pboivin/filament-peek/workflows/run-tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/v/pboivin/filament-peek" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="http://poser.pugx.org/pboivin/filament-peek/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/l/pboivin/filament-peek" alt="License"></a>
</p>

A Filament plugin to add a full-screen preview modal to your Edit pages. The modal can be used before saving to preview a modified record. It has special support for Filament's Builder field, to arrange the editor and the preview side-by-side.

![Screenshots of the edit page and preview modal](./art/01-demo.jpg)

## Installation

You can install the package via composer:

```bash
composer require pboivin/filament-peek:"1.0.0-alpha1"
```

The requirements are **PHP 8.0** and **Filament 2.0**

#### Demo Project

For an easy way to try out the plugin on a simple Filament project, have a look at the [filament-peek-demo](https://github.com/pboivin/filament-peek-demo) repository.

@todo: **Add builder previews to demo project**

## Documentation

The documentation is available in the `docs/` directory:

- [Configuration](./docs/configuration.md)
- [Page Previews](./docs/page-previews.md)
- [Builder Field Previews](./docs/builder-field-previews.md)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Patrick Boivin](https://github.com/pboivin)
- [All Contributors](../../contributors)

## Acknowledgements

The initial idea is heavily inspired by module previews in [Twill CMS](https://twillcms.com/).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
