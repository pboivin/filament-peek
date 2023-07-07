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
| `showActiveDevicePreset` | `bool` | Highlight the active device preset with a small circle under the icon. |
| `allowIframeOverflow` | `bool` | Allow the iframe dimensions to go beyond the capacity of the available preview modal area. |
| `allowIframePointerEvents` | `bool` | Allow all pointer events within the iframe. By default, only scrolling is allowed. (See [Pointer Events](./page-previews.md#preview-pointer-events)) |
| `closeModalWithEscapeKey` | `bool` | Close the preview modal by pressing the Escape key. |
| `builderEditor` | `array` | Options related to the Editor sidebar in Builder Previews. |

Builder Editor options:

| Name | Type | Description |
|---|---|---|
| `canDiscardChanges` | `bool` | Show 'Accept' and 'Discard' buttons in modal header instead of a single 'Close' button. |
| `canResizeSidebar` | `bool` | Allow users to resize the sidebar by clicking and dragging on the right edge. |
| `sidebarMinWidth` | `string` | Minimum width for the sidebar (if resizable). Must be a valid CSS `width` value. |
| `sidebarInitialWidth` | `string` | Initial width for the sidebar. Must be a valid CSS `width` value. |
| `livewireComponentClass` | `string` | Livewire component class for the Builder Editor sidebar. |
| `experimental.showAutoRefreshToggle` | `bool` | Experimental - Enable the auto-refresh option for the Builder Editor. |
| `experimental.autoRefreshDebounceMilliseconds` | `int` | Experimental - Debounce time before triggering the auto-refresh when a field loses focus. |
| `experimental.restoreIframePositionOnRefresh` | `bool` | Experimental - Restore iframe scroll position after refreshing the preview modal. |

**Note**: Options marked as experimental may break in future releases.

---

**Documentation**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews](./builder-previews.md)
