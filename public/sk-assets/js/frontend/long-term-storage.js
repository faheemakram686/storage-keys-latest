(function () {
    try {
        var homeItems = document.getElementById('ltHomeItems');
        var storeItems = document.getElementById('ltStoreItems');
        if (!homeItems || !storeItems) return;

        var homeN = document.getElementById('ltHomeN');
        var storeN = document.getElementById('ltStoreN');
        var count = document.getElementById('ltCount');
        var listEl = document.getElementById('ltList');
        var emptyEl = document.getElementById('ltEmpty');
        var btn = document.getElementById('ltSortBtn');
        var quoteBtn = document.getElementById('ltQuoteBtn');
        var tmp = document.createElement('textarea');

        function decode(s) {
            tmp.innerHTML = s;
            return tmp.value;
        }

        function refresh() {
            var stored = [].slice.call(storeItems.querySelectorAll('.lt-chip'));
            var homeCount = homeItems.querySelectorAll('.lt-chip').length;
            if (homeN) homeN.textContent = homeCount;
            if (storeN) storeN.textContent = stored.length;
            if (count) count.textContent = stored.length;
            if (emptyEl) emptyEl.style.display = stored.length ? 'none' : '';
            if (stored.length) {
                var names = stored.map(function (c) {
                    return decode(c.getAttribute('data-v'));
                });
                if (listEl) listEl.textContent = names.join(', ');
                var q = '#lt-quote?storing=long-term&items=' + encodeURIComponent(names.join(', '));
                if (btn) btn.setAttribute('href', q);
                if (quoteBtn) quoteBtn.setAttribute('href', q);
            } else {
                if (listEl) {
                    listEl.textContent =
                        'A compact unit may suit a few boxes; furniture, appliances and inventory need more room.';
                }
                if (btn) btn.setAttribute('href', '#lt-quote');
                if (quoteBtn) quoteBtn.setAttribute('href', '#lt-quote');
            }
        }

        function wire(chip) {
            chip.addEventListener('click', function () {
                var act = chip.querySelector('.act');
                var toStore = chip.parentNode === homeItems;
                if (toStore) {
                    storeItems.appendChild(chip);
                    if (act) act.className = 'fas fa-times act';
                } else {
                    homeItems.appendChild(chip);
                    if (act) act.className = 'fas fa-arrow-right act';
                }
                refresh();
            });
        }

        [].slice.call(document.querySelectorAll('.lt-chip')).forEach(wire);
        refresh();
    } catch (e) {
        /* no-op */
    }
})();
