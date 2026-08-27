/* Landing — facilities gallery carousel (#skGalleryCarousel) */
(function () {
    'use strict';

    var root = document.getElementById('skGalleryCarousel');
    if (!root) return;

    var track = root.querySelector('.sk-gal-track');
    var slides = [].slice.call(root.querySelectorAll('.sk-gal-slide'));
    var prev = root.querySelector('.sk-gal-prev');
    var next = root.querySelector('.sk-gal-next');
    var dotsWrap = root.querySelector('.sk-gal-dots');
    if (!track || !slides.length) return;

    var index = 0;
    var timer = null;
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function perView() {
        var w = window.innerWidth || document.documentElement.clientWidth;
        if (w <= 575) return 1;
        if (w <= 991) return 2;
        return 4;
    }

    function maxIndex() {
        return Math.max(0, slides.length - perView());
    }

    function buildDots() {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        var pages = maxIndex() + 1;
        for (var i = 0; i < pages; i++) {
            var b = document.createElement('button');
            b.type = 'button';
            b.setAttribute('aria-label', 'Go to slide group ' + (i + 1));
            b.setAttribute('aria-selected', i === index ? 'true' : 'false');
            if (i === index) b.classList.add('is-active');
            (function (page) {
                b.addEventListener('click', function () {
                    goTo(page);
                    restart();
                });
            })(i);
            dotsWrap.appendChild(b);
        }
    }

    function paintDots() {
        if (!dotsWrap) return;
        [].slice.call(dotsWrap.querySelectorAll('button')).forEach(function (b, i) {
            var on = i === index;
            b.classList.toggle('is-active', on);
            b.setAttribute('aria-selected', on ? 'true' : 'false');
        });
    }

    function goTo(i) {
        index = Math.max(0, Math.min(i, maxIndex()));
        var gap = parseFloat(getComputedStyle(root).getPropertyValue('--sk-gal-gap')) || 16;
        var slideW = slides[0].getBoundingClientRect().width;
        track.style.transform = 'translate3d(' + (-index * (slideW + gap)) + 'px,0,0)';
        paintDots();
    }

    function nextSlide() {
        goTo(index >= maxIndex() ? 0 : index + 1);
    }

    function prevSlide() {
        goTo(index <= 0 ? maxIndex() : index - 1);
    }

    function stop() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    function start() {
        stop();
        if (reduced || slides.length <= perView()) return;
        timer = setInterval(nextSlide, 4200);
    }

    function restart() {
        stop();
        start();
    }

    if (prev) prev.addEventListener('click', function () { prevSlide(); restart(); });
    if (next) next.addEventListener('click', function () { nextSlide(); restart(); });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin', stop);
    root.addEventListener('focusout', start);

    var touchX = null;
    track.addEventListener('touchstart', function (e) {
        touchX = e.changedTouches[0].clientX;
        stop();
    }, { passive: true });
    track.addEventListener('touchend', function (e) {
        if (touchX == null) return;
        var dx = e.changedTouches[0].clientX - touchX;
        touchX = null;
        if (Math.abs(dx) > 40) {
            if (dx < 0) nextSlide();
            else prevSlide();
        }
        start();
    }, { passive: true });

    var resizeTimer = null;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            buildDots();
            goTo(Math.min(index, maxIndex()));
            restart();
        }, 120);
    });

    buildDots();
    goTo(0);
    start();
})();
