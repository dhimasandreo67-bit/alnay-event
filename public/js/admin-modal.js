/**
 * Custom Modal System - ALNAY.ID Admin
 * Menggantikan alert(), confirm(), dan prompt() browser
 */

(function () {
    'use strict';

    // Simpan referensi fungsi asli
    const originalAlert = window.alert;
    const originalConfirm = window.confirm;
    const originalPrompt = window.prompt;

    /**
     * Buat overlay modal
     */
    function createOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.innerHTML = `
            <div class="modal-container">
                <div class="modal-header">
                    <div class="modal-icon"></div>
                    <h3 class="modal-title"></h3>
                    <p class="modal-message"></p>
                </div>
                <div class="modal-footer">
                    <button class="modal-btn modal-btn-secondary" data-action="cancel">Batal</button>
                    <button class="modal-btn modal-btn-primary" data-action="confirm">OK</button>
                </div>
            </div>
        `;
        return overlay;
    }

    /**
     * Tampilkan modal custom
     */
    function showModal(options = {}) {
        const {
            type = 'info', // info, warning, success, error
            title = 'Konfirmasi',
            message = '',
            confirmText = 'OK',
            cancelText = 'Batal',
            showCancel = true,
            onConfirm = () => { },
            onCancel = () => { }
        } = options;

        const overlay = createOverlay();
        const container = overlay.querySelector('.modal-container');
        const icon = overlay.querySelector('.modal-icon');
        const titleEl = overlay.querySelector('.modal-title');
        const messageEl = overlay.querySelector('.modal-message');
        const confirmBtn = overlay.querySelector('[data-action="confirm"]');
        const cancelBtn = overlay.querySelector('[data-action="cancel"]');

        // Set icon
        const icons = {
            warning: '⚠️',
            success: '✓',
            error: '✕',
            info: 'ℹ️'
        };
        icon.className = `modal-icon ${type}`;
        icon.textContent = icons[type] || icons.info;

        // Set content
        titleEl.textContent = title;
        messageEl.textContent = message;
        confirmBtn.textContent = confirmText;
        cancelBtn.textContent = cancelText;

        // Set button visibility
        if (!showCancel) {
            cancelBtn.style.display = 'none';
        }

        // Set button styles based on type
        if (type === 'error' || type === 'warning') {
            confirmBtn.className = 'modal-btn modal-btn-danger';
        } else if (type === 'success') {
            confirmBtn.className = 'modal-btn modal-btn-success';
        } else {
            confirmBtn.className = 'modal-btn modal-btn-primary';
        }

        // Event handlers
        function closeModal() {
            overlay.classList.remove('active');
            setTimeout(() => {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, 300);
        }

        confirmBtn.addEventListener('click', () => {
            closeModal();
            onConfirm();
        });

        if (showCancel) {
            cancelBtn.addEventListener('click', () => {
                closeModal();
                onCancel();
            });
        }

        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay && showCancel) {
                closeModal();
                onCancel();
            }
        });

        // Close on ESC key
        function handleEsc(e) {
            if (e.key === 'Escape' && showCancel) {
                closeModal();
                onCancel();
                document.removeEventListener('keydown', handleEsc);
            }
        }

        // Add to DOM
        document.body.appendChild(overlay);
        document.addEventListener('keydown', handleEsc);

        // Trigger animation
        requestAnimationFrame(() => {
            overlay.classList.add('active');
        });
    }

    /**
     * Override window.alert
     */
    window.alert = function (message, title = 'Perhatian') {
        showModal({
            type: 'info',
            title: title,
            message: message,
            showCancel: false,
            confirmText: 'OK'
        });
    };

    /**
     * Override window.confirm
     */
    window.confirm = function (message, title = 'Konfirmasi') {
        return new Promise((resolve) => {
            showModal({
                type: 'warning',
                title: title,
                message: message,
                showCancel: true,
                confirmText: 'Ya',
                cancelText: 'Batal',
                onConfirm: () => resolve(true),
                onCancel: () => resolve(false)
            });
        });
    };

    /**
     * Custom success modal
     */
    window.showSuccess = function (message, title = 'Berhasil') {
        showModal({
            type: 'success',
            title: title,
            message: message,
            showCancel: false,
            confirmText: 'Tutup'
        });
    };

    /**
     * Custom error modal
     */
    window.showError = function (message, title = 'Error') {
        showModal({
            type: 'error',
            title: title,
            message: message,
            showCancel: false,
            confirmText: 'OK'
        });
    };

    /**
     * Custom warning modal
     */
    window.showWarning = function (message, title = 'Peringatan') {
        return new Promise((resolve) => {
            showModal({
                type: 'warning',
                title: title,
                message: message,
                showCancel: true,
                confirmText: 'Ya',
                cancelText: 'Batal',
                onConfirm: () => resolve(true),
                onCancel: () => resolve(false)
            });
        });
    };

    /**
     * Shake animation untuk modal (untuk error validation)
     */
    window.shakeModal = function () {
        const modal = document.querySelector('.modal-container');
        if (modal) {
            modal.classList.remove('modal-shake');
            // Trigger reflow
            void modal.offsetWidth;
            modal.classList.add('modal-shake');
        }
    };

})();