/* ============================================================
   StorageKeys — Warehouse Storage page
   Scroll-reveal, capacity estimator, lease-vs-unit toggle,
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

        /* ---------- Capacity estimator ---------- */
        var range = document.getElementById('whRange');
        if (range) {
            var out = document.getElementById('whPallets');
            var typeEl = document.getElementById('whType');
            var descEl = document.getElementById('whDesc');
            var quote = document.getElementById('whQuote');
            var formVol = document.getElementById('whFormVolume');
            var cards = [].slice.call(document.querySelectorAll('.wh-type'));

            var tiers = [
                { min: 1, max: 3, key: 'small', t: 'Small Storage Unit', d: 'Suitable for archived records, boxed inventory, office equipment and smaller commercial stock.' },
                { min: 4, max: 10, key: 'medium', t: 'Medium Storage Space', d: 'A practical option for growing businesses storing palletised goods, retail inventory or business equipment.' },
                { min: 11, max: 25, key: 'large', t: 'Large Storage Unit', d: 'Designed for wholesalers, distributors and companies requiring substantial capacity for bulk inventory.' },
                { min: 26, max: 99, key: 'custom', t: 'Custom Storage Solution', d: 'Scalable space tailored to businesses with specialised inventory, changing requirements or unique needs.' }
            ];

            function update() {
                var v = parseInt(range.value, 10);
                out.textContent = v >= 40 ? '40+' : v;

                var tier = tiers[tiers.length - 1];
                for (var i = 0; i < tiers.length; i++) {
                    if (v >= tiers[i].min && v <= tiers[i].max) { tier = tiers[i]; break; }
                }

                typeEl.textContent = tier.t;
                descEl.textContent = tier.d;
                if (quote) quote.href = '#warehouse-quote?storing=warehouse&volume=' + tier.key + '&pallets=' + v;
                if (formVol) formVol.value = tier.key;

                cards.forEach(function (c) {
                    var mn = parseInt(c.getAttribute('data-min'), 10);
                    var mx = parseInt(c.getAttribute('data-max'), 10);
                    c.classList.toggle('active', v >= mn && v <= mx);
                });

                var pct = ((v - range.min) / (range.max - range.min)) * 100;
                range.style.background = 'linear-gradient(90deg, var(--sk-accent) ' + pct + '%, var(--sk-line) ' + pct + '%)';
            }

            range.addEventListener('input', update);
            update();
        }

        /* ---------- Lease-vs-unit toggle ---------- */
        var sw = document.getElementById('whSwitch');
        if (sw) {
            var section = sw.closest('.sk-section');
            var panels = [].slice.call(section.querySelectorAll('.wh-vspanel'));

            sw.addEventListener('click', function (e) {
                var b = e.target.closest('button');
                if (!b) return;
                var v = b.getAttribute('data-v');

                [].slice.call(sw.querySelectorAll('button')).forEach(function (t) {
                    var on = t === b;
                    t.classList.toggle('active', on);
                    t.classList.toggle('win', on && t.getAttribute('data-v') === 'unit');
                });
                panels.forEach(function (p) {
                    p.classList.toggle('active', p.getAttribute('data-v') === v);
                });
            });
        }
    } catch (e) {
        // Fail-safe: never leave revealed content hidden
        [].slice.call(document.querySelectorAll('.sk-reveal')).forEach(function (el) { el.classList.add('in'); });
    }
})();

/* Mobile nav toggle lives in ui.includes.header (shared by every page). */