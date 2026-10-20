import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

function initPageTransitions() {
    const supportsViewTransitions = 'startViewTransition' in document;

    if (!supportsViewTransitions) {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (link.target === '_blank' || link.hasAttribute('download')) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) return;

            try {
                const url = new URL(link.href, window.location.origin);
                if (url.origin !== window.location.origin) return;
            } catch (err) { return; }

            const currentPath = window.location.pathname;
            const targetPath = new URL(link.href, window.location.origin).pathname;

            let exitClass = 'page-exiting-fade';
            if (currentPath === '/login' && targetPath === '/register') {
                exitClass = 'page-exiting-slide-left';
            } else if (currentPath === '/register' && targetPath === '/login') {
                exitClass = 'page-exiting-slide-right';
            }

            e.preventDefault();
            document.body.classList.add('page-exiting', exitClass);

            setTimeout(() => { window.location.href = link.href; }, 250);
        });

        document.body.classList.add('page-enter');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageTransitions);
} else {
    initPageTransitions();
}