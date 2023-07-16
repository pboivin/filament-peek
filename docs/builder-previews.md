# Builder Previews

![Screenshot of the Builder preview modal and editor](../art/03-builder-preview.jpg)

## Overview

Clicking the preview link in the form opens a full-screen modal. The modal contains an editor on the left with a copy of the Builder field, and an iframe on the right to render the preview. The iframe can either render a full Blade view or a custom URL.

As you edit the Builder blocks, the preview can be refreshed manually or automatically. When the modal is closed, the Builder field in the main form is synchronized with the changes from the preview editor.

Closing the preview modal does not update the record in the database, only the form state is updated.

**Note**: This feature was initially designed with a focus on the Builder field but can be used with any other field type. Make sure to check out the [`Using Custom Fields`](#using-custom-fields) section below.

## Using the Builder Preview with Blade Views

#### Update the Edit Page Class

In your `Edit` page, add both `HasPreviewModal` and `HasBuilderPreview` traits:

```php
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;

class EditPost extends EditRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    // ...
```

Add the `getBuilderPreviewView()` method to define your Blade view:

```php
protected function getBuilderPreviewView(string $builderName): ?string
{
    // This corresponds to resources/views/posts/preview-blocks.blade.php
    return 'posts.preview-blocks';
}
```

Then, add the `getBuilderEditorSchema()` method to define your Builder field:

```php
public static function getBuilderEditorSchema(string $builderName): Component|array
{
    return Builder::make('content_blocks')->blocks([
            // ...
    ]);
}
```

To reduce duplication, the Builder field definition can also be extracted to a static method on the resource class (see **Complete Example** below).

#### Update the Resource Class

Add the `PreviewLink` component to your form, above or below the Builder field:

```php
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

PreviewLink::make()
    ->label('Preview Content Blocks')
    ->builderPreview('content_blocks'),
```

#### Complete Example

**`app/Filament/Resources/PostResource/Pages/EditPost.php`**

```php
namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Forms\Components\Component;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    protected static string $resource = PostResource::class;

    protected function getBuilderPreviewView(string $builderName): ?string
    {
        return 'posts.preview-blocks';
    }

    public static function getBuilderEditorSchema(string $builderName): Component|array
    {
        return PostResource::builderField(context: 'preview');
    }
}
```

**`app/Filament/Resources/PostResource.php`**

```php
namespace App\Filament\Resources;

// ...
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

class PostResource extends Resource
{
    // ...

    public static function builderField(string $context = 'form'): Field
    {
        return Builder::make('content_blocks')->blocks([
            Block::make('heading')->schema([
                Grid::make($context === 'preview' ? 1 : 2)->schema([
                    TextInput::make('title'),

                    Select::make('level')->options([
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                    ])->default('h2'),

                    Checkbox::make('uppercase')
                        ->columnSpanFull(),
                ]),
            ]),

            Block::make('paragraph')->schema([
                RichEditor::make('content')
                    ->toolbarButtons(['bold', 'italic']),
            ]),
        ])
            ->columnSpanFull()
            ->collapsible();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->columnSpanFull()
                ->required(),

            PreviewLink::make()
                ->label('Preview Content Blocks')
                ->builderPreview('content_blocks')
                ->columnSpanFull()
                ->alignRight(),

            self::builderField(),
        ]);
    }

    // ...
}
```

**Note**: Builder previews can also be used on `Create` pages.


## Using Multiple Builder Fields

Most methods in the `HasBuilderPreview` trait receive a `$builderName` argument. This corresponds to the value defined in the preview link's `builderPreview()` method. Therefore, it's possible to support independent previews for multiple Builder fields in the same page:

```php
protected function getBuilderPreviewView(string $builderName): ?string
{
    return match ($builderName) {
        'content_blocks' => 'posts.preview-blocks',
        'footer_blocks' => 'posts.preview-footer-blocks',
    };
}

public static function getBuilderEditorSchema(string $builderName): Component|array
{
    return match ($builderName) {
        'content_blocks' => PostResource::builderField(context: 'preview'),
        'footer_blocks' => PostResource::footerBuilderField(context: 'preview'),
    };
}
```

## Using Custom Fields

You may have noticed that `getBuilderEditorSchema()` supports any type of form Component. Behind the scenes, the Editor sidebar of the preview modal is a full Filament form. Therefore, you are not restricted to using a Builder field, you may use any other field type:

```php
public static function getBuilderEditorSchema(string $builderName): Component|array
{
    return RichEditor::make('post_content');
}
```

Using a single field should work without any other modifications. To support multiple fields in the sidebar, consider using a `Group` component with a custom state path:

```php
public static function getBuilderEditorSchema(string $builderName): Component|array
{
    return Group::make([
        TextInput::make('title'),

        TextInput::make('tagline'),

        RichEditor::make('paragraph'),

        // ...
    ])->statePath('post_content');
}
```

## Adding Extra Data to the Builder Editor State

When the Builder preview modal opens, the Editor sidebar is initialized with the Builder data from the main form. Use the `mutateInitialBuilderEditorData()` method to interact with the data once, before opening the preview modal:

```php
public function mutateInitialBuilderEditorData(string $builderName, array $editorData): array
{
    $editorData['preview_started_at'] = now();

    return $editorData;
}
```

## Adding Extra Data to the Builder Preview

Let's say that your Builder field is named `content`. By default, a `$content` variable is made available to the rendered Blade view. Use the `mutateBuilderPreviewData()` method to interact with the Builder preview data each time, before the preview is refreshed:

```php
public static function mutateBuilderPreviewData(string $builderName, array $editorData, array $previewData): array
{
    $previewData['message'] = "This is a preview. It started at {$editorData['preview_started_at']}.";

    return $previewData;
}
```

This would make a `$message` variable available to the Blade view when rendered in the iframe.

## Alternate Templating Engines

If you're not using Blade views on the front-end, override the `renderBuilderPreview()` method and render the preview with your solution of choice:

```php
public static function renderBuilderPreview(string $view, array $data): string
{
    return MyTemplateEngine::render($view, $data);
}
```

## Using a Preview URL

As with full page previews, you may implement Builder previews using a custom URL and a storage driver, such as the Laravel Cache or the PHP session. Instead of `getBuilderPreviewView()`, use the `getBuilderPreviewUrl()` method to define the preview URL and `mutateBuilderPreviewData()` to temporarily store the preview data:

```php
protected function getBuilderPreviewUrl(string $builderName): ?string
{
    $token = 'post-blocks';

    return route('posts.blocksPreview', ['token' => $token]);
}

public static function mutateBuilderPreviewData(string $builderName, array $editorData, array $previewData): array
{
    $token = 'post-blocks';

    $sessionKey = "preview-$token";

    session()->put($sessionKey, $previewData);

    return $previewData;
}
```

See also: [Using a Preview URL for Pages](./page-previews.md#using-a-preview-url)

## Customizing the Preview Link

By default, the preview link is styled as a primary link. Use the `button()` method to style it as a Filament button.

Use one of the following methods to adjust the horizontal alignment:

- `alignLeft()`
- `alignCenter()`
- `alignRight()`

Use the `extraAttributes()` method to add any other HTML attributes.

<a name="preview-auto-refresh"></a>

## Automatically Updating the Builder Preview

By default, the Editor sidebar is not reactive: updating the fields won't automatically refresh the preview iframe. Use the `canEnableAutoRefresh` option in the [configuration](./configuration.md) to add a checkbox in the header of the sidebar. The checkbox lets users opt into the auto-refresh behavior.

Additionally, you may choose between two auto-refresh strategies with the `autoRefreshStrategy` option:

| Name | Description |
|---|---|
| `simple` | The default strategy, which makes all fields in the sidebar behave as `lazy()`, without any other configuration. The preview modal is refreshed automatically each time the focus is taken out of a field (e.g. pressing the `Tab` key or clicking away). Because the preview iframe renders a full Blade view, this is a good compromise between user experience and performance. |
| `reactive` | The alternative strategy, which lets you make fields `lazy()` or `reactive()` as needed. Any field not explicitly configured as lazy or reactive will not trigger a refresh. |

**Important**: Making all fields reactive will have a significant performance penalty and add unnecessary strain on your Web server. Consider using `debounce()` in addition to `reactive()` on your form fields.

---

**Documentation**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews](./builder-previews.md)
