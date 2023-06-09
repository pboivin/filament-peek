document.addEventListener('alpine:init', () => {
    Alpine.data('PeekPreviewModal', (config) => ({
        config,

        isOpen: false,

        canRotatePreset: false,

        activeDevicePreset: null,

        modalTitle: null,

        modalStyle: {
            display: 'none',
        },

        iframeUrl: null,

        iframeContent: null,

        iframeStyle: {
            width: '100%',
            height: '100%',
            maxWidth: '100%',
            maxHeight: '100%',
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

            this.modalTitle = $event.detail.modalTitle;
            this.iframeUrl = $event.detail.iframeUrl;
            this.iframeContent = $event.detail.iframeContent;
            this.modalStyle.display = 'flex';
            this.isOpen = true;

            setTimeout(() => {
                const iframe = this.$refs.previewModalBody.querySelector('iframe');

                if (!(iframe && iframe.contentWindow)) return;

                iframe.contentWindow.addEventListener('keyup', (e) => {
                    if (e.key === 'Escape') this.handleEscapeKey();
                });
            }, 500);
        },

        onClosePreviewModal() {
            document.body.classList.remove('is-filament-peek-preview-modal-open');

            this.modalStyle.display = 'none';
            this.modalTitle = null;
            this.iframeUrl = null;
            this.iframeContent = null;
            this.isOpen = false;
        },

        handleEscapeKey() {
            if (!this.isOpen) return;

            if (!this.config.shouldCloseModalWithEscapeKey) return;

            this.onClosePreviewModal();
        },
    }));
});
