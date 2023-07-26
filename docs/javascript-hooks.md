# JavaScript Hooks

JavaScript hooks allow you to run custom code at specific points in the preview modal lifecycle:

| Event | Description |
|---|---|
| `peek:initializing` | Runs once, before the plugin is initialized. |
| `peek:initialized` | Runs once, after the plugin is initialized. |
| `peek:modal-initializing` | Runs once, before the Alpine component is initialized. |
| `peek:modal-initialized` | Runs once, after the Alpine component is initialized. |
| `peek:modal-opening` | Runs every time, before the preview modal opens. |
| `peek:modal-opened` | Runs every time, after the preview modal opens. |
| `peek:modal-closing` | Runs every time, before the preview modal closes. |
| `peek:modal-closed` | Runs every time, after the preview modal closes. |

Example:

```js
document.addEventListener('peek:modal-closing', (e) => {
    console.log('The modal is closing...');

    // You can access the full Alpine instance in `e.details.modal`
    if (e.details.modal.withEditor) {
        console.log('I hope you enjoyed using the new Builder Preview!');
    }
});
```

Have a look at the [Including frontend assets](https://filamentphp.com/docs/2.x/admin/appearance#including-frontend-assets) section of the Filament documentation to learn how to load custom JavaScript files into you admin pages.

---

**Documentation**

- [Configuration](./configuration.md)
- [Page Previews](./page-previews.md)
- [Builder Previews](./builder-previews.md)
- [JavaScript Hooks](./javascript-hooks.md)
