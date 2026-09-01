/* Landing home — reveal, counters, size finder, location tabs */
(function () {
    'use strict';

    try {
        var rm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var reveals = [].slice.call(document.querySelectorAll('.sk-reveal'));
        if (!rm && 'IntersectionObserver' in window) {
            var ro = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    if (en.isIntersecting) {
                        en.target.classList.add('in');
                        ro.unobserve(en.target);
                    }
                });
            }, { threshold: 0.12 });
            reveals.forEach(function (el) { ro.observe(el); });
        } else {
            reveals.forEach(function (el) { el.classList.add('in'); });
        }

        var counts = [].slice.call(document.querySelectorAll('.sk-count'));
        function run(el) {
            var target = parseFloat(el.getAttribute('data-target')) || 0;
            if (rm) { el.textContent = target.toLocaleString(); return; }
            var dur = 1500, t0 = null;
            function tick(ts) {
                if (t0 === null) t0 = ts;
                var p = Math.min((ts - t0) / dur, 1);
                var v = Math.floor(target * (1 - Math.pow(1 - p, 3)));
                el.textContent = v.toLocaleString();
                if (p < 1) requestAnimationFrame(tick); else el.textContent = target.toLocaleString();
            }
            requestAnimationFrame(tick);
        }
        if ('IntersectionObserver' in window) {
            var co = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    if (en.isIntersecting) { run(en.target); co.unobserve(en.target); }
                });
            }, { threshold: 0.5 });
            counts.forEach(function (el) { co.observe(el); });
        } else {
            counts.forEach(run);
        }
    } catch (e) {
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) { el.classList.add('in'); });
        [].slice.call(document.querySelectorAll('.sk-count')).forEach(function (el) { el.textContent = el.getAttribute('data-target'); });
    }
})();

(function () {
    var root = document.getElementById('skFinder');
    if (!root) return;

    var panels = [].slice.call(root.querySelectorAll('.sf-panel'));
    var result = root.querySelector('.sf-result');
    var stepNum = root.querySelector('.sf-stepnum');
    var progress = root.querySelector('.sf-progress');
    var backBtn = root.querySelector('.sf-back');
    var restartBtn = root.querySelector('.sf-restart');
    var recTitle = root.querySelector('.sf-rec-title');
    var recDesc = root.querySelector('.sf-rec-desc');
    var recBook = root.querySelector('.sf-rec-book');
    var recWa = root.querySelector('.sf-rec-wa');
    var total = panels.length, cur = 1, answers = {};

    var recs = {
        boxes: { t: 'Small Unit', d: 'A small unit is ideal for boxes, documents, luggage and seasonal belongings.' },
        room: { t: 'Small–Medium Unit', d: 'About a room\u2019s worth of items — a small-to-medium unit should fit comfortably.' },
        home: { t: 'Medium Unit', d: 'A medium unit suits the contents of a one or two-bedroom home, including beds, sofas and appliances.' },
        full: { t: 'Large / Warehouse', d: 'For a full household or bulk stock, a large unit or warehouse space is the right fit.' }
    };

    function show(n) {
        cur = n;
        panels.forEach(function (p) { p.hidden = (parseInt(p.getAttribute('data-step'), 10) !== n); });
        result.hidden = true;
        stepNum.textContent = n;
        progress.style.width = Math.round((n / total) * 100) + '%';
        backBtn.hidden = (n === 1);
        restartBtn.hidden = true;
    }

    function finish() {
        panels.forEach(function (p) { p.hidden = true; });
        var r = recs[answers.size] || recs.home;
        recTitle.textContent = r.t;
        recDesc.textContent = r.d;

        var q = '?type=' + encodeURIComponent(answers.store || '') +
                '&size=' + encodeURIComponent(answers.size || '') +
                '&location=' + encodeURIComponent(answers.loc || '');
        if (recBook) recBook.href = '/booking' + q;

        var msg = 'Hi Storage Keys, I used your size finder. Storing: ' + (answers.store || '') +
                  ', space: ' + (answers.size || '') +
                  ', location: ' + (answers.loc || '') +
                  '. Recommended: ' + r.t + '. Can you help with availability?';
        if (recWa) recWa.href = 'https://wa.me/971565018785?text=' + encodeURIComponent(msg);

        result.hidden = false;
        progress.style.width = '100%';
        stepNum.textContent = total;
        backBtn.hidden = false;
        restartBtn.hidden = false;
    }

    root.addEventListener('click', function (e) {
        var opt = e.target.closest('.sf-opt');
        if (opt) {
            answers[opt.getAttribute('data-key')] = opt.getAttribute('data-val');
            if (cur < total) show(cur + 1); else finish();
            return;
        }
        if (e.target.closest('.sf-back')) {
            if (!result.hidden) show(total);
            else if (cur > 1) show(cur - 1);
            return;
        }
        if (e.target.closest('.sf-restart')) { answers = {}; show(1); return; }
    });

    show(1);
})();

(function () {
    var tabs = document.getElementById('skLocTabs');
    if (!tabs) return;

    var section = tabs.closest('.sk-section');
    var panels = [].slice.call(section.querySelectorAll('.sk-locpanel'));

    tabs.addEventListener('click', function (e) {
        var b = e.target.closest('.sk-loctab');
        if (!b) return;
        var loc = b.getAttribute('data-loc');
        [].slice.call(tabs.querySelectorAll('.sk-loctab')).forEach(function (t) {
            t.classList.toggle('active', t === b);
        });
        panels.forEach(function (p) {
            p.classList.toggle('active', p.getAttribute('data-loc') === loc);
        });
    });
})();
