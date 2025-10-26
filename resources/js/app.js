import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Optimizaciones de Alpine
Alpine.plugin((Alpine) => {
    Alpine.directive('lazyload', (el, { expression }, { evaluateLater, cleanup }) => {
        let getThunk = evaluateLater(expression);
        
        let observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    getThunk(() => {});
                    observer.unobserve(el);
                }
            });
        });
        
        observer.observe(el);
        
        cleanup(() => observer.disconnect());
    });
});

Alpine.start();
