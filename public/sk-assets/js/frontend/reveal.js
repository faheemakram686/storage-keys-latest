(function () {
    'use strict';

    function showAll() {
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) {
            el.classList.add('in');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        try {
            var reveals = [].slice.call(document.querySelectorAll('.sk-reveal'));
            if (!reveals.length) return;

            var reduceMotion = window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!reduceMotion && 'IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12 });

                reveals.forEach(function (el) { observer.observe(el); });
            } else {
                showAll();
            }
        } catch (e) {
            showAll();
        }
    });
})();
