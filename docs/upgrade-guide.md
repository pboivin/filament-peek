# Upgrading from Peek 3.x

> If you see anything missing from this guide, please do not hesitate to [send me a pull request](https://github.com/pboivin/filament-peek/edit/4.x/docs/upgrade-guide.md). Any help is appreciated!

On the surface, not much has changed in Peek from v3 to v4. The focus was entirely on Filament v5 compatibility. Here are the steps to follow when upgrading:

#### 1. Upgrade Filament

Make sure to follow the [Upgrade Guide from Filament](https://filamentphp.com/docs/5.x/upgrade-guide). It's essential to get your app ready for Filament v5 before upgrading the plugin.

#### 2. Upgrade Peek

```
composer require pboivin/filament-peek:"^4.0"
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

#### 6. If you are using Laravel 13 +

You may need to add your previewable models to the `serializable_classes` array in `config/cache.php` for previews to function correctly:

```php
    'serializable_classes' => [
        App\Models\Page::class,
        App\Models\Post::class,
    ],
```

You can find more information on `serializable_classes` in the [Laravel 13 Upgrade Guide](https://laravel.com/docs/13.x/upgrade#cache-serializable_classes-configuration).

---

**Documentation**

<!-- BEGIN_TOC -->

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
- [Upgrading from Peek 3.x](./upgrade-guide.md)

<!-- END_TOC -->
