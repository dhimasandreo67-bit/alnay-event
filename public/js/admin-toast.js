(function () {
    'use strict';

    // Fungsi untuk membuat/get container toast
    function getToastContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
            console.log('Toast container created');
        }
        return container;
    }

    // Icon untuk setiap tipe toast
    const icons = {
        success: '✓',
        error: '✕',
        warning: '!',
        info: 'ℹ'
    };

    // Fungsi utama showToast
    window.showToast = function (message, type = 'success', duration = 4000) {
        console.log('showToast called:', message, type);
        
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
        console.log('Toast appended to container');

        // Close button handler
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            console.log('Toast closed manually');
            removeToast(toast);
        });

        // Auto remove setelah duration
        let timer = setTimeout(() => {
            console.log('Toast auto-removing');
            removeToast(toast);
        }, duration);

        // Pause on hover
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            const progress = toast.querySelector('.toast-progress');
            if (progress) progress.style.animationPlayState = 'paused';
        });

        toast.addEventListener('mouseleave', () => {
            const progress = toast.querySelector('.toast-progress');
            if (progress) progress.style.animationPlayState = 'running';
            timer = setTimeout(() => removeToast(toast), 2000);
        });
    };

    // Fungsi remove toast
    function removeToast(toast) {
        if (toast.classList.contains('toast-hiding')) return;
        
        toast.classList.add('toast-hiding');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
                console.log('Toast removed from DOM');
            }
        }, 300);
    }

    // Auto-detect Laravel flash messages
    function initFlashToast() {
        console.log('initFlashToast called');
        
        const flashData = document.getElementById('flash-data');
        if (!flashData) {
            console.log('No flash-data element found');
            return;
        }
        
        try {
            const data = JSON.parse(flashData.textContent);
            console.log('Flash data parsed:', data);
            
            if (data.success) {
                console.log('Showing success toast');
                window.showToast(data.success, 'success');
            }
            if (data.error) {
                console.log('Showing error toast');
                window.showToast(data.error, 'error');
            }
            if (data.warning) {
                console.log('Showing warning toast');
                window.showToast(data.warning, 'warning');
            }
            if (data.info) {
                console.log('Showing info toast');
                window.showToast(data.info, 'info');
            }
        } catch (e) {
            console.error('Failed to parse flash data:', e);
            console.error('Raw flash data:', flashData.textContent);
        }
    }

    // Jalankan saat DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing flash toast');
            setTimeout(initFlashToast, 100);
        });
    } else {
        console.log('DOM already ready, initializing flash toast');
        setTimeout(initFlashToast, 100);
    }
})();