(function () {
    try {
        // Flip cards (tap / click / keyboard)
        [].slice.call(document.querySelectorAll('.rs-flip')).forEach(function (card) {
            function toggle() {
                card.classList.toggle('flipped');
            }
            card.addEventListener('click', toggle);
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle();
                }
            });
        });

        // Space estimator
        var steps = [].slice.call(document.querySelectorAll('.rs-step'));
        if (!steps.length) return;

        var recEl = document.getElementById('rsRec');
        var descEl = document.getElementById('rsRecDesc');
        var fillEl = document.getElementById('rsFill');
        var estBtn = document.getElementById('rsEstBtn');

        var tiers = [
            {
                max: 0,
                key: 'none',
                t: 'Start adding items',
                d: 'Use the + buttons to add your furniture, boxes and appliances, and we’ll estimate the space you need.',
                pct: 8
            },
            {
                max: 6,
                key: 'few',
                t: 'A Few Items',
                d: 'A compact unit is ideal for a handful of pieces or a few boxes.',
                pct: 25
            },
            {
                max: 15,
                key: 'studio',
                t: 'Studio / 1-Bedroom',
                d: 'Room for the contents of a studio or one-bedroom apartment.',
                pct: 50
            },
            {
                max: 30,
                key: 'apartment',
                t: 'Full Apartment',
                d: 'Space for a larger apartment’s furniture, appliances and boxes.',
                pct: 75
            },
            {
                max: 999,
                key: 'villa',
                t: 'Villa / Container Rental',
                d: 'For a villa or a complete home move, a large unit or residential storage container rental keeps everything together.',
                pct: 100
            }
        ];

        function refresh() {
            var score = 0;
            steps.forEach(function (s) {
                score += (parseInt(s.querySelector('.val').textContent, 10) || 0) * parseFloat(s.getAttribute('data-w'));
            });
            var tier = tiers[tiers.length - 1];
            for (var i = 0; i < tiers.length; i++) {
                if (score <= tiers[i].max) {
                    tier = tiers[i];
                    break;
                }
            }
            if (recEl) recEl.textContent = tier.t;
            if (descEl) descEl.textContent = tier.d;
            if (fillEl) fillEl.style.width = tier.pct + '%';
            if (estBtn) estBtn.href = '#rs-quote';
        }

        steps.forEach(function (s) {
            var val = s.querySelector('.val');
            s.querySelectorAll('.rs-ctrl button').forEach(function (b) {
                b.addEventListener('click', function () {
                    var n = (parseInt(val.textContent, 10) || 0) + parseInt(b.getAttribute('data-d'), 10);
                    if (n < 0) n = 0;
                    if (n > 99) n = 99;
                    val.textContent = n;
                    refresh();
                });
            });
        });

        refresh();
    } catch (e) {
        // no-op
    }
})();
