# Configuration

## Config File

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-peek-config"
```

This will add a `config/filament-peek.php` file to your project.

## Options

Here are the main options you can configure:

| Name | Type | Description |
|---|---|---|
| `devicePresets` | `array\|false` | Quickly resize the preview iframe to specific dimensions. |
| `initialDevicePreset` | `string` | Default device preset to be activated when the preview modal is open. |
| `showActiveDevicePreset` | `bool` | Highlight the active device preset with a small circle under the icon. |
| `allowIframeOverflow` | `bool` | Allow the iframe dimensions to go beyond the capacity of the available preview modal area. |
| `allowIframePointerEvents` | `bool` | Allow all pointer events within the iframe. By default, only scrolling is allowed. (See [Pointer Events](#pointer-events)) |
| `closeModalWithEscapeKey` | `bool` | Close the preview modal by pressing the Escape key. |

---

**Contents**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Field Previews](./builder-field-previews.md)
