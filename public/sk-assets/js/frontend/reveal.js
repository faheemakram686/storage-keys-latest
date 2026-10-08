(function () {
    'use strict';

    function showAll() {
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) {
            el.classList.add('in');
        });
    }

    function revealIfVisible(el, observer) {
        var rect = el.getBoundingClientRect();
        if (rect.bottom > 0 && rect.top < window.innerHeight) {
            el.classList.add('in');
            if (observer) observer.unobserve(el);
        }
    }

    function init() {
        try {
            var reveals = [].slice.call(document.querySelectorAll('.sk-reveal'));
            if (!reveals.length) return;

            var reduceMotion = window.matchMedia &&
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!reduceMotion && 'IntersectionObserver' in window) {
                // threshold 0: a tall block (one blog article) can never reach
                // 12% of its own height inside the viewport, so it stayed at
                // opacity 0 until a scroll happened to cross that ratio.
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0 });

                reveals.forEach(function (el) { observer.observe(el); });
                reveals.forEach(function (el) { revealIfVisible(el, observer); });
            } else {
                showAll();
            }
        } catch (e) {
            showAll();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
