(function () {
    'use strict';

    function getToastContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    const icons = {
        success: '✓',
        error: '✕',
        warning: '!',
        info: 'ℹ'
    };

    window.showToast = function (message, type = 'success', duration = 4000) {
        const container = getToastContainer();
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.info}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close" aria-label="Tutup">&times;</button>
            <div class="toast-progress"></div>
        `;

        container.appendChild(toast);

        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => removeToast(toast));

        const timer = setTimeout(() => removeToast(toast), duration);

        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            const progress = toast.querySelector('.toast-progress');
            if (progress) progress.style.animationPlayState = 'paused';
        });

        toast.addEventListener('mouseleave', () => {
            const progress = toast.querySelector('.toast-progress');
            if (progress) progress.style.animationPlayState = 'running';
            setTimeout(() => removeToast(toast), 2000);
        });
    };

    function removeToast(toast) {
        if (toast.classList.contains('toast-hiding')) return;
        toast.classList.add('toast-hiding');
        setTimeout(() => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 300);
    }

    // Auto-detect Laravel flash messages
    document.addEventListener('DOMContentLoaded', function () {
        const flashData = document.getElementById('flash-data');
        if (flashData) {
            try {
                const data = JSON.parse(flashData.textContent);
                if (data.success) window.showToast(data.success, 'success');
                if (data.error) window.showToast(data.error, 'error');
                if (data.warning) window.showToast(data.warning, 'warning');
                if (data.info) window.showToast(data.info, 'info');
            } catch (e) {
                console.warn('Failed to parse flash data:', e);
            }
        }
    });
})();