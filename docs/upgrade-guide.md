# Upgrading from Peek 2.x

> If you see anything missing from this guide, please do not hesitate to [send me a pull request](https://github.com/pboivin/filament-peek/edit/3.x/docs/upgrade-guide.md). Any help is appreciated!

On the surface, not much has changed in Peek from v2 to v3. There were changes to internal classes and methods that shouldn't impact most users, Filament v4 compatibility, and the deprecation of Builder Previews. Here are the steps to follow when upgrading:

#### 1. Upgrade Filament

Make sure to follow the [Upgrade Guide from Filament](https://filamentphp.com/docs/4.x/upgrade-guide). It's essential to get your app ready for Filament v4 before upgrading the plugin.

#### 2. Upgrade Peek

```
composer require pboivin/filament-peek:"^3.0"
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

---

**Documentation**

<!-- BEGIN_TOC -->

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews (deprecated)](./builder-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
- [Upgrading from v1.x](./upgrade-guide.md)

<!-- END_TOC -->
