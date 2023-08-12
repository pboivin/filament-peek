# Upgrading from v1.x

> If you see anything missing from this guide, please do not hesitate to [send me a pull request](https://github.com/pboivin/filament-peek/edit/2.x/docs/upgrade-guide.md). Any help is appreciated!

Not much has changed in Peek from v1 to v2, aside from basic Filament v3 and Livewire v3 compatibility. Here are the steps to follow when upgrading:

#### 1. Upgrade Filament

Make sure to follow the [Upgrade Guide from Filament](https://filamentphp.com/docs/3.x/panels/upgrade-guide). It's essential to get your app ready for Filament v3 and Livewire v3 before upgrading the plugin.

#### 2. Upgrade Peek

```
composer require pboivin/filament-peek:"^2.0"
```

#### 3. Register the plugin in your Panel

In your `AdminPanelProvider`, register the `FilamentPeekPlugin` class:

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

#### 4. Publish the assets

```
php artisan filament:assets
```

#### 5. Clear your view cache

```
php artisan view:clear
```

#### 6. (Optional) Republish the configuration

This is only needed if you have published the config file before:

```
php artisan vendor:publish --force --tag=filament-peek-config
```

#### 7. (Optional) Replace `PreviewLink` with `InlinePreviewAction`

The `PreviewLink` component has been deprecated. Consider switching to the more powerful and customizable [`InlinePreviewAction`](./page-previews.md#embedding-a-preview-action-into-the-form) component:

```diff
-use Pboivin\FilamentPeek\Forms\Components\PreviewLink;
+use Filament\Forms\Components\Actions;
+use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;

public static function form(Form $form): Form
{
    return $form->schema(['
-        PreviewLink::make(),
+        Actions::make([
+            InlinePreviewAction::make(),
+        ]),

        // ...
    ]);
}
```

You can find more information on Form Actions in the [Filament Documentation](https://filamentphp.com/docs/3.x/forms/actions).

---

**Documentation**

<!-- BEGIN_TOC -->

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews](./builder-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
- [Upgrading from v1.x](./upgrade-guide.md)

<!-- END_TOC -->
