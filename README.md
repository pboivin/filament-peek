# Peek

A Filament plugin that adds a full-screen preview modal to your Edit pages. The modal can be used before save to preview a modified record.

## Installation

You can install the package via composer:

```bash
composer require pboivin/filament-peek
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-peek-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-peek-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

TODO

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
