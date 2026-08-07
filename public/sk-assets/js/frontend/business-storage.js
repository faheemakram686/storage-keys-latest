/* ============================================================
   StorageKeys — Business Storage Page
   Scroll-reveal animations, tab switching, animated counters
============================================================ */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Scroll-reveal ---------- */
    function initScrollReveal() {
        var reveals = [].slice.call(document.querySelectorAll('.sk-reveal'));

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
            reveals.forEach(function (el) { el.classList.add('in'); });
        }
    }

    /* ---------- Growth-stage tabs (Startups / SMEs / Larger companies) ---------- */
    function initGrowthTabs() {
        var tabs = document.getElementById('svcGrowthTabs');
        if (!tabs) return;

        var section = tabs.closest('.sk-section');
        var panels = [].slice.call(section.querySelectorAll('.svc-tabpanel'));

        tabs.addEventListener('click', function (e) {
            var btn = e.target.closest('.svc-tab');
            if (!btn) return;

            var target = btn.getAttribute('data-g');

            [].slice.call(tabs.querySelectorAll('.svc-tab')).forEach(function (t) {
                t.classList.toggle('active', t === btn);
            });
            panels.forEach(function (p) {
                p.classList.toggle('active', p.getAttribute('data-g') === target);
            });
        });
    }

    /* ---------- Generic tab-sets (Industry selector + What-fits explorer) ---------- */
    function initGenericTabSets() {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.svc-tabset .svc-tab');
            if (!btn) return;

            var tabset = btn.closest('.svc-tabset');
            if (!tabset) return;

            var target = btn.getAttribute('data-target');

            [].slice.call(tabset.querySelectorAll('.svc-tab')).forEach(function (t) {
                t.classList.toggle('active', t === btn);
            });
            [].slice.call(tabset.querySelectorAll('.svc-tabpanel')).forEach(function (p) {
                p.classList.toggle('active', p.getAttribute('data-panel') === target);
            });
        });
    }

    /* ---------- Animated stat counters ---------- */
    function runCount(el) {
        var target = parseFloat(el.getAttribute('data-target')) || 0;

        if (reduceMotion) {
            el.textContent = target.toLocaleString();
            return;
        }

        var duration = 1500;
        var start = null;

        function tick(timestamp) {
            if (start === null) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(target * eased).toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = target.toLocaleString();
            }
        }

        requestAnimationFrame(tick);
    }

    function initCounters() {
        var counters = [].slice.call(document.querySelectorAll('.svc-count'));

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        runCount(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(function (el) { observer.observe(el); });
        } else {
            counters.forEach(runCount);
        }
    }

    /* ---------- Init ---------- */
    document.addEventListener('DOMContentLoaded', function () {
        try {
            initScrollReveal();
            initGrowthTabs();
            initGenericTabSets();
            initCounters();
        } catch (err) {
            // Fail gracefully: make sure content is visible even if JS errors out
            [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) {
                el.classList.add('in');
            });
        }
    });
})();

// home 


/* ============================================================
   StorageKeys — Self Storage Landing Page
   Scroll-reveal, animated counters, size finder, location tabs,
   mobile nav toggle
============================================================ */
(function () {
    'use strict';

    try {
        var rm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Scroll reveal ---------- */
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

        /* ---------- Animated counters ---------- */
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
        // Fail-safe: never leave revealed content hidden
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) { el.classList.add('in'); });
        [].slice.call(document.querySelectorAll('.sk-count')).forEach(function (el) { el.textContent = el.getAttribute('data-target'); });
    }
})();

/* ---------- Interactive Size Finder ---------- */
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
        if (recBook) recBook.href = '#' + q;

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

/* ---------- Location tabs ---------- */
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

/* Mobile nav toggle lives in ui.includes.header (shared by every page). */







// @personal-Storage-page


/* ============================================================
   StorageKeys — Personal Storage page
   Scroll-reveal, item picker (build-your-list), packing
   checklist, mobile nav toggle
============================================================ */
(function () {
    'use strict';

    try {
        var rm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Scroll reveal ---------- */
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

        /* ---------- Build-your-list item picker ---------- */
        var items = document.getElementById('psItems');
        if (items) {
            var countEl = document.getElementById('psCount');
            var listEl = document.getElementById('psList');
            var cartBtn = document.getElementById('psCartBtn');
            var quoteBtn = document.getElementById('psQuoteBtn');
            var tmp = document.createElement('textarea');

            function decode(s) {
                tmp.innerHTML = s;
                return tmp.value;
            }

            function refresh() {
                var sel = [].slice.call(items.querySelectorAll('.ps-item.active'))
                    .map(function (b) { return decode(b.getAttribute('data-item')); });

                countEl.textContent = sel.length;

                if (sel.length) {
                    listEl.textContent = sel.join(', ');
                    var q = '?storing=personal&items=' + encodeURIComponent(sel.join(', '));
                    if (cartBtn) cartBtn.href = '#ps-quote' + q;
                    if (quoteBtn) quoteBtn.href = '#ps-quote' + q;
                } else {
                    listEl.textContent = 'Tap the items above to build your list.';
                    if (cartBtn) cartBtn.href = '#ps-quote';
                    if (quoteBtn) quoteBtn.href = '#ps-quote';
                }
            }

            items.addEventListener('click', function (e) {
                var b = e.target.closest('.ps-item');
                if (!b) return;
                b.classList.toggle('active');
                refresh();
            });

            refresh();
        }

        /* ---------- Packing checklist ---------- */
        var check = document.getElementById('psCheck');
        if (check) {
            var lis = [].slice.call(check.querySelectorAll('li'));
            var doneEl = document.getElementById('psDone');
            var bar = document.getElementById('psBar');
            var msg = document.getElementById('psCheckDone');

            function upd() {
                var done = check.querySelectorAll('li.done').length;
                doneEl.textContent = done;
                bar.style.width = Math.round((done / lis.length) * 100) + '%';
                if (msg) msg.classList.toggle('show', done === lis.length);
            }

            check.addEventListener('click', function (e) {
                var li = e.target.closest('li');
                if (!li) return;
                li.classList.toggle('done');
                upd();
            });

            upd();
        }
    } catch (e) {
        // Fail-safe: never leave revealed content hidden
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) { el.classList.add('in'); });
    }
})();

