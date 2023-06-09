# Peek

<p>
<a href="https://github.com/pboivin/filament-peek/actions"><img src="https://github.com/pboivin/filament-peek/workflows/run-tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/v/pboivin/filament-peek" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="http://poser.pugx.org/pboivin/filament-peek/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/pboivin/filament-peek"><img src="https://img.shields.io/packagist/l/pboivin/filament-peek" alt="License"></a>
</p>

A Filament plugin that adds a full-screen preview modal to your Edit pages. The modal can be used before saving to preview a modified record.

![Screenshots of the edit page and preview modal](./art/00_collage.jpg)

## Installation

You can install the package via composer:

```bash
composer require pboivin/filament-peek:"^0.3"
```

#### Compatibility

| Peek | Status | Filament | PHP |
|------|----------|-----|--------|
| [0.x](https://github.com/pboivin/filament-peek/tree/0.x) | Current | ^2.0 | ^8.0 |
| [1.x](https://github.com/pboivin/filament-peek/tree/1.x) | Beta | ^2.0 | ^8.0 |

#### Demo Project

For an easy way to try out the plugin on a simple Filament project, have a look at the [filament-peek-demo](https://github.com/pboivin/filament-peek-demo/tree/0.x) repository.

## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-peek-config"
```

This will add a `config/filament-peek.php` file to your project. Here are the main options you can configure:


| Name | Type | Description |
|---|---|---|
| `devicePresets` | `array\|false` | Quickly resize the preview iframe to specific dimensions. |
| `initialDevicePreset` | `string` | Default device preset to be activated when the preview modal is open. |
| `showActiveDevicePreset` | `bool` | Highlight the active device preset with a small circle under the icon. |
| `allowIframeOverflow` | `bool` | Allow the iframe dimensions to go beyond the capacity of the available preview modal area. |
| `allowIframePointerEvents` | `bool` | Allow all pointer events within the iframe. By default, only scrolling is allowed. (See [Pointer Events](#pointer-events)) |
| `closeModalWithEscapeKey` | `bool` | Close the preview modal by pressing the Escape key. |

## Overview

- Start by adding a preview action button to the top of your page. Alternatively, you can add a preview link component somewhere in your form (e.g. in a sidebar).
- When the button is clicked, a full-screen modal opens.
- The modal contains an iframe that can be resized according to some configured presets.
- The iframe can either render a full Blade view or a custom URL.

## Basic Usage with Blade Views

In your `EditRecord` pages:

- Add the `HasPreviewModal` trait.
- Add the `PreviewAction` class to the returned array in `getActions()`.
- Override the `getPreviewModalView()` method to define your Blade view.
- If your view expects a `$page` variable, override the `getPreviewModalDataRecordKey()` method to define it. By default, this variable will be `$record`.

The modal can also be used on `CreateRecord` pages and, if needed, `ListRecords` pages.

#### Complete Example

`app/Filament/Resources/PageResource/Pages/EditPage.php`

```php
namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPage extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    protected function getActions(): array
    {
        return [
            PreviewAction::make(),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'pages.preview';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'page';
    }
}
```

## Detecting the Preview Modal

If you're using the same Blade view for the site page and the preview modal, you can detect if the view is currently rendered in a preview modal by checking for the `$isPeekPreviewModal` variable:

`resources/views/pages/show.blade.php`

```blade
<x-layout>
    @isset($isPeekPreviewModal)
        <x-preview-banner />
    @endisset
    
    <x-container>
        ...
    </x-container>
</x-layout>
```

## Adding Extra Data to Previews

By default, the `$record` and `$isPeekPreviewModal` variables are made available to the rendered Blade view. If your form is relatively simple and all fields belong directly to the record, this may be all you need. However, if you have complex relationships or heavily customized form fields, you may need to include some additional data in order to render your page preview. You can add other variables by overriding the `mutatePreviewModalData()` method:

```php
class EditPage extends EditRecord
{
    // ...
    
    protected function mutatePreviewModalData($data): array
    {
        $data['message'] = 'This is a preview';

        return $data;
    }
}
```

This would make a `$message` variable available to the view when rendered in the preview modal.

Inside of `mutatePreviewModalData()` you can access:
- the modified record with unsaved changes: `$data['record']`
- the original record: `$this->record`
- any other data from the form: `$this->data['my_custom_field']`

## Dynamically Setting the View

If needed, you can use the `previewModalData` property to dynamically set the modal view:

```php
class EditPage extends EditRecord
{
    // ...
    
    protected function getPreviewModalView(): ?string
    {
        return $this->previewModalData['record']->is_featured ? 
            'posts.featured' :
            'posts.show';
    }
}
```

## Alternate Templating Engines

If you're not using Blade views on the front-end, override the `renderPreviewModalView()` method and render the preview with your templating engine of choice:

```php
    protected function renderPreviewModalView($view, $data): string
    {
        return MyTemplateEngine::render($view, $data);
    }
```

## Using a Preview URL

Instead of rendering a view, you may implement page previews using a custom URL and the PHP session (or cache). Instead of `getPreviewModalView()`, override the `getPreviewModalUrl()` method to return the preview URL:

```php
class EditPage extends EditRecord
{
    // ...
    
    protected function getPreviewModalUrl(): ?string
    {
        $token = uniqid();

        $sessionKey = "preview-$token";

        session()->put($sessionKey, $this->previewModalData);

        return route('pages.preview', ['token' => $token]);
    }
}
```

Then, you can fetch the preview data from the controller through the session (or cache):

```php
class PageController extends Controller
{
    // ...

    public function preview($token)
    {
        $previewData = session("preview-$token");

        abort_if(is_null($previewData), 404);
        
        // ...
    }
}
```

This technique can also be used to implement page previews with a decoupled front-end (e.g. Next.js):

- From `getPreviewModalUrl()`, generate the preview token and return a **front-end** preview URL.
- Then from the front-end page component, fetch the preview data from the **back-end** preview URL.

## Embedding a Preview Link into the Form

Instead of `PreviewAction`, you can use the `PreviewLink` component to integrate the Preview button directly in your form (e.g. in a sidebar):

```php 
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

class PageResource extends Resource
{
    // ...

    public static function form(Form $form): Form
    {
        return $form->schema([
            // ...

            PreviewLink::make(),
        ]);
    }
}
```

## Pointer Events

By default, only scrolling is allowed in the preview iframe. This is done by inserting a very small `<style>` tag at the end of your preview's `<body>`. If this doesn't work for your use-case, you can enable all pointer events with the [`allowIframePointerEvents` configuration](#configuration).

If you need finer control over pointer events in your previews, first set this option to `true`. Then, in your page template, add the required CSS or JS. Here's an exemple disabling preview pointer events only for `<a>` tags:

`resources/views/pages/show.blade.php`

```blade
...

@isset($isPeekPreviewModal)
    <style>
        a { pointer-events: none !important; }
    </style>
@endisset
```

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
