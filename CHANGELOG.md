# Changelog

All notable changes to `filament-peek` will be documented in this file.


## 2.0.1 - 2023-09-16

* fix: Ensure builder field is remembered in InlinePreviewAction
* chore: Rework integration tests
* chore: Update run-tests workflow
* chore: Bump actions/checkout


## 2.0.0 - 2023-08-31

Stable release


## 2.0.0-beta4 - 2023-08-26

* fix: Don't attach keyup listener if not needed
* fix: Try/catch iframe operations


## 2.0.0-beta3 - 2023-08-25

* enh: Replace assets config with plugin methods
* docs: Add documentation on custom theme integration


## 2.0.0-beta2 - 2023-08-12

* feat: Add InlinePreviewAction (Deprecate PreviewLink)
* fix: Add missing modal tag
* fix: Dark mode styles
* chore: Update illuminate/contracts requirement


## 2.0.0-beta1 - 2023-07-31

* fix: Refresh on render if needed


## 2.0.0-alpha1 - 2023-07-30

* feat!: Initial support for Filament 3


## 1.1.1 - 2023-08-26

* fix: Don't attach keyup listener if not needed
* fix: Try/catch iframe operations


## 1.1.0 - 2023-07-26

* feat: Add preview modal JavaScript hooks


## 1.0.2 - 2023-07-23

* fix: Editor sidebar resize in RTL UI


## 1.0.1 - 2023-07-18

* enh: Add Arabic translation by @atmonshi


## 1.0.0 - 2023-07-16

* enh: Default canDiscardChanges to true
* fix: Fill builder editor data


## 1.0.0-beta2 - 2023-07-15

* refactor: Always show active preset
* refactor: Updade config options
* fix: Prevent crash in resetBuilderEditor
* fix: Validate form before preview
* fix: Validate builder editor before closing preview
* test: Add BuilderEditorTest


## 1.0.0-beta1 - 2023-07-09
* feat: Add 'reactive' auto refresh strategy
* feat: Add option to restore iframe scroll position on refresh
* enh: Update getListeners method
* enh: Add check for missing custom event listener
* enh: Accept single Component as builder schema
* enh: Throw custom exception if page is not properly configured
* enh: Refresh on submit
* enh: Improve Tiptap support
* fix: Builder editor improvements
* fix: Pass raw editor data to mutateBuilderPreviewData
* fix: Update builder field if empty
* fix: Improve close modal handling
* chore: Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0


## 1.0.0-alpha2 - 2023-07-04

* refactor!: Update various method names
* feat: Implement sidebar resize
* feat: Support custom focus out handlers
* enh: Detect if editor has sidebar actions
* enh: Update PreviewLink default styles
* fix: Support preview modal on View pages


## 1.0.0-alpha1 - 2023-06-23

* feat: Builder Previews
* docs: Builder Previews Documentation
* enh: Add type annotations and tag internal methods


## 0.3.1 - 2023-06-26

* fix: Support preview modal on View pages


## 0.3.0 - 2023-06-11

* feat: Show active device preset
* feat: Add closeModalWithEscapeKey config
* refactor: Extract alpine component and version dist assets


## 0.2.4 - 2023-06-10

* fix: Handle escape key within preview modal iframe
* enh: Update preview modal pointer-events CSS selector


## 0.2.3 - 2023-06-05

* fix: Replace iframe pointer-events CSS with preview modal content style block


## 0.2.2 - 2023-05-30

- enh: Extract renderPreviewModalView method
- chore: Bump dependabot/fetch-metadata from 1.5.0 to 1.5.1
- chore: Bump aglipanci/laravel-pint-action from 2.2.0 to 2.3.0


## 0.2.1 - 2023-05-28

- fix: Remove duplicated call to getPreviewModalUrl
- test: Add HasPreviewModal tests


## 0.2.0 - 2023-05-26

- feat: Add pointer events config
- feat: Add focus trap and handle escape key
- fix: Handle preset rotation when using allowIframeOverflow config
- enh: Change PreviewLink into form component


## 0.1.2 - 2023-05-22

- fix: Support preview modal on List and Create pages


## 0.1.1 - 2023-05-22

- fix: Remove unused method
- chore: Bump dependabot/fetch-metadata from 1.4.0 to 1.5.0


## 0.1.0 - 2023-05-22

- Initial release
