# Configuration

## Publishing the Config File

You can publish the full configuration with the following command:

```bash
php artisan vendor:publish --tag="filament-peek-config"
```

This will add a `config/filament-peek.php` file to your project.

## Available Options

Here are the main options you can configure:

| Name | Type | Description |
|---|---|---|
| `devicePresets` | `array\|false` | Quickly resize the preview iframe to specific dimensions. |
| `initialDevicePreset` | `string` | Default device preset to be activated when the preview modal is open. |
| `allowIframeOverflow` | `bool` | Allow the iframe dimensions to go beyond the capacity of the available preview modal area. |
| `allowIframePointerEvents` | `bool` | Allow all pointer events within the iframe. By default, only scrolling is allowed. (Does not apply when using a preview URL. See [Pointer Events](./page-previews.md#preview-pointer-events)) |
| `closeModalWithEscapeKey` | `bool` | Close the preview modal by pressing the Escape key. |
| `internalPreviewUrl` | `array` | Render Blade previews through an internal URL. |

## Integrating With a Custom Theme

With Filament, you can change the CSS used inside of a given Panel by compiling a custom stylesheet (a "theme"). With this approach, it's also possible to modify the internal stylesheet of the plugin for a seamless integration.

#### 1. Create your custom theme

Follow the instructions on the [Creating a custom theme](https://filamentphp.com/docs/4.x/styling/overview#creating-a-custom-theme) section of the Filament documentation.

#### 2. Disable the plugin's compiled stylesheet

In your `AdminPanelProvider`, call the `disablePluginStyles()` method on the plugin object:

```diff
 use Pboivin\FilamentPeek\FilamentPeekPlugin;

 public function panel(Panel $panel): Panel
 {
     return $panel
         // ...
         ->plugins([
             FilamentPeekPlugin::make()
+                ->disablePluginStyles(),
         ]);
 }
```

#### 3. Import the source stylesheet in your `theme.css`

**`resources/css/filament/admin/theme.css`**
```diff
 @import '../../../../vendor/filament/filament/resources/css/theme.css';

+@import '../../../../vendor/pboivin/filament-peek/resources/css/plugin.css';

 @config './tailwind.config.js';
```

#### 4. Include the plugin views in your theme's `tailwind.config.js`

**`resources/css/filament/admin/tailwind.config.js`**
```diff
 export default {
     presets: [preset],
     content: [
         './app/Filament/**/*.php',
         './resources/views/filament/**/*.blade.php',
         './vendor/filament/**/*.blade.php',
+        './vendor/pboivin/filament-peek/resources/**/*.blade.php',
     ]
 }
```

#### 5. Make sure to include the `nesting` plugin in your `postcss.config.js`

**`postcss.config.js`**
```diff
 module.exports = {
     plugins: {
+        'tailwindcss/nesting': {},
         tailwindcss: {},
         autoprefixer: {},
     },
 }
```

#### 6. Rebuild your theme

```
npm run build
```

---

**Documentation**

<!-- BEGIN_TOC -->

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews (deprecated)](./builder-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
- [Upgrading from Peek 2.x](./upgrade-guide.md)

<!-- END_TOC -->
