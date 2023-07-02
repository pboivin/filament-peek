# Builder Field Previews

![Screenshot of the Builder preview modal and editor](../art/03-builder-preview.jpg)

## Overview

Clicking the preview link in the form opens a full-screen modal. The modal contains an editor on the left with a copy of the Builder field, and an iframe on the right to render the preview. The iframe can either render a full Blade view or a custom URL.

As you edit the Builder blocks, the preview can be refreshed manually or automatically (auto-refresh is considered experimental for the moment). When the modal is closed, the Builder field in the main form is synchronized with the changes from the preview modal.

Closing the preview modal does not update the record in the database, only the form state is updated.

**Note**: This feature was initially designed with a focus on the Builder field but can be used with any other field type. Make sure to check out the [Using Custom Fields](#using-custom-fields) section below.

## Using the Builder Preview with Blade Views

#### Update the Edit Page Class

In your `Edit` page, add both `HasPreviewModal` and `HasBuilderPreview` traits:

```php
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPage extends EditRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    // ...
```

Add the `getBuilderEditorPreviewView()` method to define your Blade view:

```php
protected function getBuilderEditorPreviewView(string $builderName): ?string
{
    // This corresponds to resources/views/pages/preview-blocks.blade.php
    return 'pages.preview-blocks';
}
```

Then, add the `getBuilderEditorSchema()` method to define your Builder field schema:

```php
public static function getBuilderEditorSchema(string $builderName): array
{
    return [
        Builder::make('page_blocks')->blocks([
            // ...
        ]),
    ];
}
```

To reduce duplication, the Builder field schema can also be extracted to a static method on the resource class (complete example below).

#### Update the Resource Class

Add the `PreviewLink` component to your form, above or below the Builder field:

```php
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

PreviewLink::make()
    ->label('Preview Page Blocks')
    ->builderPreview('page_blocks'),
```

#### Complete Example

**`app/Filament/Resources/PageResource/Pages/EditPage.php`**

```php
namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPage extends EditRecord
{
    use HasPreviewModal;
    use HasBuilderPreview;

    protected static string $resource = PageResource::class;

    protected function getBuilderEditorPreviewView(string $builderName): ?string
    {
        return 'pages.preview-blocks';
    }

    public static function getBuilderEditorSchema(string $builderName): array
    {
        return [
            PageResource::builderField(context: 'preview'),
        ];
    }
}
```

**Note**: If you're using custom event listeners on your page, make sure to also include the `updateBuilderEditorField` listener:

```php
protected function getListeners(): array
{
    return [
        'myCustomEventListener',
        'updateBuilderEditorField',
    ];
}
```

**`app/Filament/Resources/PageResource.php`**

```php
namespace App\Filament\Resources;

// ...
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

class PageResource extends Resource
{
    // ...

    public static function builderField(string $context = 'form'): Field
    {
        return Builder::make('page_blocks')->blocks([
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
                ->label('Preview Page Blocks')
                ->builderPreview('page_blocks')
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

Most methods in the `HasBuilderPreview` trait receive a `$builderName` argument. This corresponds to the value defined in the preview link's `builderPreview()` method. Therefore, it's possible to use Builder previews for multiple Builder fields in the same page:

```php
protected function getBuilderEditorPreviewView(string $builderName): ?string
{
    return match ($builderName) {
        'page_blocks' => 'pages.preview-blocks',
        'footer_blocks' => 'pages.preview-footer-blocks',
    };
}

public static function getBuilderEditorSchema(string $builderName): array
{
    return [
        match ($builderName) {
            'page_blocks' => PageResource::builderField(context: 'preview'),
            'footer_blocks' => PageResource::footerBuilderField(context: 'preview'),
        }
    ];
}
```

## Using Custom Fields

You may have noticed that `getBuilderEditorSchema()` returns an array instead of a single field.
Behind the scenes, the Editor sidebar of the preview modal is actually a full Filament form. Therefore, you are not restricted to using a Builder field, you may use any other field type:

```php
public static function getBuilderEditorSchema(string $builderName): array
{
    return [
        RichEditor::make('page_content')
            ->columnSpanFull(),
    ];
}
```

Using a single field should work out of the box. To use multiple fields in the Editor sidebar, you will need to modify the `updateBuilderEditorField()` accordingly. When the modal is closed, this method takes the data from the Editor sidebar and updates the main form:

```php
public function updateBuilderEditorField(string $builderName, array $editorData): void
{
    if ($editorData['my_custom_field'] ?? false) {
        $this->data['my_custom_field'] = $editorData['my_custom_field'];
    }

    if ($editorData['my_other_field'] ?? false) {
        $this->data['my_other_field'] = $editorData['my_other_field'];
    }

    // ...
}
```

Of course, you can do anything with the content of `$editorData`, you are not restricted to updating the main form.

## Adding Extra Data to the Builder Editor State

Use the `mutateInitialBuilderEditorData()` method to interact with the initial Builder editor data once, before opening the preview modal:

```php
public function mutateInitialBuilderEditorData(string $builderName, array $data): array
{
    $data['preview_started_at'] = now();

    return $data;
}
```

## Adding Extra Data to Builder Previews

Similarly, use the `mutateBuilderPreviewData()` method to interact with the Builder preview data each time, before the iframe is refreshed:

```php
public static function mutateBuilderPreviewData(string $builderName, array $data): array
{
    $data['message'] = "This is a preview. It started at {$data['preview_started_at']}.";

    return $data;
}
```

This would make a `$message` variable available to the Blade view when rendered in the iframe.

## Alternate Templating Engines

If you're not using Blade views on the front-end, override the `renderBuilderEditorPreviewView()` method and render the preview with your solution of choice:

```php
public static function renderBuilderEditorPreviewView(string $view, array $data): string
{
    return MyTemplateEngine::render($view, $data);
}
```

## Using a Preview URL

As with full page previews, you may implement Builder previews using a custom URL and a storage driver, such as Laravel's Cache or the PHP session. Instead of `getBuilderEditorPreviewView()`, use the `getBuilderEditorPreviewUrl()` method to define the preview URL and the `mutateBuilderPreviewData()` to temporarily store the preview data:

```php
protected function getBuilderEditorPreviewUrl(string $builderName): ?string
{
    $token = 'page-blocks';

    return route('pages.blocksPreview', ['token' => $token]);
}

public static function mutateBuilderPreviewData(string $builderName, array $data): array
{
    $token = 'page-blocks';

    $sessionKey = "preview-$token";

    session()->put($sessionKey, $data);

    return $data;
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

## Automatically Updating the Builder Preview (Experimental)

By default, the Builder Editor is not reactive. Updating the fields won't automatically refresh the preview iframe. You can enable the `showAutoRefreshToggle` in the [configuration](./configuration.md). This will add a checkbox in the header of the Editor panel in the preview modal. Activating this checkbox will make all fields in the Editor behave as `lazy()`. The preview modal will be refreshed automatically each time the focus is taken out of a field (e.g. pressing the `Tab` key or clicking away).

**Note**: Options marked as experimental may break in future releases.

---

**Documentation**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Field Previews](./builder-field-previews.md)
