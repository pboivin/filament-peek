# Builder Field Previews

![Screenshot of the Builder preview modal and editor](../art/03-builder-preview.jpg)

## Overview

Clicking the preview link in the form opens a full-screen modal for a specific Builder field. The modal contains an editor on the left with a copy of the Builder field, and an iframe on the right that will render the preview. The iframe can either render a full Blade view or a custom URL.

As you edit the fields, the preview can be refreshed manually or automatically (auto-refresh is considered experimental for the moment). When the modal is closed, the Builder field in the main form is updated with the changes from the preview modal.

Closing the preview modal does not update the record in the database, only the form state is updated.

## Using the Builder Preview with Blade Views

#### Update the Edit Page Class

In your `Edit` page, add both both `HasPreviewModal` and `HasBuilderPreview` traits:

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
    // This corresponds to resources/views/previews/page-blocks.blade.php
    return 'previews.page-blocks';
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
        return 'previews.page-blocks';
    }

    public static function getBuilderEditorSchema(string $builderName): array
    {
        return [
            PageResource::builderField(context: 'preview'),
        ];
    }
}
```

**Note**: If you're using custom event listeners on your page component, make sure to also include the `updateBuilderEditorField` listener:

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

            self::mainBuilderField(),
        ]);
    }

    // ...
}
```

**Note**: Builder previews can also be used on `CreateRecord` pages.

## Using Multiple Builder Fields

@todo

## Using Custom Fields

@todo

## Adding Extra Data to the Builder Editor State

Override the `mutateInitialBuilderEditorData()` method to interact with the initial Builder editor data once, before opening the preview modal:

```php
class EditPage extends EditRecord
{
    // ...
    
    public function mutateInitialBuilderEditorData(string $builderName, array $data): array
    {
        $data['preview_started_at'] = now();

        return $data;
    }
}
```

## Adding Extra Data to Builder Previews

Similarly, override the `mutateBuilderPreviewData()` method to interact with the Builder preview data, before the iframe is refreshed:

```php
class EditPage extends EditRecord
{
    // ...
    
    public static function mutateBuilderPreviewData(string $builderName, array $data): array
    {
        $data['message'] = "This is a preview. It started at {$data['preview_started_at']}.";

        return $data;
    }
}
```

This would make a `$message` variable available to the Blade view when rendered in the iframe.

## Alternate Templating Engines

@todo

## Using a Preview URL

@todo

## Customizing the Preview Link

By default, the preview link is styled as an underlined link. Use the `button()` method to style it as a Filament button.

Use one of the following methods to adjust the horizontal alignment:

- `alignLeft()`
- `alignCenter()`
- `alignRight()`

Use the `extraAttributes()` method to add any extra HTML attributes.

## Automatically Updating the Builder Preview (Experimental)

By default, the Builder Editor is not reactive. Updating the fields won't automatically refresh the preview iframe. You can enable the `showAutoRefreshToggle` in the [configuration](./configuration.md). This will add a checkbox in the header of the Editor panel in the preview modal. Activating this checkbox will make all fields in the Editor behave as `lazy()`. The preview modal will be refreshed automatically each time the focus is taken out of a field (clicking away).

**Note**: Options marked as experimental may break in future releases.

---

**Documentation**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Field Previews](./builder-field-previews.md)
