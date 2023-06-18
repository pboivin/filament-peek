document.addEventListener('alpine:init', () => {
    Alpine.data('PeekPreviewModal', (config) => ({
        config,
        isOpen: false,
        withEditor: false,
        canRotatePreset: false,
        activeDevicePreset: null,
        editorTitle: null,
        modalTitle: null,
        iframeUrl: null,
        iframeContent: null,
        modalStyle: {
            display: 'none',
        },
        iframeStyle: {
            width: '100%',
            height: '100%',
            maxWidth: '100%',
            maxHeight: '100%',
        },
        editorStyle: {
            display: 'none',
        },

        init() {
            this.setDevicePreset();
        },

        setIframeDimensions(width, height) {
            this.iframeStyle.maxWidth = width;
            this.iframeStyle.maxHeight = height;

            if (this.config.allowIframeOverflow) {
                this.iframeStyle.width = width;
                this.iframeStyle.height = height;
            }
        },

        setDevicePreset(name) {
            name = name || this.config.initialDevicePreset;

            if (!this.config.devicePresets) return;

            if (!this.config.devicePresets[name]) return;

            if (!this.config.devicePresets[name].width) return;

            if (!this.config.devicePresets[name].height) return;

            this.setIframeDimensions(this.config.devicePresets[name].width, this.config.devicePresets[name].height);

            this.canRotatePreset = this.config.devicePresets[name].canRotatePreset || false;

            this.activeDevicePreset = name;
        },

        isActiveDevicePreset(name) {
            if (!this.config.shouldShowActiveDevicePreset) {
                return false;
            }

            return this.activeDevicePreset === name;
        },

        rotateDevicePreset() {
            const newMaxWidth = this.iframeStyle.maxHeight;
            const newMaxHeight = this.iframeStyle.maxWidth;

            this.setIframeDimensions(newMaxWidth, newMaxHeight);
        },

        onOpenPreviewModal($event) {
            document.body.classList.add('is-filament-peek-preview-modal-open');

            this.withEditor = !!$event.detail.withEditor;
            this.editorTitle = $event.detail.editorTitle;
            this.editorStyle.display = this.withEditor ? 'flex' : 'none';
            this.modalTitle = $event.detail.modalTitle;
            this.iframeUrl = $event.detail.iframeUrl;
            this.iframeContent = $event.detail.iframeContent;
            this.modalStyle.display = 'flex';
            this.isOpen = true;

            setTimeout(() => {
                const firstInput = this.$el.querySelector('.filament-peek-builder-editor input');
                firstInput && firstInput.focus();
            }, 0);

            setTimeout(() => {
                const iframe = this.$refs.previewModalBody.querySelector('iframe');

                if (!(iframe && iframe.contentWindow)) return;

                iframe.contentWindow.addEventListener('keyup', (e) => {
                    if (e.key === 'Escape') this.handleEscapeKey();
                });
            }, 500);
        },

        onRefreshPreviewModal($event) {
            this.iframeUrl = $event.detail.iframeUrl;
            this.iframeContent = $event.detail.iframeContent;
        },

        onClosePreviewModal($event) {
            setTimeout(() => this._onClosePreviewModalInner(), $event?.detail?.delay ? 250 : 0);
        },

        _onClosePreviewModalInner() {
            document.body.classList.remove('is-filament-peek-preview-modal-open');

            this.withEditor = false;
            this.editorStyle.display = 'none';
            this.editorTitle = null;
            this.modalStyle.display = 'none';
            this.modalTitle = null;
            this.iframeUrl = null;
            this.iframeContent = null;
            this.isOpen = false;
        },

        onEditorFocusOut($event) {
            if (!this.editorShouldAutoRefresh()) return;

            const autorefreshTags = [
                'input',
                'select',
                'textarea',
                'trix-editor',
            ];

            if (autorefreshTags.includes($event.target.tagName.toLowerCase())) {
                Livewire.emit('refreshBuilderPreview');
            }
        },

        editorShouldAutoRefresh() {
            if (!this.withEditor) return;
            if (!this.$refs.builderEditor) return;

            return !!this.$refs.builderEditor.dataset.shouldAutoRefresh;
        },

        handleEscapeKey() {
            if (!this.isOpen) return;
            if (!this.config.shouldCloseModalWithEscapeKey) return;
            if (this.withEditor) return;

            this.onClosePreviewModal();
        },

        dispatchCloseModalEvent() {
            if (this.withEditor) {
                Livewire.emit('closeBuilderEditor');
            } else {
                this.$dispatch('close-preview-modal');
            }
        },
    }));
});
