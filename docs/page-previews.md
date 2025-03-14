# Page Previews

![Screenshots of the edit page and preview modal](../art/01-page-preview.jpg)

## Overview

Clicking the preview action button at the top of the page opens a full-screen modal. The modal contains an iframe that can be resized according to some configured presets. The iframe can either render a full Blade view or a custom URL.

Opening and closing the preview modal does not update the record in the database, the form state is unchanged.

## Using the Preview Modal on Edit pages

In your `Edit` page, start by adding the `HasPreviewModal` trait:

```php
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreviewModal;

    // ...
```

Add the `PreviewAction` class to the page actions:

```php
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

protected function getActions(): array
{
    return [
        PreviewAction::make(),
    ];
}
```

Then, add the `getPreviewModalView()` method to define your Blade view:

```php
protected function getPreviewModalView(): ?string
{
    // This corresponds to resources/views/posts/preview.blade.php
    return 'posts.preview';
}
```

Optionally, if your Blade view expects a `$post` variable, add the `getPreviewModalDataRecordKey()` method to define the variable name:

```php
protected function getPreviewModalDataRecordKey(): ?string
{
    return 'post';
}
```

By default, the variable will be `$record`.

#### Complete Example

**`app/Filament/Resources/PostResource/Pages/EditPost.php`**

```php
namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            PreviewAction::make(),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'posts.preview';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }
}
```

**Note**: The same steps can be used to add preview on other types of pages in your Panel: `View`, `Create`, `List` and even custom pages.

## Using the Preview Modal on List pages

As described above, add the `HasPreviewModal` trait and the `getPreviewModalView()` method to your `List` page.

Then, add the `ListPreviewAction` class to your resource table:

```php
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            ListPreviewAction::make(),
        ])
        // ...
}
```

**Note**: `ListPreviewAction` does not support [Builder Previews](./builder-previews.md).

## Detecting the Preview Modal

The `EditPost` example above uses a dedicated Blade view to be rendered in the preview modal. It's also possible to use the same view for the site page and the preview modal. In this case, you can detect if the view is being used for a preview by checking for the `$isPeekPreviewModal` variable:

**`resources/views/posts/show.blade.php`**

```blade
<x-layout>
    @isset($isPeekPreviewModal)
        <div class="preview-banner">
            This is a preview.
        </div>
    @endisset

    <x-container>
        ...
    </x-container>
</x-layout>
```

## Using a Preview URL

Instead of rendering a view, you may also implement page previews using a custom URL and a storage driver such as the Laravel Cache. Instead of `getPreviewModalView()`, use the `getPreviewModalUrl()` method to define the preview URL:

```php
protected function getPreviewModalUrl(): ?string
{
    // Generate a unique token for this preview
    $postId = $this->previewModalData['post']->id ?: uniqid();
    $userId = auth()->user()->id;
    $token = md5("post-{$postId}-{$userId}");

    // Store the preview data in the cache to be retrieved from the controller
    cache()->put("preview-{$token}", $this->previewModalData, 5 * 60); // 5 minutes

    // Return the preview URL
    return route('posts.preview', ['token' => $token]);
}
```

Then, fetch the preview data from the controller:

```php
class PostController extends Controller
{
    public function preview(string $token)
    {
        $previewData = cache("preview-{$token}");

        abort_unless($previewData, 404);

        // ...
    }
}
```

#### Filament as Headless CMS

This technique can also be used to implement page previews with a decoupled front-end (e.g. Next.js, Nuxt, Astro):

- From `getPreviewModalUrl()`, generate the preview token and return a front-end preview URL. This would usually render a full page component.
- From the front-end page component, fetch the preview data from the back-end preview URL, as shown above.

See also:

- [JavaScript hooks documentation](./javascript-hooks.md)
- [Demo project using Astro](https://github.com/pboivin/filament-peek-demo-with-astro)

## Embedding a Preview Action into the Form

Instead of a `PreviewAction`, you can use the `InlinePreviewAction` component to integrate a button directly into your form (e.g. in a sidebar):

```php
use Filament\Forms\Components\Actions;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;

public static function form(Form $form): Form
{
    return $form->schema([
        Actions::make([
            InlinePreviewAction::make()
        ]),

        // ...
    ]);
}
```

By default, the action is styled as a primary link. Use the `button()` method to style it as a Filament button.

Use one of the following methods on the `Actions` wrapper to adjust the horizontal alignment:

- `alignStart()`
- `alignCenter()`
- `alignEnd()`
- `alignJustify()`

## Preview Pointer Events

By default, only scrolling is allowed in the preview iframe. This is done by inserting a very small `<style>` tag at the end of your preview's `<body>`. If this doesn't work for your use-case, you can enable all pointer events with the [`allowIframePointerEvents` option](./configuration.md).

If you need finer control over pointer events in your previews, first set this option to `true` in the configuration. Then, in your page template, add the required CSS or JS. Here's an example disabling preview pointer events only for `<a>` tags:

**`resources/views/posts/show.blade.php`**

```blade
...

@isset($isPeekPreviewModal)
    <style>
        a { pointer-events: none !important; }
    </style>
@endisset
```

**Note**: The `allowIframePointerEvents` option will not work when using a preview URL. If you run into any issues, use the CSS code above in your preview templates.

## Adding Extra Data to Previews

By default, the `$record` and `$isPeekPreviewModal` variables are made available to the rendered Blade view. If your form is relatively simple and all fields belong directly to the record, this may be all you need. However, if you have complex relationships or heavily customized form fields, you may need to include some additional data in order to render your page preview.

#### Using the `mutatePreviewModalData()` Method on the Page

```php
protected function mutatePreviewModalData(array $data): array
{
    $data['message'] = 'This is a preview';

    return $data;
}
```

This would make a `$message` variable available to the Blade view when rendered in the iframe.

Inside of `mutatePreviewModalData()` you can access:

| What | Where |
|---|---|
| The modified record with unsaved changes | `$data['record']` |
| The original record | `$this->record` |
| Any other field from the form | `$this->data['my_custom_field']` |

#### Using the `previewModalData()` Method on the Action

```php
PreviewAction::make()
    ->previewModalData(['message' => 'This is a preview']),
```

## Alternate Templating Engines

If you're not using Blade views on the front-end, override the `renderPreviewModalView()` method and render the preview with your solution of choice:

```php
public static function renderPreviewModalView(string $view, array $data): string
{
    return MyTemplateEngine::render($view, $data);
}
```

## Opening the Preview in a New Tab

You may choose to open the preview in a new tab, instead of a full-screen modal:

```php
PreviewAction::make()
    ->previewInNewTab(),
```

**Note**: You must enable the [`internalPreviewUrl` option](https://github.com/pboivin/filament-peek/blob/2.x/config/filament-peek.php#L86) in the configuration to open previews in tabs.

---

**Documentation**

<!-- BEGIN_TOC -->

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews](./builder-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
- [Upgrading from v1.x](./upgrade-guide.md)

<!-- END_TOC -->
