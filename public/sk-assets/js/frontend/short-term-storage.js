(function () {
    try {
        var range = document.getElementById('stRange');
        if (range) {
            var types = document.getElementById('stTypes');
            var periodEl = document.getElementById('stPeriod');
            var planText = document.getElementById('stPlanText');
            var planBtn = document.getElementById('stPlanBtn');
            var typeKey = 'compact';
            var typeRec = {
                compact: 'a compact short-term unit or luggage storage',
                home: 'a larger short-term unit for furniture, appliances and household cartons',
                commercial: 'temporary commercial storage for stock, equipment or supplies'
            };

            function periodInfo(w) {
                if (w <= 1) return { label: 'About a week', arr: 'on a flexible short-stay arrangement' };
                if (w <= 4) return { label: w + ' weeks', arr: 'on a flexible weekly arrangement' };
                if (w <= 12) return { label: 'About ' + Math.round(w / 4) + ' months', arr: 'on a flexible monthly arrangement' };
                if (w <= 26) return { label: 'Around 3–6 months', arr: 'on a monthly arrangement you can extend or end as plans firm up' };
                return { label: '6–12+ months', arr: 'on a longer monthly arrangement, reviewed as your plans settle' };
            }

            function refresh() {
                var w = parseInt(range.value, 10);
                var pi = periodInfo(w);
                if (periodEl) periodEl.textContent = pi.label;
                if (planText) {
                    planText.innerHTML = '<b>' + pi.label + '</b> · ' + typeRec[typeKey] + ', ' + pi.arr + '.';
                }
                if (planBtn) planBtn.href = '#st-quote';
                var pct = ((w - range.min) / (range.max - range.min)) * 100;
                range.style.background = 'linear-gradient(90deg, var(--sk-accent) ' + pct + '%, var(--sk-line) ' + pct + '%)';
            }

            range.addEventListener('input', refresh);
            if (types) {
                types.addEventListener('click', function (e) {
                    var b = e.target.closest('.st-type');
                    if (!b) return;
                    typeKey = b.getAttribute('data-k') || 'compact';
                    [].slice.call(types.querySelectorAll('.st-type')).forEach(function (t) {
                        t.classList.toggle('active', t === b);
                    });
                    refresh();
                });
            }
            refresh();
        }

        var nodesWrap = document.getElementById('stNodes');
        if (nodesWrap) {
            var nodes = [].slice.call(nodesWrap.querySelectorAll('.st-node'));
            var prog = document.getElementById('stProg');
            var panel = document.getElementById('stPanel');
            var prev = document.getElementById('stPrev');
            var next = document.getElementById('stNext');
            var steps = [
                { ic: 'fa-comments', h: 'Tell us your need', p: "Share what you'd like to store, roughly how much, and your expected dates. Providing these details helps us understand the requirement first." },
                { ic: 'fa-clipboard-list', h: 'We suggest an arrangement', p: 'We recommend a unit sized to your belongings and matched to your period — a smaller unit for boxes and luggage, or a larger one for a furnished home or commercial stock.' },
                { ic: 'fa-warehouse', h: 'Store for your period', p: 'Your belongings stay in a suitable space for as long as you need — whether that is measured in weeks or months.' },
                { ic: 'fa-box-open', h: 'Collect when ready', p: 'Retrieve your items when your situation resolves — the move completes, the renovation ends, or you return from travel.' }
            ];
            var cur = 0;

            function render(i) {
                cur = i;
                nodes.forEach(function (n, idx) {
                    n.classList.toggle('active', idx === i);
                    n.classList.toggle('done', idx < i);
                });
                if (prog) {
                    prog.style.width = (nodes.length > 1 ? (i / (nodes.length - 1)) * 76 : 0) + '%';
                }
                if (panel) {
                    var s = steps[i];
                    panel.innerHTML = '<div class="ic"><i class="fas ' + s.ic + '"></i></div><div><h3>' + s.h + '</h3><p>' + s.p + '</p></div>';
                }
                if (prev) prev.style.visibility = i === 0 ? 'hidden' : 'visible';
                if (next) {
                    next.innerHTML = (i === nodes.length - 1
                        ? 'Start over <i class="fas fa-redo"></i>'
                        : 'Next <i class="fas fa-arrow-right"></i>');
                }
            }

            nodesWrap.addEventListener('click', function (e) {
                var n = e.target.closest('.st-node');
                if (!n) return;
                render(parseInt(n.getAttribute('data-i'), 10));
            });
            if (prev) prev.addEventListener('click', function () { if (cur > 0) render(cur - 1); });
            if (next) next.addEventListener('click', function () { render(cur >= nodes.length - 1 ? 0 : cur + 1); });
            render(0);
        }
    } catch (e) {
        /* no-op */
    }
})();
