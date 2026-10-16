{{-- BACK TO TOP BUTTON --}}
<button id="backToTop" class="back-to-top" aria-label="Kembali ke atas" title="Kembali ke atas">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
    </svg>
</button>

<style>
/* ==================== BACK TO TOP BUTTON (TEMA EMAS) ==================== */
.back-to-top {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d4af37 0%, #b8860b 50%, #d4af37 100%);
    background-size: 200% 200%;
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.25);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 
        0 4px 15px rgba(212, 175, 55, 0.45),
        0 0 0 0 rgba(212, 175, 55, 0.4);
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px) scale(0.9);
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 999;
    animation: shimmer 3s ease-in-out infinite;
}

.back-to-top.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.back-to-top:hover {
    transform: translateY(-4px) scale(1.08);
    box-shadow: 
        0 8px 25px rgba(212, 175, 55, 0.65),
        0 0 0 6px rgba(212, 175, 55, 0.15);
    background: linear-gradient(135deg, #f5d76e 0%, #d4af37 50%, #b8860b 100%);
}

.back-to-top:active {
    transform: translateY(-2px) scale(1.02);
}

.back-to-top svg {
    width: 22px;
    height: 22px;
    stroke: #fff;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
    animation: bounce-up 2s infinite;
}

@keyframes shimmer {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes bounce-up {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-3px); }
}

html {
    scroll-behavior: smooth;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTop');
    if (!backToTopBtn) return;

    const scrollThreshold = 400;

    window.addEventListener('scroll', function() {
        if (window.scrollY > scrollThreshold) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });

    backToTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>