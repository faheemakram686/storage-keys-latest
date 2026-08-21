/* ============================================================
   StorageKeys — Furniture Storage page
   Interactive space estimator (#estimator)
============================================================ */
(function () {
    'use strict';

    var list = document.getElementById('catList');
    if (!list) return;

    var fill = document.getElementById('unitFill');
    var sizeEl = document.getElementById('recSize');
    var descEl = document.getElementById('recDesc');
    var totalEl = document.getElementById('itemTotal');
    var quoteBtn = document.getElementById('fsQuoteBtn');

    /* Weight sum → recommended unit (approx. sq ft / room scale) */
    var tiers = [
        {
            max: 0,
            size: 'Add your items',
            desc: 'Tap a counter on the left to start building your estimate — the fill shows roughly how much of a unit your furniture would take up.',
            fill: 0,
            key: ''
        },
        {
            max: 14,
            size: 'Small Unit (~25–50 sq ft)',
            desc: 'A compact unit for a few seating pieces or a small bedroom set — ideal when you only need to clear one room temporarily.',
            fill: 28,
            key: 'small'
        },
        {
            max: 28,
            size: 'Medium Unit (~75–100 sq ft)',
            desc: 'Comfortable space for a living-room set plus beds or dining furniture — the most popular choice for moves and renovations.',
            fill: 52,
            key: 'medium'
        },
        {
            max: 48,
            size: 'Large Unit (~125–150 sq ft)',
            desc: 'Room for a full household furniture mix — sofas, wardrobes, beds and tables — without overcrowding the unit.',
            fill: 74,
            key: 'large'
        },
        {
            max: Infinity,
            size: 'Extra-Large / Multi-Unit',
            desc: 'Substantial volume for a whole home or office clear-out. We’ll help you split across units or recommend a custom layout.',
            fill: 92,
            key: 'xlarge'
        }
    ];

    function getTier(weight) {
        for (var i = 0; i < tiers.length; i++) {
            if (weight <= tiers[i].max) return tiers[i];
        }
        return tiers[tiers.length - 1];
    }

    function update() {
        var rows = list.querySelectorAll('.cat-row');
        var weight = 0;
        var items = 0;

        rows.forEach(function (row) {
            var countEl = row.querySelector('.step-count');
            var count = parseInt(countEl.textContent, 10) || 0;
            var w = parseFloat(row.getAttribute('data-weight')) || 0;
            weight += count * w;
            items += count;
            row.classList.toggle('has-items', count > 0);
        });

        var tier = getTier(weight);
        var fillPct = weight === 0
            ? 0
            : Math.min(100, Math.round((weight / 60) * 100));

        if (fill) fill.style.width = (tier.fill || fillPct) + '%';
        if (sizeEl) sizeEl.textContent = tier.size;
        if (descEl) descEl.textContent = tier.desc;
        if (totalEl) {
            totalEl.textContent = items === 1
                ? '1 item selected'
                : items + ' items selected';
        }
        if (quoteBtn && tier.key) {
            quoteBtn.href = '#ps-quote';
        }
    }

    list.addEventListener('click', function (e) {
        var btn = e.target.closest('.step-btn');
        if (!btn || !list.contains(btn)) return;

        var row = btn.closest('.cat-row');
        var countEl = row.querySelector('.step-count');
        var count = parseInt(countEl.textContent, 10) || 0;
        var action = btn.getAttribute('data-action');

        if (action === 'inc') count += 1;
        if (action === 'dec') count = Math.max(0, count - 1);

        countEl.textContent = String(count);
        update();
    });

    update();
})();
