# Builder Field Previews

![Screenshot of the Builder preview modal and editor](../art/03-builder-preview.jpg)

## Overview

- A preview link in your form opens a full-screen modal for a specific Builder field.
- The modal contains an editor on the left with a copy of your Builder field, and an iframe on the right that will render the preview.
- The iframe can either render a full Blade view or a custom URL.
- As you edit the fields, the preview can be refreshed manually or automatically (auto-refresh is considered experimental for now).
- When the modal is closed, the Builder field in the main form is updated with the changes from the Builder field preview.
- Closing the preview modal does not update the record in the database, only the form state is updated.

## Using the Builder Preview with Blade Views

In your `EditRecord` page:

- Add both `HasPreviewModal` and `HasBuilderPreview` traits.
- Override the `getBuilderEditorPreviewView()` method to define your Blade view.
- Override the `getBuilderEditorSchema()` method to define your Builder field schema. This can be extracted to a static method on your resource class for reuse.

In your `Resource` class:

- Add the `PreviewLink` component to your form, above or below the Builder field.

**Note**: Builder previews can also be used on `CreateRecord` pages.

#### Complete Example

`app/Filament/Resources/PageResource/Pages/EditPage.php`

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
        return match ($builderName) {
            'main_blocks' => 'pages.main-preview',
            'footer_blocks' => 'pages.footer-preview',
        };
    }

    public static function getBuilderEditorSchema(string $builderName): array
    {
        return [
            match ($builderName) {
                'main_blocks' => PageResource::mainBuilderField('preview'),
                'footer_blocks' => PageResource::footerBuilderField('preview'),
            }
        ];
    }
}
```

`app/Filament/Resources/PageResource.php`

```php
namespace App\Filament\Resources;

// ...
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;

class PageResource extends Resource
{
    // ...

    public static function mainBuilderField($context = null): Field
    {
        return Builder::make('main_blocks')->blocks([
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

    public static function footerBuilderField($context = null): Field
    {
        return Builder::make('footer_blocks')->blocks([
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
                ->label('Preview Main Blocks')
                ->builderPreview('main_blocks')
                ->columnSpanFull()
                ->alignRight(),

            self::mainBuilderField(),

            PreviewLink::make()
                ->label('Preview Footer')
                ->builderPreview('footer_blocks')
                ->columnSpanFull()
                ->alignRight(),

            self::footerBuilderField(),
        ]);
    }

    // ...
}
```

**Note**: If you're using custom event listeners on your page component, make sure to include the `updateBuilderEditorField` listener:

```php
    protected function getListeners(): array
    {
        return [
            'myCustomEventListener',
            'updateBuilderEditorField',
        ];
    }
```

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

**Contents**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Field Previews](./builder-field-previews.md)
