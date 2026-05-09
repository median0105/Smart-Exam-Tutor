import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const scrollToTopBtn = document.getElementById('scrollToTopBtn');

if (scrollToTopBtn) {
    const toggleScrollButton = () => {
        const shouldShow = window.scrollY > 220;

        scrollToTopBtn.classList.toggle('opacity-0', !shouldShow);
        scrollToTopBtn.classList.toggle('pointer-events-none', !shouldShow);
        scrollToTopBtn.classList.toggle('opacity-100', shouldShow);
    };

    toggleScrollButton();
    window.addEventListener('scroll', toggleScrollButton, { passive: true });

    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
