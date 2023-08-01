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
| `allowIframePointerEvents` | `bool` | Allow all pointer events within the iframe. By default, only scrolling is allowed. (See [Pointer Events](./page-previews.md#preview-pointer-events)) |
| `closeModalWithEscapeKey` | `bool` | Close the preview modal by pressing the Escape key. Does not apply to Builder previews. |
| `builderEditor` | `array` | Options related to the Editor sidebar in [Builder Previews](./builder-previews.md). |

Builder Editor options:

| Name | Type | Description |
|---|---|---|
| `canDiscardChanges` | `bool` | Show 'Accept' and 'Discard' buttons in modal header instead of a single 'Close' button. |
| `canResizeSidebar` | `bool` | Allow users to resize the sidebar by clicking and dragging on the right edge. |
| `sidebarMinWidth` | `string` | Minimum width for the sidebar, if resizable. Must be a valid CSS `width` value. |
| `sidebarInitialWidth` | `string` | Initial width for the sidebar. Must be a valid CSS `width` value. |
| `preservePreviewScrollPosition` | `bool` | Restore the preview iframe scroll position when the preview is refreshed. |
| `canEnableAutoRefresh` | `bool` | Enable the auto-refresh option for the Builder Editor. |
| `autoRefreshDebounceMilliseconds` | `int` | Debounce time before refreshing the preview. |
| `autoRefreshStrategy` | `string` | Possible values: `simple` or `reactive`. (See [Automatically Updating the Builder Preview](./builder-previews.md#preview-auto-refresh)) |
| `livewireComponentClass` | `string` | Livewire component class for the Builder Editor sidebar. |

---

**Documentation**

<!-- BEGIN_TOC -->

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews](./builder-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
- [Upgrading from v1.x](./upgrade-guide.md)

<!-- END_TOC -->