/* ============================================================
   StorageKeys — Climate-Controlled Storage page
   Scroll-reveal, item risk explorer, climate simulator,
   mobile nav toggle
============================================================ */
(function () {
    'use strict';

    try {
        var rm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Scroll reveal ---------- */
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

        /* ---------- Item risk explorer ---------- */
        var items = document.getElementById('ccItems');
        if (items) {
            var data = {
                wood: { ic: 'fa-couch', t: 'Wooden Furniture, Antiques & Home Décor', r: 'Wood can expand, contract or warp when exposed to excessive heat and moisture.', p: 'Stable temperature and humidity keep timber and joints from cracking or warping.' },
                elec: { ic: 'fa-laptop', t: 'Electronics, Computers & Equipment', r: 'Heat and humidity degrade circuitry, screens and sensitive equipment over time.', p: 'A controlled environment keeps conditions stable, protecting delicate electronics.' },
                docs: { ic: 'fa-folder-open', t: 'Business Documents & Archived Records', r: 'Paper and ink yellow, warp and deteriorate in heat and humidity.', p: 'Climate control preserves contracts, financial records and archives from moisture damage.' },
                art: { ic: 'fa-palette', t: 'Artwork, Photographs & Collectibles', r: 'Colours, surfaces and finishes are highly sensitive to environmental change.', p: 'Steady conditions preserve artwork, photographs and collectibles in their original state.' },
                music: { ic: 'fa-guitar', t: 'Musical Instruments', r: 'Instruments react to temperature and humidity swings, affecting wood and tuning.', p: 'Controlled conditions protect the body, strings and playability of instruments.' },
                stock: { ic: 'fa-boxes', t: 'Premium & Sensitive Business Inventory', r: 'Fluctuating conditions raise the likelihood of damage to premium products and stock.', p: 'Temperature-controlled storage helps maintain product quality and sellable condition.' }
            };

            var icEl = document.getElementById('ccIc');
            var titleEl = document.getElementById('ccTitle');
            var riskEl = document.getElementById('ccRisk');
            var protEl = document.getElementById('ccProt');

            items.addEventListener('click', function (e) {
                var b = e.target.closest('.cc-it');
                if (!b) return;
                var d = data[b.getAttribute('data-k')];
                if (!d) return;

                [].slice.call(items.querySelectorAll('.cc-it')).forEach(function (t) {
                    t.classList.toggle('active', t === b);
                });

                icEl.innerHTML = '<i class="fas ' + d.ic + '"></i>';
                titleEl.textContent = d.t;
                riskEl.textContent = d.r;
                protEl.textContent = d.p;
            });
        }

        /* ---------- Climate simulator ---------- */
        var sim = document.getElementById('ccSimToggle');
        if (sim) {
            var std = {
                mild: { t: 32, h: 60, tb: 'cc-bar-warn', hb: 'cc-bar-warn', cls: 'warn', msg: '<i class="fas fa-triangle-exclamation"></i> Warm & humid — long-term risk to sensitive items' },
                summer: { t: 48, h: 85, tb: 'cc-bar-hot', hb: 'cc-bar-hot', cls: 'hot', msg: '<i class="fas fa-triangle-exclamation"></i> Extreme heat & humidity — belongings at risk' }
            };

            var tEl = document.querySelector('.cc-unit.standard .cc-t');
            var hEl = document.querySelector('.cc-unit.standard .cc-h');
            var tBar = document.querySelector('.cc-unit.standard .cc-tb');
            var hBar = document.querySelector('.cc-unit.standard .cc-hb');
            var status = document.getElementById('ccStdStatus');

            function apply(mode) {
                var d = std[mode];
                tEl.textContent = d.t + '°C';
                hEl.textContent = d.h + '%';
                tBar.style.width = Math.round(d.t / 50 * 100) + '%';
                hBar.style.width = d.h + '%';
                tBar.className = 'cc-tb ' + d.tb;
                hBar.className = 'cc-hb ' + d.hb;
                status.className = 'cc-status ' + d.cls;
                status.innerHTML = d.msg;
            }

            sim.addEventListener('click', function (e) {
                var b = e.target.closest('button');
                if (!b) return;
                [].slice.call(sim.querySelectorAll('button')).forEach(function (t) {
                    t.classList.toggle('active', t === b);
                });
                apply(b.getAttribute('data-mode'));
            });
        }
    } catch (e) {
        // Fail-safe: never leave revealed content hidden
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) { el.classList.add('in'); });
    }
})();
